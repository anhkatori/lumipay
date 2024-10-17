<?php

namespace Modules\PayPalManager\App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\PayPalManager\App\Models\PaypalAccount;
use Modules\PayPalManager\App\Models\PaypalMoney;
use Modules\PayPalManager\App\Models\PaypalAccountStatus;
use Modules\ClientManager\App\Models\Client;
use Log;
use Exception;
class PaypalAccountController extends Controller
{
    public function index(Request $request)
    {
        $limit = $request->get('limit', 10);
        $limit = $this->validateLimit($limit);
        $paypalAccounts = PaypalAccount::when($request->get('email'), function ($query) use ($request) {
            $query->where('email', 'like', '%' . $request->get('email') . '%');
        })
            ->when($request->get('proxy'), function ($query) use ($request) {
                $query->where('proxy', 'like', '%' . $request->get('proxy') . '%');
            })
            ->when($request->get('status'), function ($query) use ($request) {
                $query->whereHas('status', function ($query) use ($request) {
                    $query->where('name', $request->get('status'));
                });
            })
            ->when($request->get('domain_site_fake'), function ($query) use ($request) {
                $query->where('domain_site_fake', 'like', '%' . $request->get('domain_site_fake') . '%');
            })
            ->when($request->get('client'), function ($query) use ($request) {
                $clientIds = $request->get('client');
                if (is_array($clientIds)) {
                    $query->where(function ($query) use ($clientIds) {
                        foreach ($clientIds as $clientId) {
                            $query->orWhere('client_ids', 'like', '%' . $clientId . '%');
                        }
                    });
                } else {
                    $query->where('client_ids', 'like', '%' . $clientIds . '%');
                }
            })
            ->when($request->get('payment_type'), function ($query) use ($request) {
                $query->where('payment_method', $request->get('payment_type'));
            })
            ->orderBy('status_id')
            ->orderBy('id')
            ->paginate($limit);

        $statuses = PaypalAccountStatus::all();
        $paymentTypes = PaypalAccount::getPaymentMethods();
        $clients = Client::all();
        $selectedClients = $request->get('client', []);
        return view('paypalmanager::admin.account.index', compact('paypalAccounts', 'statuses', 'paymentTypes', 'clients', 'selectedClients'));
    }

    public function create()
    {
        $statuses = PaypalAccountStatus::get();
        $paymentMethods = PaypalAccount::getPaymentMethods();
        $clients = Client::get();

        return view('paypalmanager::admin.account.form', compact('statuses', 'paymentMethods', 'clients'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'domain_site_fake' => 'required',
            'max_receive_amount' => 'required|numeric',
            'active_amount' => 'required|numeric',
            'hold_amount' => 'required|numeric',
            'max_order_receive_amount' => 'required|numeric',
            'proxy' => 'nullable',
            'seller' => 'required',
            'domain_status' => 'nullable',
            'site_client' => 'required',
            'status_id' => 'required|exists:paypal_account_statuses,id',
            'description' => 'nullable',
            'xmdt_status' => 'nullable',
            'remover' => 'nullable',
            'payment_method' => 'required',
            'client_ids' => 'required|array'
        ]);
        $data['client_ids'] = implode(',', $data['client_ids']);
        $data['password'] = utf8_encode($data['password']);
        $data['xmdt_status'] = isset($data['xmdt_status']) ? now() : null;
        $data['domain_status'] = isset($data['domain_status']) ? 1 : 0;
        PaypalAccount::create($data);

        return redirect()->route('admin.paypal-accounts.index')->with('success', 'PayPal Account created successfully.');
    }

    public function show(PaypalAccount $paypalAccount)
    {
        // return view('paypalmanager::admin.account.show', compact('paypalAccount'));
    }

    public function edit(PaypalAccount $paypalAccount)
    {
        $statuses = PaypalAccountStatus::get();
        $paymentMethods = PaypalAccount::getPaymentMethods();
        $clients = Client::get();

        return view('paypalmanager::admin.account.form', compact('paypalAccount', 'statuses', 'paymentMethods', 'clients'));
    }

    public function update(Request $request, PaypalAccount $paypalAccount)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'client_key' => 'nullable',
            'secret_key' => 'nullable',
            'domain_site_fake' => 'required',
            'max_receive_amount' => 'required|numeric',
            'active_amount' => 'required|numeric',
            'hold_amount' => 'required|numeric',
            'max_order_receive_amount' => 'required|numeric',
            'proxy' => 'nullable',
            'seller' => 'required',
            'domain_status' => 'nullable',
            'site_client' => 'required',
            'status_id' => 'required|exists:paypal_account_statuses,id',
            'description' => 'nullable',
            'xmdt_status' => 'nullable',
            'remover' => 'nullable',
            'payment_method' => 'required',
            'client_ids' => 'required|array'
        ]);
        $data['client_ids'] = implode(',', $data['client_ids']);
        $data['password'] = utf8_encode($data['password']);
        $data['xmdt_status'] = isset($data['xmdt_status']) ? now() : null;
        $data['domain_status'] = isset($data['domain_status']) ? 1 : 0;
        $paypalAccount->update($data);

        return redirect()->route('admin.paypal-accounts.edit', $paypalAccount->id)->with('success', 'PayPal Account updated successfully.');
    }

    public function destroy(PaypalAccount $paypalAccount)
    {
        if ($paypalAccount->status_id != 6) {
            return redirect()->route('admin.paypal-accounts.index')->with('error', 'PayPal Account cannot be deleted.'); 
        }
        $paypalAccount->delete();
        return redirect()->route('admin.paypal-accounts.index')->with('success', 'PayPal Account deleted successfully.');
    }

    public function sell(Request $request)
    {
        $paypalAccount = PaypalAccount::find($request['account-id']);

        if (!$paypalAccount) {
            return redirect()->route('admin.paypal-accounts.index')->with('error', 'PayPal account not found.');
        }

        $moneyToSell = $request->input('money');

        if ($moneyToSell <= 0) {
            return redirect()->route('admin.paypal-accounts.index')->with('error', 'Invalid amount to sell.');
        }

        $paypalMoney = new PaypalMoney();
        $paypalMoney->account_id = $request['account-id'];
        $paypalMoney->paypal_email = $paypalAccount->email;
        $paypalMoney->money = $moneyToSell;
        $paypalMoney->buyer_email = $request->input('buyer_email');
        $paypalMoney->buyer_name = $request->input('buyer_name');
        $paypalMoney->save();

        $paypalAccount->active_amount += $moneyToSell;
        $paypalAccount->save();

        return redirect()->route('admin.paypal-accounts.index')->with('success', 'PayPal sell successfully.');
    }

    public function soldIndex(Request $request)
    {
        $limit = $request->get('limit', 10);
        $limit = $this->validateLimit($limit);
        $paypalMoneys = PaypalMoney::when($request->get('paypal_email'), function ($query) use ($request) {
            $query->where('paypal_email', 'like', '%' . $request->get('paypal_email') . '%');
        })
            ->when($request->get('buyer_email'), function ($query) use ($request) {
                $query->where('buyer_email', 'like', '%' . $request->get('buyer_email') . '%');
            })
            ->orderBy('id')
            ->paginate($limit);

        return view('paypalmanager::admin.account.sold', compact('paypalMoneys'));
    }

    public function updateSold(Request $request, $id)
    {
        $paypalMoney = PaypalMoney::find($id);
        $oldMoney = $paypalMoney->money;
        $paypalMoney->money = $request->input('money');
        $paypalMoney->buyer_email = $request->input('buyer_email');
        $paypalMoney->buyer_name = $request->input('buyer_name');
        $paypalMoney->save();

        $paypalAccount = PaypalAccount::where('id', $paypalMoney->account_id)->first();
        if ($paypalAccount) {
            $paypalAccount->active_amount = $paypalAccount->active_amount + $paypalMoney->money - $oldMoney;
            $paypalAccount->save();
        }
        return redirect()->route('admin.paypal-moneys.sold-index')->with('success', 'Update successfully.');
    }
}
