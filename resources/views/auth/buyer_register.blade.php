@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="p-4 shadow-lg card">
                <h2 class="mb-4 text-center text-primary">Registration</h2>

                <!-- Display validation errors -->
                @if ($errors->any())
                    <div class="mb-4 alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('register.details.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <!-- Hidden fields -->
                    <input type="hidden" name="email" value="{{ $initialData['email'] }}">
                    <input type="hidden" name="password" value="{{ $initialData['password'] }}">
                    <input type="hidden" name="role" value="buyer">

                    <!-- Full Name -->
                    <div class="mb-3 form-group">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Full Name" value="{{ old('name') }}" required>
                    </div>

                    <!-- Business Name -->
                    <div class="mb-3 form-group">
                        <label for="business_name" class="form-label">Business Name</label>
                        <input type="text" class="form-control" id="business_name" name="business_name" placeholder="Business Name" value="{{ old('business_name') }}" required>
                    </div>

                    <!-- Industry -->
                    <div class="mb-3 form-group">
                        <label for="industry" class="form-label">Industry</label>
                        <select class="form-control" id="industry" name="industry" required>
                            <option value="" disabled selected>Select Industry</option>
                            <option value="Manufacturing" {{ old('industry') == 'Manufacturing' ? 'selected' : '' }}>Manufacturing</option>
                            <option value="Retail" {{ old('industry') == 'Retail' ? 'selected' : '' }}>Retail</option>
                            <option value="Wholesale" {{ old('industry') == 'Wholesale' ? 'selected' : '' }}>Wholesale</option>
                            <option value="E-commerce" {{ old('industry') == 'E-commerce' ? 'selected' : '' }}>E-commerce</option>
                            <option value="Other" {{ old('industry') == 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>

                    <!-- Business Interest -->
                    <div class="mb-3 form-group">
                        <label for="business_interest" class="form-label">Business Interest</label>
                        <textarea class="form-control" id="business_interest" name="business_interest" placeholder="Describe your business interests and sourcing needs" rows="3" required>{{ old('business_interest') }}</textarea>
                    </div>

                    <!-- Sourcing Needs -->
                    <div class="mb-3 form-group">
                        <label for="sourcing_needs" class="form-label">Sourcing Needs</label>
                        <textarea class="form-control" id="sourcing_needs" name="sourcing_needs" placeholder="Describe your sourcing needs" rows="3" required>{{ old('sourcing_needs') }}</textarea>
                    </div>

                    <!-- About Us -->
                    <div class="mb-3 form-group">
                        <label for="about_us" class="form-label">About Us</label>
                        <textarea class="form-control" id="about_us" name="about_us" placeholder="Tell us about your company" rows="3" required>{{ old('about_us') }}</textarea>
                    </div>

                    <!-- Phone Number -->
                    <div class="mb-3 form-group">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="text" class="form-control" id="phone" name="phone" placeholder="Phone Number" value="{{ old('phone') }}" required>
                    </div>

                    <!-- Address -->
                    <div class="mb-3 form-group">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control" id="address" name="address" placeholder="Enter your address" rows="2" required>{{ old('address') }}</textarea>
                    </div>

                    <!-- Annual Budget -->
                    <div class="mb-3 form-group">
                        <label for="annual_budget" class="form-label">Annual Budget</label>
                        <input type="text" class="form-control" id="annual_budget" name="annual_budget" placeholder="Enter your annual budget (e.g., $50,000)" value="{{ old('annual_budget') }}" required>
                    </div>

                    <!-- Upload Company Document -->
                    <div class="mb-3 form-group">
                        <label for="document" class="form-label">Upload Company Document (optional)</label>
                        <input type="file" class="form-control" id="document" name="document" accept=".pdf,.doc,.docx">
                    </div>

                    <!-- Profile Picture -->
                    <div class="mb-3 form-group">
                        <label for="profile_picture" class="form-label">Profile Picture</label>
                        <input type="file" class="form-control" id="profile_picture" name="profile_picture" accept="image/*" required>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-100 btn btn-primary">Register</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
