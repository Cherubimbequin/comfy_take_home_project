<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\PolicyManager;
use App\Http\Controllers\Controller;

class PolicyController extends Controller
{
    public function index(Request $request)
    {
        // Retrieve query parameters
        $filters = $request->only(['status', 'user_id', 'policy_type_id', 'start_date', 'end_date']);

        // Query policies with filters
        $query = PolicyManager::query();

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        if (isset($filters['policy_type_id'])) {
            $query->where('policy_type_id', $filters['policy_type_id']);
        }

        if (isset($filters['start_date'])) {
            $query->whereDate('start_date', '>=', $filters['start_date']);
        }

        if (isset($filters['end_date'])) {
            $query->whereDate('end_date', '<=', $filters['end_date']);
        }

        // Fetch policies with pagination
        $policies = $query->paginate(10);

        // Return response as JSON
        return response()->json([
            'success' => true,
            'data' => $policies,
        ]);
    }
}
