@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1>Edit Product</h1>
    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="form-group mb-3">
            <label for="title" class="form-label">Product Title</label>
            <input type="text" class="form-control" id="title" name="title" value="{{ $product->title }}" required>
        </div>
        <div class="form-group mb-3">
            <label for="description" class="form-label">Product Description</label>
            <textarea class="form-control" id="description" name="description" rows="3">{{ $product->description }}</textarea>
        </div>
        <div class="form-group mb-3">
            <label for="images" class="form-label">Product Images</label>
            <input type="file" class="form-control" id="images" name="images[]" multiple onchange="previewImages()">
            <div id="imagePreview" class="mt-2 d-flex flex-wrap">
                @foreach ($product->images as $image)
                    <div class="p-2">
                        <img src="{{ asset('storage/' . $image->image_path) }}" class="img-thumbnail" alt="{{ $product->title }}" style="width: 100px; height: 100px;">
                        <div class="form-check mt-1">
                            <input class="form-check-input" type="radio" name="thumbnail_image" id="thumbnail_{{ $image->id }}" value="{{ $image->id }}" {{ $product->thumbnail_image_id == $image->id ? 'checked' : '' }}>
                            <label class="form-check-label" for="thumbnail_{{ $image->id }}">
                                Set as Thumbnail
                            </label>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="form-group mb-3">
            <label for="price" class="form-label">Price</label>
            <input type="number" class="form-control" id="price" name="price" value="{{ $product->price }}" min="0" required>
        </div>
        <div class="form-group mb-3">
            <label for="category_id" class="form-label">Category</label>
            <select class="form-control" id="category_id" name="category_id" required>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Update Product</button>
    </form>
</div>
@endsection

@section('styles')
<link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<style>
    .modal-body .form-label {
        font-weight: bold;
    }

    .modal-body .form-control {
        border-radius: 0.25rem;
    }

    .modal-body .form-control-file {
        margin-top: 0.5rem;
    }

    #imagePreview img {
        margin: 5px;
        border: 1px solid #ddd;
        border-radius: 5px;
        padding: 5px;
    }
</style>
@endsection

@section('scripts')
<script src="https://unpkg.com/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
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
                var image = new Image();
                image.height = 100;
                image.title = file.name;
                image.src = this.result;
                preview.appendChild(image);
            });
            reader.readAsDataURL(file);
        }
    }

    document.getElementById('images').addEventListener('change', previewImages);
</script>
@endsection
