<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Notification;
use App\Models\Proposal;
use App\Models\User;
use App\Models\RFQ;
use App\Models\UserSubscription;


class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            Log::info('Redirecting to login: user not authenticated.');
            return redirect()->route('login');
        }

        if ($user->role === 'buyer') {
            Log::info('Redirecting to buyer dashboard: user ID '.$user->id);
            return redirect()->route('buyer.dashboard');
        }

        // Fetch proposals with the `selected` field
        $rfqsWithProposals = Proposal::where('seller_id', $user->id)
            ->with('rfq')
            ->select('id', 'rfq_id', 'price', 'lead_time', 'description', 'created_at', 'selected')
            ->get();

        // Fetch notifications for approved proposals
        $approvedProposals = Proposal::where('seller_id', $user->id)
            ->where('status', 'approved')
            ->with('rfq')
            ->get();

        // ✅ Fetch user notifications
        $notifications = $user->notifications; // Fetch all notifications
        
        // Fetch the active subscription plan
        $subscription = UserSubscription::where('user_id', $user->id)
            ->where('status', 'active')
            ->with('subscriptionPlan')
            ->first();

        return view('auth.dashboard', compact('user', 'rfqsWithProposals', 'approvedProposals', 'notifications', 'subscription'));
    }       
}