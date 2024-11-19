<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user(); 
        $policyCount = $user->policies()->count(); 

        $expiringPoliciesCount = $user->policies()
        ->where('end_date', '>', Carbon::now()) // Policies not yet expired
        ->where('end_date', '<=', Carbon::now()->addDays(30)) // Policies expiring within 30 days
        ->count();

        return view('users.index', compact('policyCount', 'expiringPoliciesCount'));
    }
}
