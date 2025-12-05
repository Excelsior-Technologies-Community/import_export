@extends('layouts.app')

@section('title', 'Products List')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5>Products List</h5>
            <div>
                <a href="{{ route('products.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Add Product
                </a>
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#importModal">
                    <i class="bi bi-upload"></i> Import
                </button>
                <a href="{{ route('products.export') }}" class="btn btn-warning">
                    <i class="bi bi-download"></i> Export
                </a>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Category</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td>{{ $product->id }}</td>
                            <td>
                                @if($product->images->count() > 0)
                                    <img src="{{ asset('storage/' . $product->images->first()->image_path) }}"
                                        alt="{{ $product->name }}" style="height: 50px; width: auto; object-fit: cover;">
                                @else
                                    <img src="{{ asset('images/default-product.jpg') }}" alt="No image"
                                        style="height: 50px; width: auto; object-fit: cover;">
                                @endif
                            </td>
                            <td>{{ $product->name }}</td>
                            <td>${{ $product->price }}</td>
                            <td>{{ $product->quantity }}</td>
                            <td>{{ $product->category }}</td>
                            <td>
                                <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-info">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('products.destroy', $product) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Move to trash?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No products found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="d-flex justify-content-center">
                {{ $products->links() }}
            </div>
        </div>
    </div>

    <!-- Import Modal -->
    <div class="modal fade" id="importModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Import Products</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('products.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-info">
                            <h6><i class="bi bi-info-circle"></i> Import Instructions:</h6>
                            <ul class="mb-0">
                                <li>SKU must be unique for each product</li>
                                <li>Duplicate SKUs will be skipped</li>
                                <li>Required fields: Name, SKU</li>
                                <li>Optional fields: Description, Price, Quantity, Category, Images</li>
                                <li>For multiple images, separate paths with commas</li>
                            </ul>
                        </div>

                        <div class="mb-3">
                            <label for="file" class="form-label">Select File (Excel/CSV)</label>
                            <input type="file" class="form-control @error('file') is-invalid @enderror" name="file" required
                                accept=".xlsx,.xls,.csv">
                            @error('file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Import Options:</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="import_mode" id="skip_duplicates"
                                    value="skip" checked>
                                <label class="form-check-label" for="skip_duplicates">
                                    Skip duplicate SKUs (recommended)
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="import_mode" id="update_duplicates"
                                    value="update">
                                <label class="form-check-label" for="update_duplicates">
                                    Update existing products with matching SKU
                                </label>
                            </div>
                        </div>

                        <div class="mt-3">
                            <a href="{{ route('products.export') }}?template=true" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-download"></i> Download Template
                            </a>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Import Products</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection