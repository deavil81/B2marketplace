

<?php $__env->startSection('content'); ?>
<div class="container py-4">
    <h1>Edit Product</h1>
    <form action="<?php echo e(route('products.update', $product->id)); ?>" method="POST" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>
        <div class="form-group mb-3">
            <label for="title" class="form-label">Product Title</label>
            <input type="text" class="form-control" id="title" name="title" value="<?php echo e($product->title); ?>" required>
        </div>
        <div class="form-group mb-3">
            <label for="description" class="form-label">Product Description</label>
            <textarea class="form-control" id="description" name="description" rows="3"><?php echo e($product->description); ?></textarea>
        </div>
        <div class="form-group mb-3">
            <label for="images" class="form-label">Product Images</label>
            <input type="file" class="form-control" id="images" name="images[]" multiple onchange="previewImages()">
            <div id="imagePreview" class="mt-2 d-flex flex-wrap">
                <?php $__currentLoopData = $product->images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="p-2">
                        <img src="<?php echo e(asset('storage/' . $image->image_path)); ?>" class="img-thumbnail" alt="<?php echo e($product->title); ?>" style="width: 100px; height: 100px;">
                        <div class="form-check mt-1">
                            <input class="form-check-input" type="radio" name="thumbnail_image" id="thumbnail_<?php echo e($image->id); ?>" value="<?php echo e($image->id); ?>" <?php echo e($product->thumbnail_image_id == $image->id ? 'checked' : ''); ?>>
                            <label class="form-check-label" for="thumbnail_<?php echo e($image->id); ?>">
                                Set as Thumbnail
                            </label>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
        <div class="form-group mb-3">
            <label for="price" class="form-label">Price</label>
            <input type="number" class="form-control" id="price" name="price" value="<?php echo e($product->price); ?>" min="0" required>
        </div>
        <div class="form-group mb-3">
            <label for="category_id" class="form-label">Category</label>
            <select class="form-control" id="category_id" name="category_id" required>
                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($category->id); ?>" <?php echo e($product->category_id == $category->id ? 'selected' : ''); ?>><?php echo e($category->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Update Product</button>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('styles'); ?>
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
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\online-marketplace\resources\views/products/edit.blade.php ENDPATH**/ ?>