@extends('layouts.navlayout')

@section('content')
<div class="container">
    <h2 class="text-white">Available RFQs</h2>
    
    @foreach ($rfqs as $rfq)
        <div class="p-3 my-3 text-white card bg-dark">
            <h4>{{ $rfq->title }}</h4>
            <p><strong>Category:</strong> {{ $rfq->category->name }}</p>
            <p><strong>Quantity:</strong> {{ $rfq->quantity }}</p>
            <p><strong>Budget:</strong> ₹{{ number_format($rfq->budget, 2) }}</p>
            <p><strong>Deadline:</strong> {{ \Carbon\Carbon::parse($rfq->deadline)->format('d M, Y') }}</p>            
            <!-- Submit Proposal Button -->
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#submitProposalModal{{ $rfq->id }}">
                Submit Proposal
            </button>

            <!-- Proposal Submission Modal -->
            <div class="modal fade" id="submitProposalModal{{ $rfq->id }}" tabindex="-1" aria-labelledby="submitProposalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="text-white modal-content bg-dark">
                        <div class="modal-header">
                            <h5 class="modal-title" id="submitProposalLabel">Submit Proposal for "{{ $rfq->title }}"</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('rfqs.submit.proposal', $rfq->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                
                                <div class="mb-3">
                                    <label for="price" class="form-label">Price (INR)</label>
                                    <input type="number" name="price" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label for="lead_time" class="form-label">Lead Time (days)</label>
                                    <input type="number" name="lead_time" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Proposal Description</label>
                                    <textarea name="description" class="form-control" rows="3" required></textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="moq" class="form-label">Minimum Order Quantity (Optional)</label>
                                    <input type="number" name="moq" class="form-control">
                                </div>

                                <div class="mb-3">
                                    <label for="payment_terms" class="form-label">Payment Terms (Optional)</label>
                                    <input type="text" name="payment_terms" class="form-control">
                                </div>

                                <div class="mb-3">
                                    <label for="shipping_terms" class="form-label">Shipping Terms (Optional)</label>
                                    <input type="text" name="shipping_terms" class="form-control">
                                </div>

                                <div class="mb-3">
                                    <label for="attachment" class="form-label">Attachment (PDF, DOC, JPG, PNG) - Max 2MB</label>
                                    <input type="file" name="attachment" class="form-control" accept=".pdf,.jpg,.png,.doc,.docx">
                                </div>

                                <button type="submit" class="btn btn-success">Submit Proposal</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Modal -->
        </div>
    @endforeach
</div>
@endsection
