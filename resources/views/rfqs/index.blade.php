@extends('layouts.navlayout')

@section('content')
<div class="container mt-5">
    <a href="{{ route('rfqs.create') }}" class="btn btn-outline-primary btn-lg mb-4 d-block mx-auto">Create New RFQ</a>

    <div class="row">
        @foreach ($rfqs as $rfq)
            <div class="col-12 mb-4">  <!-- Full width for each card -->
                <div class="card shadow-sm p-3 h-100">
                    <div class="card-body text-left d-flex flex-column align-items-start"> <!-- Ensure left alignment -->
                        <h5 class="card-title font-weight-bold mb-2">{{ $rfq->title }}</h5>
                        <p class="card-text"><strong>Budget (INR):</strong> ₹{{ number_format($rfq->budget) }}</p>
                        <p class="card-text"><strong>Description:</strong> {{ $rfq->description }}</p>
                        <a href="{{ route('rfqs.show', $rfq->id) }}" class="btn btn-primary mt-2">View Details</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
