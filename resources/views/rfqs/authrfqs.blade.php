@extends('layouts.navlayout')

@section('content')
<div class="container">
    <h2 class="mb-4">My RFQs</h2>
    
    @if($rfqs->isEmpty())
        <p>No RFQs found. <a href="{{ route('rfqs.create') }}">Create one</a></p>
    @else
        <div class="row">
            @foreach ($rfqs as $rfq)
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm p-3 h-100">
                        <div class="card-body text-left">
                            <h5 class="card-title font-weight-bold mb-2">{{ $rfq->title }}</h5>
                            <p class="card-text"><strong>Status:</strong> {{ $rfq->status }}</p>

                            <div class="mt-3">
                                <p class="card-text"><strong>Budget (INR):</strong> ₹{{ number_format($rfq->budget) }}</p>
                                <p class="card-text"><strong>Description:</strong> {{ \Illuminate\Support\Str::limit($rfq->description, 100) }}</p>
                                <p class="card-text"><strong>Deadline:</strong> {{ $rfq->deadline }}</p>
                            </div>

                            <div class="mt-3 d-flex">
                                <a href="{{ route('rfqs.show', $rfq->id) }}" class="btn btn-primary mr-2">View Details</a>
                                <form action="{{ route('rfqs.close', $rfq->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('POST')
                                    <button type="submit" class="btn btn-danger">Close RFQ</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
