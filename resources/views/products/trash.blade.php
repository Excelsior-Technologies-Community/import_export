@extends('layouts.app')

@section('title', 'Trash')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5>Trash Bin</h5>
        <a href="{{ route('products.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back to Products
        </a>
    </div>
    <div class="card-body">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Deleted At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                <tr>
                    <td>{{ $product->id }}</td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->deleted_at->format('Y-m-d H:i:s') }}</td>
                    <td>
                        <form action="{{ route('products.restore', $product->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-success">
                                <i class="bi bi-arrow-counterclockwise"></i> Restore
                            </button>
                        </form>
                        <form action="{{ route('products.forceDelete', $product->id) }}" method="POST" 
                              class="d-inline" onsubmit="return confirm('Permanently delete? This cannot be undone!')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">
                                <i class="bi bi-trash-fill"></i> Delete Forever
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center">Trash is empty.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        <div class="d-flex justify-content-center">
            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection