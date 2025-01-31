@extends('layouts.navlayout')

@section('content')
<div class="container mt-5">

    <!-- Profile Summary -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5>Welcome, {{ $user->name }}</h5>
            <p>Email: {{ $user->email }} | Role: Buyer</p>
        </div>
        <a href="{{ route('profile.edit') }}" class="btn btn-outline-secondary">Edit Profile</a>
    </div>

    <!-- Statistics and Summaries -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm p-3">
                <div class="card-body">
                    <h5 class="card-title font-weight-bold">Total RFQs</h5>
                    <p class="card-text">{{ $totalRfqs }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm p-3">
                <div class="card-body">
                    <h5 class="card-title font-weight-bold">Open RFQs</h5>
                    <p class="card-text">{{ $openRfqs }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm p-3">
                <div class="card-body">
                    <h5 class="card-title font-weight-bold">Closed RFQs</h5>
                    <p class="card-text">{{ $closedRfqs }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs navigation -->
    <ul class="nav nav-tabs justify-content-center" id="dashboardTabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="rfqs-tab" data-toggle="tab" href="#rfqs" role="tab" aria-controls="rfqs" aria-selected="true">
                📝 RFQs
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="messages-tab" data-toggle="tab" href="#messages" role="tab" aria-controls="messages" aria-selected="false">
                💬 Messages
            </a>
        </li>
    </ul>

    <!-- Tabs content -->
    <div class="tab-content mt-4" id="dashboardTabsContent">
        <!-- RFQs Tab -->
        <div class="tab-pane fade show active" id="rfqs" role="tabpanel" aria-labelledby="rfqs-tab">
            <div class="d-flex justify-content-between mb-3">
                <h5>RFQs Summary</h5>
                <input type="text" class="form-control w-50" placeholder="Search RFQs">
            </div>
            <div class="row">
                @foreach ($rfqs as $rfq)
                    <div class="col-md-6 mb-4">
                        <div class="card shadow-lg p-3 h-100 border-0 rounded">
                            <div class="card-body d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h5 class="card-title font-weight-bold mb-0">{{ $rfq->title }}</h5>
                                    <span class="badge {{ $rfq->status === 'open' ? 'bg-success' : 'bg-danger' }}">
                                        {{ ucfirst($rfq->status) }}
                                    </span>
                                </div>
                                <!-- RFQ Details (Fully Left-Aligned) -->
                                <div class="card-body text-left d-flex flex-column align-items-start"> <!-- Ensure left alignment -->
                                    <p class="card-text mb-1"><i class="fas fa-comments text-muted"></i> <strong> Responses:</strong> {{ $rfq->responses_count }}</p>
                                    <p class="card-text mb-1"> <strong> Budget:</strong> <i class="fas fa-rupee-sign text-muted"></i> ₹{{ number_format($rfq->budget) }}</p>
                                    <p class="card-text mb-1"><i class="fas fa-align-left text-muted"></i> <strong> Description:</strong> {{ \Illuminate\Support\Str::limit($rfq->description, 100) }}</p>
                                    <p class="card-text mb-3"><i class="far fa-calendar-alt text-muted"></i> <strong> Deadline:</strong> 
                                        <span class="text-danger fw-bold">{{ \Carbon\Carbon::parse($rfq->deadline)->format('d M, Y') }}</span>
                                    </p>
                                </div>                                
                                <div class="mt-auto d-flex gap-2">
                                    <div class="flex-grow-1 d-grid">
                                        <a href="{{ route('rfqs.show', $rfq->id) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i> View Details
                                        </a>
                                    </div>
                                    
                                    <div class="flex-grow-1 d-grid">
                                        <a href="{{ route('rfqs.authrfqs') }}" class="btn btn-sm btn-secondary">
                                            My RFQs
                                        </a>
                                    </div>
                                    
                                    <div class="flex-grow-1 d-grid">
                                        <form action="{{ route('rfqs.close', $rfq->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-lock"></i> Close RFQ
                                            </button>
                                        </form>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Messages Tab -->
        <div class="tab-pane fade" id="messages" role="tabpanel" aria-labelledby="messages-tab">
            <div class="d-flex justify-content-between mb-3">
                <h5>Messages</h5>
                <input type="text" class="form-control w-50" placeholder="Search messages">
            </div>
            <div class="row">
                @if (isset($messages) && $messages->isNotEmpty())
                    @foreach ($messages as $message)
                        <div class="col-md-6 mb-4">
                            <div class="card shadow-sm p-3 h-100 {{ $message->unread ? 'bg-light' : '' }}">
                                <div class="card-body text-left d-flex flex-column align-items-start">
                                    <h5 class="card-title font-weight-bold mb-2">From: {{ $message->sender->name }} <span class="badge badge-info">{{ $message->created_at->diffForHumans() }}</span></h5>
                                    <p class="card-text">{{ \Illuminate\Support\Str::limit($message->content, 100) }}</p>
                                    <a href="{{ route('messages.show', $message->id) }}" class="btn btn-primary mt-2">Read Message</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <p>No messages available.</p>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        var tabTriggerList = [].slice.call(document.querySelectorAll('#dashboardTabs a'));
        tabTriggerList.forEach(function (tabEl) {
            tabEl.addEventListener('click', function (event) {
                event.preventDefault();
                var tab = new bootstrap.Tab(tabEl);
                tab.show();
            });
        });
    });
</script>
@endsection
