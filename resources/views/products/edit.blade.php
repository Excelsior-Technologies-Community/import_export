@extends('layouts.app')

@section('title', 'Edit Product')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5>Edit Product: {{ $product->name }}</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="name" class="form-label">Product Name *</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                name="name" value="{{ old('name', $product->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="sku" class="form-label">SKU *</label>
                            <input type="text" class="form-control @error('sku') is-invalid @enderror" id="sku" name="sku"
                                value="{{ old('sku', $product->sku) }}" required>
                            @error('sku')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="price" class="form-label">Price *</label>
                            <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror"
                                id="price" name="price" value="{{ old('price', $product->price) }}" required>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="quantity" class="form-label">Quantity *</label>
                            <input type="number" class="form-control @error('quantity') is-invalid @enderror" id="quantity"
                                name="quantity" value="{{ old('quantity', $product->quantity) }}" required>
                            @error('quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="category" class="form-label">Category *</label>
                            <input type="text" class="form-control @error('category') is-invalid @enderror" id="category"
                                name="category" value="{{ old('category', $product->category) }}" required>
                            @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description"
                                name="description" rows="3">{{ old('description', $product->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="images" class="form-label">Add More Images</label>
                            <input type="file" class="form-control @error('images.*') is-invalid @enderror" id="images"
                                name="images[]" multiple accept="image/*">
                            <div class="form-text">Select additional images</div>
                            @error('images.*')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <div id="imagePreview" class="mt-2"></div>
                        </div>
                    </div>
                </div>

                <!-- Existing Images -->
                @if($product->images->count() > 0)
                    <div class="mb-4">
                        <label class="form-label">Existing Images</label>
                        <div class="row">
                            @foreach($product->images as $image)
                                <div class="col-md-2 mb-3 image-container">
                                    <input type="checkbox" class="image-checkbox" name="deleted_images[]" value="{{ $image->id }}"
                                        id="image-{{ $image->id }}">
                                    <label for="image-{{ $image->id }}" class="d-block">
                                        <img src="{{ asset('storage/' . $image->image_path) }}" alt="Product Image"
                                            class="img-thumbnail w-100" style="height: 100px; object-fit: cover;">
                                        <small class="text-muted d-block text-center">Click to delete</small>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="d-flex justify-content-between">
                    <a href="{{ route('products.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update Product</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Image preview for new images
        document.getElementById('images').addEventListener('change', function (e) {
            const preview = document.getElementById('imagePreview');
            preview.innerHTML = '';

            Array.from(e.target.files).forEach(file => {
                const reader = new FileReader();
                reader.onload = function (e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'image-preview';
                    preview.appendChild(img);
                }
                reader.readAsDataURL(file);
            });
        });

        // Toggle image selection
        document.querySelectorAll('.image-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function () {
                const label = this.closest('label');
                if (this.checked) {
                    label.classList.add('opacity-50');
                } else {
                    label.classList.remove('opacity-50');
                }
            });
        });
    </script>
@endsection