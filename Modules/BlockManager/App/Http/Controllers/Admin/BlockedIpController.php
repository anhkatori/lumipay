<?php

namespace Modules\BlockManager\App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\BlockManager\App\Models\BlockedIp;

class BlockedIpController extends Controller
{
    public function index(Request $request)
    {
        $searchIpBan = $request->input('search_ip_ban');
        $blockedIps = BlockedIp::when($searchIpBan, function ($query) use ($searchIpBan) {
            $query->where('ip_ban', 'like', '%' . $searchIpBan . '%');
        })->paginate(10);
        return view('blockmanager::admin.blocked-ip.index', compact('blockedIps'));
    }
    public function destroy(BlockedIp $blockedIp)
    {
        $blockedIp->delete();
        return redirect()->route('admin.blocked-ip.index')->with('success', 'Blocked IP deleted successfully.');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'ip_ban' => 'required',
            'sort_ip' => 'required',
        ]);

        BlockedIp::create($validatedData);

        return redirect()->route('admin.blocked-ip.index')->with('success', 'Blocked IP created successfully');
    }
}