<?php

namespace Modules\AirwalletManager\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\AirwalletManager\App\Models\AirwalletAccount;

class AirwalletAccountController extends Controller
{
    public function index(Request $request)
    {
        $limit = $request->get('limit', 10);
        $limit = $this->validateLimit($limit);
        $airwalletAccounts = AirwalletAccount::paginate($limit);
        return view('airwalletmanager::admin.account.index', compact('airwalletAccounts'));
    }

    public function create()
    {
        return view('airwalletmanager::admin.account.form');
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

        AirwalletAccount::create($data);

        return redirect()->route('admin.airwallet-accounts.index')->with('success', 'Airwallet Account created successfully.');
    }

    public function show(AirwalletAccount $airwalletAccount)
    {
        return view('airwalletmanager::admin.account.show', compact('airwalletAccount'));
    }

    public function edit(AirwalletAccount $airwalletAccount)
    {
        return view('airwalletmanager::admin.account.form', compact('airwalletAccount'));
    }

    public function update(Request $request, AirwalletAccount $airwalletAccount)
    {
        $data = $request->validate([
            'domain' => 'required',
            'max_receive_amount' => 'required|numeric',
            'current_amount' => 'required|numeric',
            'max_order_receive_amount' => 'required|numeric',
            'status' => 'required'
        ]);

        $airwalletAccount->update($data);

        return redirect()->route('admin.airwallet-accounts.index')->with('success', 'Airwallet Account updated successfully.');
    }

    public function destroy(AirwalletAccount $airwalletAccount)
    {
        $airwalletAccount->delete();

        return redirect()->route('admin.airwallet-accounts.index')->with('success', 'Airwallet Account deleted successfully.');
    }

    protected function validateLimit($limit)
    {
        $limit = intval($limit); 
        if ($limit < 1) {
            $limit = 10; 
        }
        if ($limit > 100) {
            $limit = 100; 
        }

        return $limit;
    }
    
}