<?php

namespace Modules\PayPalManager\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Modules\PayPalManager\App\Models\PaypalAccount;
use Modules\PayPalManager\Helper\Data as HelperPaypal;
use Modules\OrderManager\App\Models\Order;
use Modules\ClientManager\App\Models\Client;

class PayPalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function webhook(Request $request)
    {   
        Log::build([
            'driver' => 'single',
            'path' => storage_path('logs/paypal-ipn.log'),
        ])->info('fdfdfdfdfd');
        $requestBody = file_get_contents('php://input');

        if(!$requestBody) {
            http_response_code(200);
            exit();
        } 

        $headers = getallheaders();
        $headers = array_change_key_case($headers, CASE_UPPER);


        if(
            (!array_key_exists('PAYPAL-AUTH-ALGO', $headers)) ||
            (!array_key_exists('PAYPAL-TRANSMISSION-ID', $headers)) ||
            (!array_key_exists('PAYPAL-CERT-URL', $headers)) ||
            (!array_key_exists('PAYPAL-TRANSMISSION-SIG', $headers)) ||
            (!array_key_exists('PAYPAL-TRANSMISSION-TIME', $headers)) 
        )
        {
            http_response_code(200);
            exit();    
        }
        $requestBody = json_decode($requestBody, true);
        $args = [
            'auth_algo' => $headers['PAYPAL-AUTH-ALGO'],
            'cert_url' => $headers['PAYPAL-CERT-URL'],
            'transmission_id' => $headers['PAYPAL-TRANSMISSION-ID'],
            'transmission_sig' => $headers['PAYPAL-TRANSMISSION-SIG'],
            'transmission_time' => $headers['PAYPAL-TRANSMISSION-TIME'],
            'webhook_id' => $requestBody['id'],
            'webhook_event' => $requestBody
        ];
        $order = Order::find($args['webhook_event']['resource']['invoice']['detail']['memo']);
        $client = Client::find($order['client_id']);
        if ($order->method != 'PAYPAL' || !isset($order->method_account)) {
            exit();
        }
        $methodAccount = PaypalAccount::find($order->method_account);
        $token = HelperPaypal::getAccessToken($methodAccount->client_key,$methodAccount->secret_key);
        $verify = HelperPaypal::verifyWebhook($args, $token);  
        if($verify){
            if (isset($requestBody['event_type'])) {
                $event_type = $requestBody['event_type'];
                Log::build([
                    'driver' => 'single',
                    'path' => storage_path('logs/paypal-ipn.log'),
                ])->info($event_type);

                switch ($event_type) {
                    case 'INVOICING.INVOICE.PAID':
                        if ($order['canceled'] != '1') {
                            $order['status'] = 'complete';
                        }
                        if ($order->method == 'PAYPAL' && isset($order->method_account)) {
                            $methodAccount = PaypalAccount::find($order->method_account);
                            $methodAccount->active_amount += $order->amount;
                            $methodAccount->save();
                        }
                        $order->save();
                        $signature = $this->generateSignature($client['private_key'], [
                            "data" => [
                                "request_id" => $order['request_id']
                            ]
                        ]);
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
                        curl_setopt($ch, CURLOPT_HTTPHEADER, [
                            "Content-Type: application/json",
                            "Content-Lenght: " . strlen(json_encode($orderData))
                        ]);
                        curl_exec($ch);
                    default:
                    // Do Stuff...
                        break;
                }
            }
            http_response_code(200);
            exit();

        }
        else{
            http_response_code(200);
            exit();
        }
        // Log::build([
        //     'driver' => 'single',
        //     'path' => storage_path('logs/paypal-ipn.log'),
        //   ])->info("ip: $clientIp - key: $key");
        // return true;
    }

    private function generateSignature($secretKey, $params = [])
    {
        ksort($params);
        $signature = hash_hmac("sha256", base64_encode(json_encode($params['data'])), $secretKey);
        return $signature;
    }
}
