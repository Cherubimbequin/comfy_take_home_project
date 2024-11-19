<?php

namespace App\Console\Commands;

use App\Models\PolicyManager;
use Illuminate\Support\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Notifications\PolicyExpiredNotification;
use App\Notifications\PolicyExpiringNotification;

class NotifyExpiringPolicies extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:expiring-policies';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notify users about expiring policies';


    /**
     * Execute the console command.
     */
    public function handle()
{
    $now = Carbon::now();
    $expiringSoonDate = $now->addDays(7);

    // Update expired policies and notify users
    $expiredPolicies = PolicyManager::where('end_date', '<=', $now)
        ->where('status', '!=', 'Expired') // Only process policies not already marked as expired
        ->get();

    foreach ($expiredPolicies as $policy) {
        // Update policy status to 'Expired'
        $policy->update(['status' => 'Expired']);

        // Notify the user about the expired policy
        $user = $policy->user;
        if ($user) {
            $user->notify(new PolicyExpiredNotification($policy));
            Log::info("Notification sent to user: {$user->email} for expired policy: {$policy->policy_number}");
        }
    }

    // Notify users of policies expiring soon
    $expiringPolicies = PolicyManager::where('end_date', '>', $now)
        ->where('end_date', '<=', $expiringSoonDate)
        ->get();

    foreach ($expiringPolicies as $policy) {
        $user = $policy->user;
        if ($user) {
            $user->notify(new PolicyExpiringNotification($policy));
        }
    }
}

}
