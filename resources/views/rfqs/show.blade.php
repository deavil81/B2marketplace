@extends('layouts.navlayout')

@section('content')
<div class="container mt-5">
    <div class="card shadow-sm border-0" style="max-width: 800px; margin: auto; padding: 40px;">
        <div class="card-body">
            <!-- Date Positioned as in the Letter -->
            <p class="text-right" style="text-align: right;"><strong>Date:</strong> {{ now()->format('d-m-Y') }}</p>

            <p style="text-align: left;">Dear Recipient,</p>

            <p style="text-align: left;">We appreciate your interest in our RFQ. Below are the details:</p>

            <p style="text-align: left;"><strong>Title:</strong> {{ $rfq->title }}</p>
            <p style="text-align: left;"><strong>Category:</strong> {{ $rfq->category->name ?? 'N/A' }}</p>
            <p style="text-align: left;"><strong>Subcategory:</strong> {{ $rfq->subcategory->name ?? 'N/A' }}</p>
            <p style="text-align: left;"><strong>Quantity:</strong> {{ number_format($rfq->quantity) }}</p>
            <p style="text-align: left;"><strong>Budget (INR):</strong> ₹{{ number_format($rfq->budget) }}</p>
            <p style="text-align: left;"><strong>Deadline:</strong> {{ $rfq->deadline }}</p>
            <p style="text-align: left;"><strong>Description:</strong> {{ $rfq->description }}</p>

            <p style="text-align: left;">We look forward to your proposal. If you have any questions, feel free to reach out.</p>

            <!-- Signature Section -->
            <p class="mt-4" style="text-align: left;">Warm regards,</p>
            <p style="text-align: left;"><strong>{{ Auth::user()->name }}</strong></p>
        </div>
    </div>
</div>
@endsection
