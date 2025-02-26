<?php

namespace App\Services;

use Razorpay\Api\Api;
use Exception;

class RazorpayService
{
    protected $api;

    public function __construct()
    {
        $this->api = new Api(config('services.razorpay.key_id'), config('services.razorpay.key_secret'));
    }

    public function createSubscriptionPlan($planData)
    {
        try {
            return $this->api->plan->create($planData);
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function createSubscription($subscriptionData)
    {
        try {
            return $this->api->subscription->create($subscriptionData);
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
}
