<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
</head>
<body>
    <h1>Edit Your Profile</h1>
    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="mb-3 form-group">
        <label for="title" class="form-label">Product Title</label>
        <input type="text" class="form-control" id="title" name="title" value="{{ $product->title }}" required>
    </div>
    <div class="mb-3 form-group">
        <label for="description" class="form-label">Description</label>
        <textarea class="form-control" id="description" name="description" rows="3">{{ $product->description }}</textarea>
    </div>
    <div class="mb-3 form-group">
        <label for="price" class="form-label">Price</label>
        <input type="number" class="form-control" id="price" name="price" value="{{ $product->price }}" required>
    </div>
    <div class="mb-3 form-group">
        <label for="category_id" class="form-label">Category</label>
        <select class="form-control" id="category_id" name="category_id" required>
            <!-- Options for categories -->
        </select>
    </div>
    <div class="mb-3 form-group">
        <label for="image" class="form-label">Thumbnail Image</label>
        <input type="file" class="form-control" id="image" name="image">
        <input type="checkbox" id="set_as_thumbnail" name="set_as_thumbnail" value="1">
        <label for="set_as_thumbnail"> Set as Thumbnail</label>
    </div>
    <button type="submit" class="btn btn-primary">Update Product</button>
</form>
</body>
</html>
