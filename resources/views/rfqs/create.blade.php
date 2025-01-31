@extends('layouts.navlayout')

@section('title', 'Create RFQ')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0 text-center">Create a New RFQ</h3>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('rfqs.store') }}">
                            @csrf
                            <!-- Title -->
                            <div class="mb-4">
                                <label for="title" class="form-label fw-bold">Title</label>
                                <input type="text" 
                                       class="form-control shadow-sm" 
                                       id="title" 
                                       name="title" 
                                       placeholder="Enter a title for the RFQ" 
                                       required>
                            </div>
                            
                            <!-- Description -->
                            <div class="mb-4">
                                <label for="description" class="form-label fw-bold">Description</label>
                                <textarea class="form-control shadow-sm" 
                                          id="description" 
                                          name="description" 
                                          rows="4" 
                                          placeholder="Provide detailed information about the RFQ" 
                                          required></textarea>
                            </div>
                            
                            <!-- Category -->
                            <div class="mb-4">
                                <label for="category" class="form-label fw-bold">Category</label>
                                <select class="form-control shadow-sm" 
                                        id="category" 
                                        name="category_id" 
                                        onchange="fetchSubcategories()" 
                                        required>
                                    <option value="" disabled selected>Select a category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Subcategory -->
                            <div class="mb-4">
                                <label for="subcategory" class="form-label fw-bold">Subcategory</label>
                                <select class="form-control shadow-sm" 
                                        id="subcategory" 
                                        name="subcategory_id" 
                                        required>
                                    <option value="" disabled selected>Select a subcategory</option>
                                </select>
                            </div>
                            
                            <!-- Quantity -->
                            <div class="mb-4">
                                <label for="quantity" class="form-label fw-bold">Quantity</label>
                                <input type="number" 
                                       class="form-control shadow-sm" 
                                       id="quantity" 
                                       name="quantity" 
                                       placeholder="Enter the quantity required" 
                                       min="1" 
                                       required>
                            </div>
                            
                            <!-- Budget -->
                            <div class="mb-4">
                                <label for="budget" class="form-label fw-bold">Budget (INR)</label>
                                <input type="number" 
                                       class="form-control shadow-sm" 
                                       id="budget" 
                                       name="budget" 
                                       placeholder="Enter your budget in INR" 
                                       min="1" 
                                       required>
                            </div>
                            
                            <!-- Deadline -->
                            <div class="mb-4">
                                <label for="deadline" class="form-label fw-bold">Deadline</label>
                                <input type="date" 
                                       class="form-control shadow-sm" 
                                       id="deadline" 
                                       name="deadline" 
                                       required>
                            </div>
                            
                            <!-- Submit Button -->
                            <div class="text-center">
                                <button type="submit" class="btn btn-success px-5 shadow-sm">Create RFQ</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<script>
    // Dynamically fetch subcategories based on the selected category
    function fetchSubcategories() {
        const categoryId = document.getElementById('category').value;
        const subcategorySelect = document.getElementById('subcategory');

        if (!categoryId) {
            return;
        }

        subcategorySelect.innerHTML = '<option value="" disabled selected>Loading...</option>';
        subcategorySelect.disabled = true;

        fetch(`/categories/${categoryId}/subcategories`)
            .then(response => response.json())
            .then(data => {
                subcategorySelect.innerHTML = '<option value="" disabled selected>Select a subcategory</option>';
                if (data.length === 0) {
                    subcategorySelect.innerHTML = '<option value="" disabled>No subcategories available</option>';
                } else {
                    data.forEach(subcategory => {
                        const option = document.createElement('option');
                        option.value = subcategory.id;
                        option.textContent = subcategory.name;
                        subcategorySelect.appendChild(option);
                    });
                }
                subcategorySelect.disabled = false;
            })
            .catch(error => {
                console.error("Error fetching subcategories:", error);
                subcategorySelect.innerHTML = '<option value="" disabled>Error loading subcategories</option>';
                subcategorySelect.disabled = false;
            });
    }
</script>
