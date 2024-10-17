<?php

namespace Modules\StripeManager\App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\StripeManager\App\Models\StripeAccount;
use Modules\StripeManager\App\Models\StripeMoney;
use Modules\ClientManager\App\Models\Client;

class StripeAccountController extends Controller
{
    public function index(Request $request)
    {
        $limit = $request->get('limit', 10);
        $limit = $this->validateLimit($limit);
        $stripeAccounts = StripeAccount::orderBy('status', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate($limit);
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


    public function sell(Request $request)
    {
        $stripeAccount = StripeAccount::find($request['account-id']);

        if (!$stripeAccount) {
            return redirect()->route('admin.stripe-accounts.index')->with('error', 'PayPal account not found.');
        }

        $moneyToSell = $request->input('money');

        if ($moneyToSell <= 0) {
            return redirect()->route('admin.stripe-accounts.index')->with('error', 'Invalid amount to sell.');
        }

        $stripeMoney = new StripeMoney();
        $stripeMoney->account_id = $request['account-id'];
        $stripeMoney->stripe_domain = $stripeAccount->domain;
        $stripeMoney->money = $moneyToSell;
        $stripeMoney->buyer_email = $request->input('buyer_email');
        $stripeMoney->buyer_name = $request->input('buyer_name');
        $stripeMoney->status = '0';
        $stripeMoney->save();

        $stripeAccount->current_amount += $moneyToSell;
        $stripeAccount->save();

        return redirect()->route('admin.stripe-accounts.index')->with('success', 'Stripe sell successfully.');
    }

    public function soldIndex(Request $request)
    {
        $limit = $request->get('limit', 10);
        $limit = $this->validateLimit($limit);
        $stripeMoneys = StripeMoney::when($request->get('stripe_domain'), function ($query) use ($request) {
            $query->where('stripe_domain', 'like', '%' . $request->get('stripe_domain') . '%');
        })
            ->when($request->get('buyer_email'), function ($query) use ($request) {
                $query->where('buyer_email', 'like', '%' . $request->get('buyer_email') . '%');
            })
            ->orderBy('id')
            ->paginate($limit);

        return view('stripemanager::admin.account.sold', compact('stripeMoneys'));
    }

    public function updateSold(Request $request, $id)
    {
        $stripeMoney = StripeMoney::find($id);
        $oldMoney = $stripeMoney->money;
        $stripeMoney->money = $request->input('money');
        $stripeMoney->buyer_email = $request->input('buyer_email');
        $stripeMoney->buyer_name = $request->input('buyer_name');
        $stripeMoney->status = $request->input('status') ? '1' : '0';
        $stripeMoney->save();

        $stripeAccount = StripeAccount::where('id', $stripeMoney->account_id)->first();
        if ($stripeAccount) {
            $stripeAccount->current_amount = $stripeAccount->current_amount + $stripeMoney->money - $oldMoney;
            $stripeAccount->save();
        }
        return redirect()->route('admin.stripe-moneys.sold-index')->with('success', 'Update successfully.');
    }
}
