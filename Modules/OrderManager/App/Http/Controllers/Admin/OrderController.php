<?php

namespace Modules\OrderManager\App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\OrderManager\App\Models\Order;
use Modules\ClientManager\App\Models\Client;
use Modules\PayPalManager\App\Models\PaypalAccount;
use Modules\StripeManager\App\Models\StripeAccount;
use Modules\AirwalletManager\App\Models\AirwalletAccount;
use Modules\BlockManager\App\Models\BlockedIp;
use Modules\BlockManager\App\Models\BlockedEmail;
use Modules\PayPalManager\Helper\Data as HelperPaypal;
use Illuminate\Support\Facades\Log;
use \Carbon\Carbon;
use Log as Log2;


class OrderController extends Controller
{
    /**
     * Display a listing of the orders.
     */
    public function index(Request $request)
    {
        $limit = $request->get('limit', 10);
        $limit = $this->validateLimit($limit);

        $orders = Order::when($request->get('request_id'), function ($query) use ($request) {
            $query->where('request_id', 'like', '%' . $request->get('request_id') . '%');

        })
            ->when($request->get('email'), function ($query) use ($request) {
                $query->where('email', 'like', '%' . $request->get('email') . '%');
            })
            ->when($request->get('status'), function ($query) use ($request) {
                $query->where('status', $request->get('status'));
            })
            ->when($request->get('method_account'), function ($query) use ($request) {
                $query->where(function ($query) use ($request) {
                    $query->where('method', 'PAYPAL')
                        ->whereHas('paypalAccount', function ($query) use ($request) {
                            $query->where('email', 'like', '%' . $request->get('method_account') . '%');
                        })
                        ->orWhere('method', 'CREDIT_CARD')
                        ->whereHas('stripeAccount', function ($query) use ($request) {
                            $query->where('domain', 'like', '%' . $request->get('method_account') . '%');
                        })
                        ->orWhere('method', 'CREDIT_CARD_2')
                        ->whereHas('airwalletAccount', function ($query) use ($request) {
                            $query->where('domain', 'like', '%' . $request->get('method_account') . '%');
                        });
                });
            })
            ->with('paypalAccount', 'stripeAccount', 'airwalletAccount')
            ->orderBy('id', 'desc')
            ->paginate($limit);

       $availableStatuses = $this->getAvailableStatuses();
        
        return view('ordermanager::admin.index', compact('orders', 'availableStatuses'));
    }

    protected function getAvailableStatuses()
    {
        $result[''] = '--Please Select--';
        $dbQueries = \DB::table('orders')->distinct()->get(['status']);
        $order = new Order();
        foreach ($dbQueries as $record) {
            $result[$record->status] = $order->getOrderStatusLabel($record->status);
        }
        return $result;
    }

    /**
     * Remove the specified order from storage.
     */
    public function destroy($id)
    {
        $order = Order::find($id);
        if ($order) {
            $order->delete();
            return redirect()->route('admin.ordermanager.index')->with('success', 'Order deleted successfully.');
        } else {
            return redirect()->route('admin.ordermanager.index')->with('error', 'Order not found.');
        }
    }

    /**
     * Remove the specified order from storage.
     */
    public function dispute($id)
    {
        $order = Order::find($id);
        if ($order) {
            $email = $order->email;
            $ip = $order->ip;
            $blockedIp = BlockedIp::where('ip_ban', $ip)->first();
            $blockedEmail = BlockedEmail::where('email', $email)->first();

            if (!$blockedIp) {
                BlockedIp::create(
                    [
                        'ip_ban' => $ip, 
                        'sort_ip' => substr($ip, 0, strrpos($ip, '.'))
                    
                    ]);
            }
            if (!$blockedEmail) {
                BlockedEmail::create([
                    'email' => $email, 
                    'name' => '', 
                    'status_delete' => '0'
                ]);
            }
            $order['status'] = 'dispute';
            $order->save();

            return redirect()->route('admin.ordermanager.index')->with('success', 'This email and IP have been blocked.');
        } else {
            return redirect()->route('admin.ordermanager.index')->with('error', 'Order not found.');
        }
    }

    public function closeDispute($id)
    {
        $order = Order::find($id);
        if ($order) {
            $order['status'] = 'close_dispute';
            $order->save();

            return redirect()->route('admin.ordermanager.index')->with('success', 'Closed dispute for this order');
        } else {
            return redirect()->route('admin.ordermanager.index')->with('error', 'Order not found.');
        }
    }

    public function apiCheck(Request $request)
    {
        return response()->json([
            'status' => 200,
            'message' => 'Success',
            'data' => $request->all()
        ]);
    }

    public function storeOrder(Request $request)
    {
        $authorizationHeader = $request->header('Authorization');
        preg_match('/client_id=([^&]+)/', $authorizationHeader, $match);
        $clientId = $match[1];

        $client = Client::where('merchant_id', $clientId)->first();

        $params = $request->all();
        ksort($params);
        if (!$this->checkBlockedIpAndEmail($params['ip'], $params['email'])) {
            return response()->json([
                'status' => 'error',
                'message' => 'Email or IP blocked!',
                'payment_url' => '',
                'error' => '2'
            ]);
        }
        if ($methodData = $this->isValidMethod($params, $client)) {
            $params['status'] = 'processing';
            $params['client_id'] = (int) $client['id'];
            $params['method_account'] = $methodData['id'];
            $params['addtional'] = '';
            if (isset($params['country_code'])) {
                $params['addtional'] .= $params['country_code'];
            }
            if (isset($params['country_name'])) {
                $params['addtional'] .= ' | ' . $params['country_name'];
            }
            if (isset($params['ips'])) {
                $params['addtional'] .= ' | ' . $params['ips'];
            }

            $order = Order::where('client_id', $params['client_id'])
                ->where('request_id', $params['request_id'])
                ->first();

            if (!$order) {
                $order = Order::create(attributes: $params);
            }

            $signature = $this->generateSignatureToCheckWhenUpdate((string) $client->private_key, (string) $order['id']);
            $paymentType = 'site_fake';
            switch ($params['method']) {
                case 'PAYPAL':
                    $methodData->days_stopped = Carbon::now();
                    $methodData->save();
                    $orderCode = base64_encode($order['id'] . "-" . "12345" . "-" . url('/') . "-" . $order['amount'] . "-" . date(format: "Y/m/d H:i:s") . "-" . $signature);
                    $url =  $methodData['domain_site_fake'] . "?wc-ajax=tpaypal&hash=" . $orderCode;
                    if ($methodData->payment_method == 'invoice') {
                        $paymentType = 'invoice';
                        $token = HelperPaypal::getAccessToken($methodData->client_key,$methodData->secret_key);
                        $paypalRandom = PaypalAccount::whereNotNull('products')->inRandomOrder()->first();
                        $products = $paypalRandom->parseProductsToArray();
                        
                        $product = reset(array: $products);
                        $productName = isset($product['name']) ? $product['name']: '';
                        $productDescription = isset($product['description']) ? $product['description']: '';
                        $data = [
                            "detail" => (object) [
                                "currency_code" => "USD",
                                "reference" => 'Order #'.$params['request_id'],
                                "note" => $params['description'],
                                'memo' => $order['id']
                            ],
                            'primary_recipients' => [
                                [
                                    'billing_info' => (object) [
                                        'email_address' => $params['email']
                                    ]
                                ]                                
                            ],
                            "items" => [
                                [
                                    "name" =>  $productName,
                                    "description" => $productDescription,
                                    "quantity" => "1",
                                    "unit_amount" => (object) [
                                        "currency_code" => "USD",
                                        "value" => $params['amount']
                                    ],
                                    "unit_of_measure" => "QUANTITY"
                                ]
                            ]
                        ];
                    
                        $url = HelperPaypal::createAndSendInvoice($data, $token);
                    }
                    return response()->json([
                        'status' => 'success',
                        'message' => '',
                        'payment_url' => $url,
                        'payment_type' => $paymentType,
                        'error' => ''
                    ]);
                case 'CREDIT_CARD':
                    $orderCode = base64_encode($order['id'] . "-" . "12345" . "-" . url('/') . "-" . $order['amount'] . "-" . date(format: "Y/m/d H:i:s") . "-" . $params['email'] . "-" . $signature);
                    return response()->json([
                        'status' => 'success',
                        'message' => '',
                        'payment_url' => $methodData['domain'] . "?wc-ajax=stripe_redirect&hash=" . $orderCode,
                        'payment_type' => $paymentType,
                        'error' => ''
                    ]);
                case 'CREDIT_CARD_2':
                    $orderCode = base64_encode($order['id'] . "-" . "12345" . "-" . url('/') . "-" . $order['amount'] . "-" . date(format: "Y/m/d H:i:s") . "-" . $params['email'] . "-" . $signature);
                    return response()->json([
                        'status' => 'success',
                        'message' => '',
                        'payment_url' => $methodData['domain'] . "?wc-ajax=visa_magento_redirect&hash=" . $orderCode,
                        'payment_type' => $paymentType,
                        'error' => ''
                    ]);
            }
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Payment not valid',
                'payment_url' => '',
                'error' => '1'
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Payment not valid',
            'payment_url' => '',
            'error' => '2'
        ]);
    }
    protected function isValidMethod($params, $client)
    {
        if ($params['method'] && $params['method'] == 'PAYPAL') {
            $accounts = $client->paypalAccounts;
            $filteredAccounts = $accounts->filter(function ($account) use ($params) {
                return
                    $account->status_id == '1' &&
                    $params['amount'] <= $account->max_order_receive_amount &&
                    $account->max_receive_amount >= ($params['amount'] + $account->current_amount);
            });
        }
        if ($params['method'] && $params['method'] == 'CREDIT_CARD') {
            $accounts = $client->stripeAccounts;
            $filteredAccounts = $accounts->filter(function ($account) use ($params) {
                return
                    $account->status == '1' &&
                    $params['amount'] <= $account->max_order_receive_amount &&
                    $account->max_receive_amount >= ($params['amount'] + $account->current_amount);
            });
        }
        if ($params['method'] && $params['method'] == 'CREDIT_CARD_2') {
            
            $accounts = $client->airwalletAccounts;
            $filteredAccounts = $accounts->filter(function ($account) use ($params) {
                return
                    $account->status == '1' &&
                    $params['amount'] <= $account->max_order_receive_amount &&
                    $account->max_receive_amount >= ($params['amount'] + $account->current_amount);
            });
        }
        
        if ($filteredAccounts->count() > 0) {
            $accounts = [];
            foreach ($filteredAccounts as $account) {
                $accounts[$account->max_order_receive_amount][] = $account;
            }
            ksort($accounts);
            $accounts = reset($accounts);
            $int = rand(0,count($accounts)-1);
            return $accounts[$int] ? : $accounts[0];
        }
        return false;
    }

    public function checkStatusOrder(Request $request)
    {
        $params = $request->all();
        $id = $params['id'];
        $order = Order::find($id);
        if ($order && $order['status'] == 'processing') {
            return response()->json([
                'total' => $order['amount'],
                'status' => $order['status']
            ]);
        }
        return response()->json([
            'status' => 'error',
            'total' => ''
        ]);
    }

    public function updateStatusOrder(Request $request)
    {
        $params = $request->all();
        $id = $params['id'];
        $signatureToCheck = $params['signature'];
        $order = Order::find($id);
        $client = Client::find($order['client_id']);
        if (!$client || !$this->isValidSignature($client, $id, $signatureToCheck)) {
            return false;
        } else {
            if ($order['canceled'] != '1') {
                $order['status'] = 'complete';
            }
            if ($order->method == 'PAYPAL' && isset($order->method_account)) {
                $methodAccount = PaypalAccount::find($order->method_account);
                $methodAccount->active_amount += $order->amount;
                $methodAccount->save();
            }
            if ($order->method == 'CREDIT_CARD' && isset($order->method_account)) {
                $methodAccount = StripeAccount::find($order->method_account);
                $methodAccount->current_amount += $order->amount;
                $methodAccount->save();
            }
            if ($order->method == 'CREDIT_CARD_2' && isset($order->method_account)) {
                $methodAccount = AirwalletAccount::find($order->method_account);
                $methodAccount->current_amount += $order->amount;
                $methodAccount->save();
            }
            $order->save();
            $data = [
                "data" => [
                    "request_id" => $order['request_id']
                ]
            ];
            $signature = $this->generateSignature($client['private_key'], $data);
            $orderData = array(
                "data" => [
                    "request_id" => $order['request_id']
                ],
                "signature" => $signature
            );
            $ch = curl_init($order->notify_url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($orderData));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Content-Lenght: " . strlen(json_encode($orderData))));
            $result = curl_exec($ch);
        }
        // var_dump($result); die;
    }

    protected function isValidSignature($client, $params, $signature)
    {
        $sign = $this->generateSignatureToCheckWhenUpdate((string) $client->private_key, (string) $params);
        return hash_equals($signature, $sign);
    }
    private function generateSignatureToCheckWhenUpdate($secretKey, $params = [])
    {
        $signature = hash_hmac("sha256", base64_encode(json_encode($params)), $secretKey);
        return $signature;
    }
    private function generateSignature($secretKey, $params = [])
    {
        ksort($params);
        $signature = hash_hmac("sha256", base64_encode(json_encode($params['data'])), $secretKey);
        return $signature;
    }

    public function redirectOrder(Request $request)
    {
        $params = $request->all();
        $action = $params['action'];
        $id = $params['id'];
        $signature = $params['signature'];
        $order = Order::find($id);
        $client = Client::find($order['client_id']);
        if (!$client || !$this->isValidSignature($client, $id, $signature)) {
            return false;
        } else {
            if ($action == 'return') {
                $url = $order['return_url'];
            } elseif ($action == 'cancel_return') {
                $url = $order['cancel_url'];
                $order['canceled'] = '1';
                $order->save();
            }
            return $url;
        }
    }

    public function checkBlockedIpAndEmail($ip = null, $email = null)
    {
        $blockedIp = BlockedIp::where('ip_ban', $ip)->first();
        $sortIp = substr($ip, 0, strrpos($ip, '.'));
        $blockedIp = BlockedIp::where('ip_ban', $ip)
                                ->orWhere('sort_ip', $sortIp) 
                                ->first();    
                                
        $blockedEmail = BlockedEmail::where('email', $email)->where('status_delete', '0')->first();

        if ($blockedIp || $blockedEmail) {
            if ($blockedEmail && !$blockedIp) {
                BlockedIp::create(['ip_ban' => $ip, 'sort_ip' => '']);
            } elseif ($blockedIp && !$blockedEmail) {
                BlockedEmail::create(['email' => $email, 'name' => '', 'status_delete' => '0']);
            }
            return false;
        }


        return true;
    }
}
