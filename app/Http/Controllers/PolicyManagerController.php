<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Payments;
use App\Models\PolicyType;
use Illuminate\Http\Request;
use App\Models\PolicyManager;
use Illuminate\Support\Carbon;
use App\Mail\PolicyPurchasedMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use App\Mail\AdminPolicyNotificationMail;
use App\DataTables\PolicyManagerDataTable;
use App\Mail\PolicyCreatedNotificationMail;

class PolicyManagerController extends Controller
{
    public function index(PolicyManagerDataTable $dataTable)
    {
        return $dataTable->render('users.pages.policyManager.index');
    }


    public function create($id)
    {
        $policyType = PolicyType::findOrFail($id);

        $latestPolicy = PolicyManager::latest()->first();
        $nextNumber = $latestPolicy ? ((int)substr($latestPolicy->policy_number, -6)) + 1 : 1;
        $policyNumber = 'POL-' . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
        return view('users.pages.policyManager.create', compact('policyType', 'policyNumber'));
    }

    public function store(Request $request)
    {
        Log::info('Store method initiated', [
            'request_data' => $request->all(),
            'authenticated_user' => auth()->user(),
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
    
        Log::info('Validation passed', ['validated_data' => $request->all()]);
    
        // Verify payment using Paystack
        $paymentDetails = $this->verifyPayment($request->reference);
    
        if (!$paymentDetails) {
            Log::error('Payment verification failed.', ['reference' => $request->reference]);
            return back()->with('error', 'Payment verification failed.');
        }
    
        Log::info('Payment verification successful', ['payment_details' => $paymentDetails]);
    
        if ($paymentDetails['status'] == true) {
            $result = DB::transaction(function () use ($request, $paymentDetails) {
                try {
                    Log::info('Starting database transaction for policy creation.');
    
                    // Create Policy
                    $policy = PolicyManager::create([
                        'policy_number' => $request->policy_number,
                        'policy_type_id' => $request->policy_type_id,
                        'premium_amount' => $request->premium_amount, // Save in GHS
                        'start_date' => $request->start_date,
                        'end_date' => $request->end_date,
                        'next_of_kin' => $request->next_of_kin,
                        'user_id' => auth()->id(),
                        'status' => 'Active',
                    ]);
    
                    Log::info('Policy created successfully.', ['policy' => $policy]);
    
                    // Log Payment
                    Payments::create([
                        'policy_id' => $policy->id,
                        'reference' => $request->reference,
                        'amount' => $paymentDetails['amount'] / 100, // Convert to GHS
                        'status' => $paymentDetails['status'],
                        'user_id' => auth()->id(),
                        'channel' => $paymentDetails['channel'] ?? null,
                        'currency' => $paymentDetails['currency'],
                        'paid_at' => Carbon::parse($paymentDetails['paid_at'])->format('Y-m-d H:i:s'),
                    ]);
    
                    Log::info('Payment logged successfully.');
                    return $policy;
                } catch (\Throwable $th) {
                    Log::error('Error during database transaction', [
                        'error_message' => $th->getMessage(),
                        'trace' => $th->getTraceAsString(),
                    ]);
                    return false;
                }
            });
    
            if ($result) {
                Log::info('Policy creation and payment logging completed successfully.', ['policy' => $result]);
                $this->notifyPolicyStakeholders($result);
                return redirect()->route('all.policy.user')->with('success', 'Policy created and payment verified successfully.');
            } else {
                Log::error('Database transaction failed.');
                return back()->with('error', 'Payment was successful, but an error occurred. Contact Administrator.');
            }
        } else {
            Log::info('Payment status marked as failed.', ['payment_details' => $paymentDetails]);
            return back()->with('error', $paymentDetails['message'] ?? 'Payment failed.');
        }
    }
    


    private function notifyPolicyStakeholders($policy)
    {
        try {
            Log::info('Starting notification emails for policy stakeholders.', ['policy_id' => $policy->id]);

            // Buyer Email
            $buyer = $policy->user;
            try {
                Log::info('Sending email to buyer.', ['email' => $buyer->email, 'policy_id' => $policy->id]);
                Mail::to($buyer->email)->queue(new PolicyPurchasedMail($buyer, $policy));
                Log::info('Email sent to buyer successfully.', ['email' => $buyer->email]);
            } catch (\Exception $e) {
                Log::error('Error sending email to buyer.', [
                    'email' => $buyer->email,
                    'error_message' => $e->getMessage(),
                ]);
            }

            // Policy Creator Email
            $policyCreator = $policy->policyType->user;
            if ($policyCreator) {
                try {
                    Log::info('Sending email to policy creator.', ['email' => $policyCreator->email]);
                    Mail::to($policyCreator->email)->queue(new PolicyCreatedNotificationMail($buyer, $policy));
                    Log::info('Email sent to policy creator successfully.', ['email' => $policyCreator->email]);
                } catch (\Exception $e) {
                    Log::error('Error sending email to policy creator.', [
                        'email' => $policyCreator->email,
                        'error_message' => $e->getMessage(),
                    ]);
                }
            } else {
                Log::warning('No policy creator found for policy.', ['policy_id' => $policy->id]);
            }

            // Admin Emails
            $adminUsers = User::where('role', 0)->get();
            foreach ($adminUsers as $admin) {
                try {
                    Log::info('Sending email to admin.', ['email' => $admin->email]);
                    Mail::to($admin->email)->queue(new AdminPolicyNotificationMail($buyer, $policy));
                    Log::info('Email sent to admin successfully.', ['email' => $admin->email]);
                } catch (\Exception $e) {
                    Log::error('Error sending email to admin.', [
                        'email' => $admin->email,
                        'error_message' => $e->getMessage(),
                    ]);
                }
            }

            Log::info('All notification emails processed successfully for policy stakeholders.');
        } catch (\Exception $e) {
            Log::error('Error in notifyPolicyStakeholders method.', [
                'policy_id' => $policy->id,
                'error_message' => $e->getMessage(),
            ]);
        }
    }


    public function show()
    {
        $policyTypes = PolicyType::all();
        return view('users.pages.policyManager.grid', compact('policyTypes'));
    }

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

        return false; 
    }
}
