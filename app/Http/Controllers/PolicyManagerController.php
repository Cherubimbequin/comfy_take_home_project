<?php

namespace App\Http\Controllers;

use App\Models\Payments;
use App\Models\PolicyType;
use Illuminate\Http\Request;
use App\Models\PolicyManager;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\DataTables\PolicyManagerDataTable;

class PolicyManagerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(PolicyManagerDataTable $dataTable)
    {
        return $dataTable->render('users.pages.policyManager.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id)
    {
        $policyType = PolicyType::findOrFail($id);

        $latestPolicy = PolicyManager::latest()->first();
        $nextNumber = $latestPolicy ? ((int)substr($latestPolicy->policy_number, -6)) + 1 : 1;
        $policyNumber = 'POL-' . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
        return view('users.pages.policyManager.create', compact('policyType', 'policyNumber'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Log::info('Received request to create policy.', [
            'request_data' => $request->all(),
            'user_id' => auth()->id(),
        ]);
        $request->validate([
            'policy_number' => 'required|unique:policy_managers|max:255',
            'policy_type_id' => 'required|exists:policy_types,id',
            'premium_amount' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'next_of_kin' => 'required',
            'reference' => 'required|unique:payments',
        ]);

        // Verify Payment using Paystack API
        $paymentDetails = $this->verifyPayment($request->reference);

        if (!$paymentDetails) {
            return response()->json(['error' => 'Payment verification failed.'], 400);
        }

        try {
            // Create Policy
            $policy = PolicyManager::create([
                'policy_number' => $request->policy_number,
                'policy_type_id' => $request->policy_type_id,
                'premium_amount' => $request->premium_amount,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'next_of_kin' => $request->next_of_kin,
                'user_id' => auth()->id(),
                'status' => 'Active', // Activate upon payment
            ]);
            Log::info('Policy created successfully.', ['policy_id' => $policy->id]);
            // Log Payment
            Payments::create([
                'policy_id' => $policy->id,
                'reference' => $request->reference,
                'amount' => $request->premium_amount,
                'status' => 'Success',
            ]);
            Log::info('Payment logged successfully.');


            return response()->json(['message' => 'Policy and payment recorded successfully.']);
        } catch (\Exception $e) {
            Log::error('Error creating policy or payment: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred. Please try again.'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        $policyTypes = PolicyType::all();
        return view('users.pages.policyManager.grid', compact('policyTypes'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function verifyPayment($reference)
    {
        Log::info('Verifying payment with reference: ' . $reference);
    
        $paystackSecretKey = config('services.paystack.secret_key');
    
        try {
            $response = Http::withToken($paystackSecretKey)
                ->get("https://api.paystack.co/transaction/verify/{$reference}");
    
            Log::info('Paystack verification response', ['response' => $response->json()]);
    
            if ($response->successful() && $response->json('data.status') === 'success') {
                return $response->json('data'); // Return payment details
            }
    
            Log::error('Payment verification failed.', ['response' => $response->json()]);
        } catch (\Exception $e) {
            Log::error('Error during payment verification: ' . $e->getMessage());
        }
    
        return false; // Default to failure
    }
    
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PolicyManager $policyManager)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PolicyManager $policyManager)
    {
        //
    }
}
