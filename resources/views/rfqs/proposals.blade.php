@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Submitted Proposals for {{ $rfq->title }}</h3>

    @if($rfq->proposals->isEmpty())
        <p>No proposals submitted yet.</p>
    @else
        @foreach($rfq->proposals as $proposal)
            <div class="mb-3 card">
                <div class="card-body">
                    <p><strong>Price:</strong> ₹{{ $proposal->price }}</p>
                    <p><strong>Lead Time:</strong> {{ $proposal->lead_time }} days</p>
                    <p><strong>Description:</strong> {{ $proposal->description }}</p>

                    @if($proposal->moq)
                        <p><strong>MOQ:</strong> {{ $proposal->moq }}</p>
                    @endif

                    @if($proposal->payment_terms)
                        <p><strong>Payment Terms:</strong> {{ $proposal->payment_terms }}</p>
                    @endif

                    @if($proposal->shipping_terms)
                        <p><strong>Shipping Terms:</strong> {{ $proposal->shipping_terms }}</p>
                    @endif

                    @if($proposal->attachment)
                        <p><strong>Attachment:</strong> 
                            <a href="{{ asset('storage/'.$proposal->attachment) }}" target="_blank">View File</a>
                        </p>
                    @endif
                </div>
            </div>
        @endforeach
    @endif
</div>
@endsection
