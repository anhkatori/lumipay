<?php

namespace Modules\StripeManager\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\StripeManager\App\Models\StripeAccount;

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
        return view('stripemanager::admin.account.form');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'domain' => 'required',
            'max_receive_amount' => 'required|numeric',
            'current_amount' => 'required|numeric',
            'max_order_receive_amount' => 'required|numeric',
            'status' => 'required'
        ]);

        StripeAccount::create($data);

        return redirect()->route('admin.stripe-accounts.index')->with('success', 'Stripe Account created successfully.');
    }

    public function show(StripeAccount $stripeAccount)
    {
        return view('stripemanager::admin.account.show', compact('stripeAccount'));
    }

    public function edit(StripeAccount $stripeAccount)
    {
        return view('stripemanager::admin.account.form', compact('stripeAccount'));
    }

    public function update(Request $request, StripeAccount $stripeAccount)
    {
        $data = $request->validate([
            'domain' => 'required',
            'max_receive_amount' => 'required|numeric',
            'current_amount' => 'required|numeric',
            'max_order_receive_amount' => 'required|numeric',
            'status' => 'required'
        ]);

        $stripeAccount->update($data);

        return redirect()->route('admin.stripe-accounts.index')->with('success', 'Stripe Account updated successfully.');
    }

    public function destroy(StripeAccount $stripeAccount)
    {
        $stripeAccount->delete();

        return redirect()->route('admin.stripe-accounts.index')->with('success', 'Stripe Account deleted successfully.');
    }
}
