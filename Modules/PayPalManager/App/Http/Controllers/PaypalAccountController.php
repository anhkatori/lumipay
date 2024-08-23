<?php

namespace Modules\PayPalManager\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\PayPalManager\App\Models\PaypalAccount;
use Modules\PayPalManager\App\Models\PaypalAccountStatus;
use Modules\ClientManager\App\Models\Client;

class PaypalAccountController extends Controller
{
    public function index(Request $request)
    {
        $limit = $request->get('limit', 10);
        $limit = $this->validateLimit($limit);
        $paypalAccounts = PaypalAccount::paginate($limit);

        return view('paypalmanager::admin.account.index', compact('paypalAccounts'));
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
            'days_stopped' => 'required|integer',
            'status_id' => 'required|exists:paypal_account_statuses,id',
            'description' => 'nullable',
            'payment_method' => 'required',
            'client_id' => 'required'
        ]);

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
            'domain_site_fake' => 'required',
            'max_receive_amount' => 'required|numeric',
            'active_amount' => 'required|numeric',
            'hold_amount' => 'required|numeric',
            'max_order_receive_amount' => 'required|numeric',
            'proxy' => 'nullable',
            'days_stopped' => 'required|integer',
            'status_id' => 'required|exists:paypal_account_statuses,id',
            'description' => 'nullable',
            'payment_method' => 'required',
            'client_id' => 'required'

        ]);

        $paypalAccount->update($data);

        return redirect()->route('admin.paypal-accounts.index')->with('success', 'PayPal Account updated successfully.');
    }

    public function destroy(PaypalAccount $paypalAccount)
    {
        $paypalAccount->delete();

        return redirect()->route('admin.paypal-accounts.index')->with('success', 'PayPal Account deleted successfully.');
    }
}
