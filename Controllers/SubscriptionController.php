<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserSubscription;
use App\Models\SubscriptionPlan;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller {
    public function index() {
        // Ensure the user is authenticated and is a seller
        if (!auth()->check() || auth()->user()->role !== 'seller') {
            abort(403, 'Unauthorized action.');
        }

        // Fetch all subscription plans
        $plans = SubscriptionPlan::all();
        
        return view('subscriptions.subscription', compact('plans'));
    }

    public function create(Request $request) {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
    
        // Check if plan_key is provided
        if (!$request->has('plan_key')) {
            return response()->json(['message' => 'plan_key is required'], 400);
        }
    
        // Find the plan using plan_key instead of plan_id
        $plan = SubscriptionPlan::where('name', ucfirst($request->plan_key))->first();
        if (!$plan) {
            return response()->json(['message' => 'Plan not found'], 404);
        }
    
        // Create the subscription
        $subscription = new UserSubscription();
        $subscription->user_id = $user->id;
        $subscription->subscription_plan_id = $plan->id;
        $subscription->status = 'active';
        $subscription->start_date = now();
        $subscription->end_date = now()->addMonths(1);
        $subscription->save();
    
        return response()->json(['message' => 'Subscription created successfully!'], 200);
    }
    
    public function cancel($id) {
        $subscription = UserSubscription::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $subscription->update(['status' => 'canceled']);

        return response()->json(['message' => 'Subscription canceled successfully!'], 200);
    
    }
}