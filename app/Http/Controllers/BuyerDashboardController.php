<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Buyer;
use App\Models\Rfq;
use App\Models\Message;
//use App\Models\Notification;
//use App\Models\Bid;

class BuyerDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role !== 'buyer') {
            return redirect()->route('login')->with('error', 'Unauthorized access.');
        }

        // Fetch buyer-specific details using the relationship
        $buyerDetails = $user->buyer;

        if (!$buyerDetails) {
            return redirect()->route('profile.index')->with('error', 'Buyer details not found.');
        }

        // Fetch related RFQs, messages, notifications, and bids
        $rfqs = Rfq::where('user_id', $user->id)->latest()->get();
        $messages = Message::where('receiver_id', $user->id)->latest()->take(5)->get(); // Fetch latest 5 messages
        //$notifications = Notification::where('user_id', $user->id)->latest()->take(5)->get();
        //$bids = Bid::where('user_id', $user->id)->get();

        // Fetch related RFQs
        $rfqs = Rfq::where('user_id', $user->id)->latest()->get();
        $totalRfqs = $rfqs->count();
        $openRfqs = $rfqs->where('status', 'open')->count();
        $closedRfqs = $rfqs->where('status', 'closed')->count();

        return view('buyer.dashboard', compact('user', 'buyerDetails', 'rfqs', 'messages', 'totalRfqs', 'openRfqs', 'closedRfqs'));    
    }
}
