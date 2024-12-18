@extends('layouts.navlayout') 
@section('content') 
<div class="container py-4"> 
    <div class="row"> 
        <div class="col-md-4"> 
            <!-- Profile Picture, Name, and Email Card --> 
            <div class="card text-center widget-card border-light shadow-sm"> 
                <div class="card-body d-flex flex-column align-items-center"> 
                    <img src="{{ asset('storage/' . ($user->profile_picture ?? 'default-avatar.png')) }}" 
                         class="mb-3 rounded-circle img-thumbnail" alt="Profile Picture" style="width: 150px; height: 150px;"> 
                    <h4 class="card-title">{{ $user->name }}</h4> 
                    <p class="text-muted">{{ $user->email }}</p> 
                    <button class="mt-2 btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editProfileModal"> 
                        Edit Profile 
                    </button> 
                </div> 
            </div> 
        </div> 
        <div class="col-md-8"> 
            <div class="card widget-card border-light shadow-sm"> 
                <div class="card-body p-4"> 
                    <ul class="nav nav-tabs" id="profileTab" role="tablist"> 
                        <li class="nav-item" role="presentation"> 
                            <button class="nav-link active" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview-tab-pane" type="button" role="tab" aria-controls="overview-tab-pane" aria-selected="true">Overview</button> 
                        </li> 
                        <li class="nav-item" role="presentation"> 
                            <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false">Profile</button> 
                        </li> 
                        <li class="nav-item" role="presentation"> 
                            <button class="nav-link" id="email-tab" data-bs-toggle="tab" data-bs-target="#email-tab-pane" type="button" role="tab" aria-controls="email-tab-pane" aria-selected="false">Emails</button> 
                        </li> 
                        <li class="nav-item" role="presentation"> 
                            <button class="nav-link" id="password-tab" data-bs-toggle="tab" data-bs-target="#password-tab-pane" type="button" role="tab" aria-controls="password-tab-pane" aria-selected="false">Password</button> 
                        </li> 
                    </ul> 
                    <div class="tab-content" id="profileTabContent"> 
                        <div class="tab-pane fade show active" id="overview-tab-pane" role="tabpanel" aria-labelledby="overview-tab"> 
                            <!-- About Us content in Overview tab --> 
                            <div>
                                <p><strong>About Us:</strong> {{ $user->about_us ?? 'Not provided' }}</p>
                            </div> 
                        </div> 
                        <div class="tab-pane fade" id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab"> 
                            <!-- Profile content goes here --> 
                            <div> 
                                <p><strong>Name:</strong> {{ $user->name }}</p> 
                                <p><strong>Email:</strong> {{ $user->email }}</p>
                                <p><strong>Contact:</strong> {{ $user->phone ?? 'Not provided' }}</p> 
                                <p><strong>Business Type:</strong> {{ $user->business_type ?? 'Not provided' }}</p> 
                                <p><strong>Address:</strong> {{ $user->address ?? 'Not provided' }}</p> 
                            </div> 
                        </div> 
                        <div class="tab-pane fade" id="email-tab-pane" role="tabpanel" aria-labelledby="email-tab"> 
                            <!-- Email content goes here --> 
                            <div>
                                <p><strong>Primary Email:</strong> {{ $user->email }}</p>
                                <p>Manage your email preferences, view your email history, and update your contact details here.</p>
                            </div>
                        </div> 
                        <div class="tab-pane fade" id="password-tab-pane" role="tabpanel" aria-labelledby="password-tab"> 
                            <!-- Password content goes here --> 
                            <div>
                                <form action="{{ route('password.update') }}" method="POST">
                                    @csrf
                                    <div class="form-group mb-3">
                                        <label for="current_password" class="form-label">Current Password</label>
                                        <input type="password" class="form-control" id="current_password" name="current_password" required>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="new_password" class="form-label">New Password</label>
                                        <input type="password" class="form-control" id="new_password" name="new_password" required>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="new_password_confirmation" class="form-label">Confirm New Password</label>
                                        <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Update Password</button>
                                </form>
                            </div>
                        </div> 
                    </div> 
                </div> 
            </div> 
        </div> 
    </div>
    <!-- Edit Profile Modal -->
    <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProfileModalLabel">Edit Profile</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('profile.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                            <small class="form-text text-muted">Leave blank to keep the current password.</small>
                        </div>
                        <div class="form-group mb-3">
                            <label for="profile_picture" class="form-label">Profile Picture</label>
                            <input type="file" class="form-control" id="profile_picture" name="profile_picture">
                        </div>
                        <div class="form-group mb-3">
                            <label for="business_type" class="form-label">Business Type</label>
                            <select class="form-control" id="business_type" name="business_type" required>
                                <option value="" disabled selected>Select Business Type</option>
                                <option value="building_construction_material_equipment" {{ $user->business_type == 'building_construction_material_equipment' ? 'selected' : '' }}>
                                    Building Construction Material & Equipment
                                </option>
                                <option value="electronics_electrical_goods_supplies" {{ $user->business_type == 'electronics_electrical_goods_supplies' ? 'selected' : '' }}>
                                    Electronics & Electrical Goods & Supplies
                                </option>
                                <option value="pharmaceutical_drug_medicine_medical_care_consultation" {{ $user->business_type == 'pharmaceutical_drug_medicine_medical_care_consultation' ? 'selected' : '' }}>
                                    Pharmaceutical Drug, Medicine, Medical Care & Consultation
                                </option>
                                <option value="hospital_and_medical_equipment" {{ $user->business_type == 'hospital_and_medical_equipment' ? 'selected' : '' }}>
                                    Hospital and Medical Equipment
                                </option>
                                <option value="industrial_plants_machinery_equipment" {{ $user->business_type == 'industrial_plants_machinery_equipment' ? 'selected' : '' }}>
                                    Industrial Plants, Machinery & Equipment
                                </option>
                                <option value="industrial_engineering_products_spares_supplies" {{ $user->business_type == 'industrial_engineering_products_spares_supplies' ? 'selected' : '' }}>
                                    Industrial & Engineering Products, Spares and Supplies
                                </option>
                                <option value="food_agriculture_farming" {{ $user->business_type == 'food_agriculture_farming' ? 'selected' : '' }}>
                                    Food, Agriculture & Farming
                                </option>
                                <option value="apparel_clothing_garments" {{ $user->business_type == 'apparel_clothing_garments' ? 'selected' : '' }}>
                                    Apparel, Clothing & Garments
                                </option>
                                <option value="packaging_material_supplies_machines" {{ $user->business_type == 'packaging_material_supplies_machines' ? 'selected' : '' }}>
                                    Packaging Material, Supplies & Machines
                                </option>
                                <option value="chemicals_dyes_solvents_allied_products" {{ $user->business_type == 'chemicals_dyes_solvents_allied_products' ? 'selected' : '' }}>
                                    Chemicals, Dyes, Solvents & Allied Products
                                </option>
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label for="about_us" class="form-label">About Us</label>
                            <textarea class="form-control" id="about_us" name="about_us" rows="3">{{ $user->about_us }}</textarea>
                        </div>
                        <div class="form-group mb-3">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="text" class="form-control" id="phone" name="phone" value="{{ $user->phone }}">
                        </div>
                        <div class="form-group mb-3">
                            <label for="address" class="form-label">Address</label>
                            <input type="text" class="form-control" id="address" name="address" value="{{ $user->address }}">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update Profile</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- User Products Section -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Your Products</h5>
                    <!-- Add Product Button -->
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addProductModal">
                        Add New Product
                    </button>
                </div>
                <div class="card-body">
                    <div class="row">
                        @forelse ($products as $product)
                            <div class="mb-4 col-md-4">
                                <div class="card h-100">
                                    @if ($product->thumbnail_image_id && $product->images->contains('id', $product->thumbnail_image_id))
                                        @php
                                            $thumbnail = $product->images->firstWhere('id', $product->thumbnail_image_id);
                                        @endphp
                                        <img src="{{ asset('storage/' . $thumbnail->image_path) }}" alt="{{ $product->title }}">
                                    @elseif ($product->images->isNotEmpty())
                                        <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" alt="{{ $product->title }}">
                                    @else
                                        <img src="{{ asset('storage/default-product.png') }}" alt="Default Product Image">
                                    @endif

                                    <div class="card-body">
                                        <h6 class="card-title">{{ $product->title }}</h6>
                                        <p class="card-text">{{ Str::limit($product->description, 100, '...') }}</p>
                                        <p><strong>Price:</strong> ₹{{ $product->price }}</p>
                                    </div>
                                    <div class="card-footer d-flex justify-content-between">
                                        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning btn-sm">
                                            Edit
                                        </a>
                                        <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" 
                                                    onclick="return confirm('Are you sure you want to delete this product?')">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <p class="text-center text-muted">No products added yet.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Product Modal -->
    <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addProductModalLabel">Add New Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <label for="title" class="form-label">Product Title</label>
                            <input type="text" class="form-control" id="title" name="title" placeholder="Enter product title" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="description" class="form-label">Product Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3" placeholder="Enter product description"></textarea>
                        </div>
                        <div class="form-group mb-3">
                            <label for="thumbnail_image" class="form-label">Thumbnail Image</label>
                            <input type="file" class="form-control" id="thumbnail_image" name="thumbnail_image" accept="image/*">                        </div>
                        <div class="form-group mb-3">
                            <label for="images" class="form-label">Product Images</label>
                            <input type="file" class="form-control" id="images" name="images[]" multiple onchange="previewImages()">
                            <div id="imagePreview" class="mt-2 d-flex flex-wrap"></div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="price" class="form-label">Price</label>
                            <input type="number" class="form-control" id="price" name="price" placeholder="Enter product price" min="0" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="category_id" class="form-label">Category</label>
                            <select class="form-control" id="category_id" name="category_id" required>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection 
@section('styles')
    <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
@endsection

@section('scripts') <script src="https://unpkg.com/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<style> .modal-body .form-label { font-weight: bold; } .modal-body .form-control { border-radius: 0.25rem; } .modal-body .form-control-file { margin-top: 0.5rem; } #imagePreview img { margin: 5px; border: 1px solid #ddd; border-radius: 5px; padding: 5px; } 
</style> 
    <script> 
        function previewImages() { 
            var preview = document.getElementById('imagePreview'); 
            preview.innerHTML = ''; 
            if (this.files) { 
                [].forEach.call(this.files, readAndPreview); 

            } 
            function readAndPreview(file) { 
                if (!/\.(jpe?g|png|gif)$/i.test(file.name)) { 
                    return alert(file.name + " is not an image"); 
                } 
                var reader = new FileReader(); 
                reader.addEventListener("load", function() { 
                    var image = new Image(); image.height = 100; 
                    image.title = file.name; image.src = this.result; 
                    preview.appendChild(image); 
                }); 
                reader.readAsDataURL(file); 
            } 
        } 
        
        document.getElementById('images').addEventListener('change', previewImages); 

    </script> 
@endsection