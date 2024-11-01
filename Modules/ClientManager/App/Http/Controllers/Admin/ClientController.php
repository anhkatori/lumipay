<?php

namespace Modules\ClientManager\App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\ClientManager\App\Models\Client;

class ClientController extends Controller
{
    /**
     * Display a listing of the clients.
     */
    public function index(Request $request)
    {
        $limit = $request->get('limit', 10);
        $limit = $this->validateLimit($limit);
        $clients = Client::when($request->get('only-trashed'), function ($query) use ($request) {
            $query->onlyTrashed();
        })
        ->when($request->get('status'), function ($query) use ($request) {
            $query->orderBy('status', strtolower($request->get('status')) == 'asc' ? 'ASC' : 'DESC');
        })
        ->orderBy('id', 'desc')
        ->paginate($limit);
        
        return view('clientmanager::admin.index', compact('clients'));
    }

    /**
     * Show the form for creating a new client.
     */
    public function create()
    {
        return view('clientmanager::admin.form');
    }

    /**
     * Store a newly created client in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:clients',
            'username' => 'required|unique:clients',
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'status' => 'nullable',
            'invoice_description' => 'nullable',
        ]);
        $validatedData['status'] = isset($validatedData['status']) ? 1 : 0;
        Client::create($validatedData);

        return redirect()->route('admin.clients.index')->with('success', 'Client created successfully.');
    }

    /**
     * Display the specified client.
     */
    public function show(Client $client)
    {
        return view('clientmanager::admin.show', compact('client'));
    }

    /**
     * Show the form for editing the specified client.
     */
    public function edit(Client $client, Request $request)
    {
        $limit = $request->get('limit', 10);
        $limit = $this->validateLimit($limit);

        $paypalAccounts = $client->paypalAccounts()->paginate($limit, ['*'], 'paypal_account')->setPageName('paypal_account');
        $stripeAccounts = $client->stripeAccounts()->paginate($limit, ['*'], 'stripe_account')->setPageName('stripe_account');
        $airwalletAccounts = $client->airwalletAccounts()->paginate($limit, ['*'], 'airwallet_account')->setPageName('airwallet_account');

        return view('clientmanager::admin.form', compact('client', 'paypalAccounts', 'stripeAccounts', 'airwalletAccounts'));
    }

    /**
     * Update the specified client in storage.
     */
    public function update(Request $request, Client $client)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:clients,email,' . $client->id,
            'username' => 'required|unique:clients,username,' . $client->id,
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'status' => 'nullable',
            'invoice_description' => 'nullable',
        ]);
        $validatedData['status'] = isset($validatedData['status']) ? 1 : 0;
        
        $client->update($validatedData);

        return redirect()->route('admin.clients.index')->with('success', 'Client updated successfully.');
    }

    /**
     * Remove the specified client from storage.
     */
    public function destroy(Client $client)
    {
        // $client->airwalletAccounts()->delete();
        // $client->paypalAccounts()->delete();
        // $client->stripeAccounts()->delete();

        $client->delete();

        return redirect()->route('admin.clients.index')->with('success', 'Client deleted successfully.');
    }

    public function apiCheck(Request $request){
        return response()->json([
            'status' => 200,
            'message' => 'Success',
            'data' => $request->all()
        ]);
    }

    public function restore($id)
    {
        $client = Client::onlyTrashed()->find($id);
        if ($client) {
            $client->restore();
            return redirect()->route('admin.clients.index', ['only-trashed' => 1])->with('success', 'Client restored successfully.');
        }

        return redirect()->route('admin.clients.index', ['only-trashed' => 1])->with('error', 'Client not found.');
    }

    public function status(Client $client, Request $request)
    {
        if ($client) {
            $client->update(['status' => $request->input('status')]);
            return redirect()->route('admin.clients.index')->with('success', 'Client change status successfully.');
        }

        return redirect()->route('admin.clients.index')->with('error', 'Client not found.');
    }
}
