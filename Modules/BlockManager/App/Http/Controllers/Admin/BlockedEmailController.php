<?php

namespace Modules\BlockManager\App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\BlockManager\App\Models\BlockedEmail;

class BlockedEmailController extends Controller
{
    public function index(Request $request)
    {
        $query = BlockedEmail::query();

        if ($request->has('email')) {
            $query->where('email', 'like', '%' . $request->input('email') . '%');
        }

        if ($request->has('status_lock') && $request->input('status_lock') != '') {
            $query->where('status_lock', $request->input('status_lock'));
        }

        $blockedEmails = $query->paginate(10);

        return view('blockmanager::admin.blocked-email.index', compact('blockedEmails'));
    }

    public function create()
    {
        return view('blockmanager::admin.blocked-email.form');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|email|unique:blocked_emails',
            'name' => 'required',
            'status_delete' => 'nullable|filled',
        ]);

        $validatedData['status_lock'] = $request->filled('status_lock') ? 1 : 0;
        $validatedData['status_delete'] = $request->filled('status_delete') ? 1 : 0;


        BlockedEmail::create($validatedData);

        return redirect()->route('admin.blocked-email.index')->with('success', 'Blocked Email created successfully');
    }

    public function edit($id)
    {
        $blockedEmail = BlockedEmail::find($id);

        return view('blockmanager::admin.blocked-email.form', compact('blockedEmail'));
    }

    public function update(Request $request, $id)
    {
        $blockedEmail = BlockedEmail::find($id);

        $validatedData = $request->validate([
            'email' => 'required|email',
            'name' => 'required',
            'status_delete' => 'nullable|filled',
        ]);

        $validatedData['status_delete'] = $request->filled('status_delete') ? 1 : 0;


        $blockedEmail->update($validatedData);

        return redirect()->route('admin.blocked-email.index')->with('success', 'Blocked Email updated successfully');
    }

    public function destroy($id)
    {
        $blockedEmail = BlockedEmail::find($id);

        $blockedEmail->delete();

        return redirect()->route('admin.blocked-email.index')->with('success', 'Blocked Email deleted successfully');
    }

    public function unblock($id)
    {

        $blockedEmail = BlockedEmail::find($id);
        $blockedEmail->status_delete = 1;
        $blockedEmail->save();

        return redirect()->route('admin.blocked-email.index')->with('success', 'Blocked Email is unblocked successfully');
    }

    public function block($id)
    {
        $blockedEmail = BlockedEmail::find($id);
        $blockedEmail->status_delete = 0;
        $blockedEmail->save();
        return redirect()->route('admin.blocked-email.index')->with('success', 'Blocked Email is blocked successfully');
    }

}