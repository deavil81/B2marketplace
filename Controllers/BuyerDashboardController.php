<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Buyer;
use App\Models\Rfq;
use App\Models\Message;
//use App\Models\Notification;
//use App\Models\Bid;
use carbon\carbon;

class BuyerDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role !== 'buyer') {
            return redirect()->route('login')->with('error', 'Unauthorized access.');
        }

        // Fetch buyer details
        $buyerDetails = $user->buyer;
        if (!$buyerDetails) {
            return redirect()->route('profile.index')->with('error', 'Buyer details not found.');
        }

        // Get current date
        $today = Carbon::today();

        // Update RFQ status based on deadline
        Rfq::where('user_id', $user->id)
            ->where('deadline', '<', $today)
            ->where('status', 'open') // Only update if still marked as open
            ->update(['status' => 'closed']);

        // Fetch buyer's RFQs with updated statuses
        $rfqsQuery = Rfq::where('user_id', $user->id);
        $rfqs = $rfqsQuery->withCount('responses')->get();
        $totalRfqs = (clone $rfqsQuery)->count(); // Clone before modifying
        $openRfqs = (clone $rfqsQuery)->where('status', 'open')->count(); // Clone before filtering
        $closedRfqs = (clone $rfqsQuery)->where('status', 'closed')->count();

        // Get RFQs with upcoming deadlines
        $rfqsWithUpcomingDeadline = Rfq::where('user_id', $user->id)
            ->where('deadline', '>=', $today)
            ->latest()
            ->get();

        // Fetch latest 5 messages
        $messages = Message::where('receiver_id', $user->id)->latest()->take(5)->get();

        return view('buyer.dashboard', compact(
            'user', 
            'buyerDetails',
            'rfqs',  // ✅ Keep this
            'rfqsWithUpcomingDeadline', 
            'messages', 
            'totalRfqs', 
            'openRfqs', 
            'closedRfqs'
        ));
        
    }
}
