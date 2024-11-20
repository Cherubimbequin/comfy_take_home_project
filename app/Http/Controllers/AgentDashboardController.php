<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\PolicyManager;

class AgentDashboardController extends Controller
{
    public function index()
    {

        $authUserId = auth()->id();

        // Count policies sold by the user (Active status assumed for sold)
        $policiesSoldByUserCount = PolicyManager::where('user_id', $authUserId)
            ->where('status', 'Active')
            ->count();
    
        // Count expired policies sold by the user
        $expiredPoliciesByUserCount = PolicyManager::where('user_id', $authUserId)
            ->where('status', 'Expired')
            ->count();
        return view('agent.index', compact('policiesSoldByUserCount', 'expiredPoliciesByUserCount'));
    }
}
