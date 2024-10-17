<?php

namespace Modules\AirwalletManager\App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\AirwalletManager\App\Models\AirwalletAccount;
use Modules\AirwalletManager\App\Models\AirwalletMoney;

class AirwalletMoneyController extends Controller
{   

    public function index(Request $request){
        $limit = $request->get('limit', 10);
        $limit = $this->validateLimit($limit);
        $airwalletMoneys = AirwalletMoney::when($request->get('domain'), function ($query) use ($request) {
            $query->where('domain', 'like', '%' . $request->get('domain') . '%');
        })
            ->when($request->get('buyer_email'), function ($query) use ($request) {
                $query->where('buyer_email', 'like', '%' . $request->get('buyer_email') . '%');
            })
            ->orderBy('id')
            ->paginate($limit);

        return view('airwalletmanager::admin.sold.index', compact('airwalletMoneys'));
    }

    public function update(AirwalletMoney $airwalletMoney, Request $request): RedirectResponse{
        if($airwalletMoney->status){
            return redirect()->back()->with('error', 'Withdrawn is activated');
        }
        $oldMoney = $airwalletMoney->money;
        $airwalletMoney->money = $request->input('money');
        $airwalletMoney->buyer_email = $request->input('buyer_email');
        $airwalletMoney->buyer_name = $request->input('buyer_name');
        $airwalletMoney->status = $request->input('status') ? '1' : '0';
        $airwalletMoney->save();

        $airwalletAccount = $airwalletMoney->account;
        $airwalletAccount->current_amount = $airwalletAccount->current_amount + $airwalletMoney->money - $oldMoney;
        $airwalletAccount->save();

        return redirect()->back()->with('success', 'Update successfully.');
    }

    public function sell(AirwalletAccount $airwalletAccount, Request $request) : RedirectResponse
    {
        if (!$airwalletAccount) {
            return redirect()->back()->with('error', 'Airwallet account not found.');
        }

        $moneyToSell = $request->input('money');

        if ($moneyToSell <= 0) {
            return redirect()->back()->with('error', 'Invalid amount to sell.');
        }

        $airwalletMoney = new AirwalletMoney();
        $airwalletMoney->account_id = $airwalletAccount->id;
        $airwalletMoney->domain = $airwalletAccount->domain;
        $airwalletMoney->money = $moneyToSell;
        $airwalletMoney->buyer_email = $request->input('buyer_email');
        $airwalletMoney->buyer_name = $request->input('buyer_name');
        $airwalletMoney->status = 0;
        $airwalletMoney->save();

        $airwalletAccount->current_amount += $moneyToSell;
        $airwalletAccount->save();

        return redirect()->back()->with('success', 'Airwallet sell successfully.');
    }


}
