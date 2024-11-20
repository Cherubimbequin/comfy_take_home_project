<?php

namespace App\Http\Controllers;

use App\Models\PolicyManager;
use App\Models\User;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $agentsCount = User::where('role', 2)->count();
    $clientsCount = User::where('role', 1)->count();
    $policiesSoldCount = PolicyManager::where('status', 'Active')->count();
    $expiredPoliciesCount = PolicyManager::where('status', 'Expired')->count();

        return view('admin.index',  compact('agentsCount', 'clientsCount', 'policiesSoldCount', 'expiredPoliciesCount'));
    }
}
