<?php

namespace Modules\StripeManager\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\StripeManager\App\Models\StripeAccount;
use Modules\ClientManager\App\Models\Client;

class StripeAccountController extends Controller
{
    public function index(Request $request)
    {
        $limit = $request->get('limit', 10);
        $limit = $this->validateLimit($limit);
        $stripeAccounts = StripeAccount::paginate($limit);
        return view('stripemanager::admin.account.index', compact('stripeAccounts'));
    }

    public function create()
    {
        $clients = Client::get();
        return view('stripemanager::admin.account.form', compact('clients'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'domain' => 'required',
            'max_receive_amount' => 'required|numeric',
            'current_amount' => 'required|numeric',
            'max_order_receive_amount' => 'required|numeric',
            'status' => 'required',
            'client_ids' => 'required|array'
        ]);
        $data['client_ids'] = implode(',', $data['client_ids']);

        StripeAccount::create($data);

        return redirect()->route('admin.stripe-accounts.index')->with('success', 'Stripe Account created successfully.');
    }

    public function show(StripeAccount $stripeAccount)
    {
        return view('stripemanager::admin.account.show', compact('stripeAccount'));
    }

    public function edit(StripeAccount $stripeAccount)
    {
        $clients = Client::get();
        return view('stripemanager::admin.account.form', compact('stripeAccount', 'clients'));
    }

    public function update(Request $request, StripeAccount $stripeAccount)
    {
        $data = $request->validate([
            'domain' => 'required',
            'max_receive_amount' => 'required|numeric',
            'current_amount' => 'required|numeric',
            'max_order_receive_amount' => 'required|numeric',
            'status' => 'required',
            'client_ids' => 'required|array'
        ]);
        $data['client_ids'] = implode(',', $data['client_ids']);

        $stripeAccount->update($data);

        return redirect()->route('admin.stripe-accounts.index')->with('success', 'Stripe Account updated successfully.');
    }

    public function destroy(StripeAccount $stripeAccount)
    {
        $stripeAccount->delete();

        return redirect()->route('admin.stripe-accounts.index')->with('success', 'Stripe Account deleted successfully.');
    }
}
