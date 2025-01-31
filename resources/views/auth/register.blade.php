<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detailed Registration</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/detailed_registration.css') }}">
</head>
<body class="register-page">
    <div class="container-register100">
        <div class="wrap-register100">
            <form class="register100-form validate-form" action="{{ route('register.details.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <span class="register100-form-title">Business Registration</span>

                <!-- Hide email, password, and role fields -->
                <input type="hidden" name="email" value="{{ $initialData['email'] ?? old('email') }}">
                <input type="hidden" name="password" value="{{ $initialData['password'] ?? old('password') }}">
                <input type="hidden" name="password_confirmation" value="{{ $initialData['password_confirmation'] ?? old('password_confirmation') }}">
                <input type="hidden" name="role" id="role-input" value="{{ $initialData['role'] ?? old('role') }}">

                <div class="form-group">
                    <label for="profile_picture">Profile Picture</label>
                    <input type="file" class="form-control-file" id="profile_picture" name="profile_picture" accept="image/*" required>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="name">Full Name</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Full Name" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="about_us">About Us</label>
                    <textarea class="form-control" id="about_us" name="about_us" placeholder="Tell us about your company" rows="4" required>{{ old('about_us') }}</textarea>
                </div>

                <!-- Role-Specific Fields -->
                <div id="buyer-fields" style="display: none;">
                    <h4>Buyer Information</h4>
                    <div class="form-group">
                        <label for="sourcing_needs">Sourcing Needs</label>
                        <textarea class="form-control" id="sourcing_needs" name="sourcing_needs" placeholder="Describe your sourcing needs">{{ old('sourcing_needs') }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="budget">Budget</label>
                        <input type="text" class="form-control" id="budget" name="budget" placeholder="Enter your budget">
                    </div>
                </div>

                <div id="seller-fields" style="display: none;">
                    <h4>Seller Information</h4>
                    <div class="form-group">
                        <label for="business_name">Business Name</label>
                        <input type="text" class="form-control" id="business_name" name="business_name" placeholder="Business Name">
                    </div>
                    <div class="form-group">
                    <label for="business_type">Business Type</label>
                    <select class="form-control" id="business_type" name="business_type" required>
                        <option value="" disabled selected>Select Business Type</option>
                        <option value="building_construction_material_equipment">Building Construction Material & Equipment</option>
                        <option value="electronics_electrical_goods_supplies">Electronics & Electrical Goods & Supplies</option>
                        <option value="pharmaceutical_drug_medicine_medical_care_consultation">Pharmaceutical Drug, Medicine, Medical Care & Consultation</option>
                        <option value="hospital_and_medical_equipment">Hospital and Medical Equipment</option>
                        <option value="industrial_plants_machinery_equipment">Industrial Plants, Machinery & Equipment</option>
                        <option value="industrial_engineering_products_spares_supplies">Industrial & Engineering Products, Spares and Supplies</option>
                        <option value="food_agriculture_farming">Food, Agriculture & Farming</option>
                        <option value="apparel_clothing_garments">Apparel, Clothing & Garments</option>
                        <option value="packaging_material_supplies_machines">Packaging Material, Supplies & Machines</option>
                        <option value="chemicals_dyes_solvents_allied_products">Chemicals, Dyes, Solvents & Allied Products</option>
                    </select>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="phone">Phone Number</label>
                        <input type="text" class="form-control" id="phone" name="phone" placeholder="Phone Number">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="address">Address</label>
                        <input type="text" class="form-control" id="address" name="address" placeholder="Address">
                    </div>
                </div>

                <div class="form-group">
                    <label for="gst_number">GST Number</label>
                    <input type="text" class="form-control" id="gst_number" name="gst_number" placeholder="Enter GST Number">
                </div>

                <div class="form-group">
                    <label for="business_license">Business License</label>
                    <input type="text" class="form-control" id="business_license" name="business_license" placeholder="Enter Business License">
                </div>

                <div class="form-group">
                    <label for="catalog">Upload Catalog</label>
                    <input type="file" class="form-control-file" id="catalog" name="catalog" accept=".pdf,.doc,.docx">
                </div>

                <div class="form-group">
                    <label for="certificates">Upload Certificates</label>
                    <input type="file" class="form-control-file" id="certificates" name="certificates[]" accept=".pdf,.jpg,.png" multiple>
                </div>

                <button type="submit" class="btn btn-register">Register</button>
            </form>

            @if ($errors->any())
                <div class="mt-3 alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const role = document.getElementById('role-input').value;
            toggleRoleSpecificFields(role);
        });

        function toggleRoleSpecificFields(role) {
            const buyerFields = document.getElementById('buyer-fields');
            const sellerFields = document.getElementById('seller-fields');

            if (role === 'buyer') {
                buyerFields.style.display = 'block';
                sellerFields.style.display = 'none';
            } else if (role === 'seller') {
                sellerFields.style.display = 'block';
                buyerFields.style.display = 'none';
            } else {
                buyerFields.style.display = 'none';
                sellerFields.style.display = 'none';
            }
        }
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
