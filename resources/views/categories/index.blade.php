@extends('layouts.app')

@section('title', 'Categories - TechNews')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="display-6 fw-bold">
                <i class="fas fa-folder-open me-2 text-primary"></i>Categories
            </h1>
            <span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2">
                {{ $categories->count() }} categories
            </span>
        </div>
        
        <div class="row g-4">
            @forelse($categories as $category)
                <div class="col-md-4 col-lg-3">
                    <a href="{{ route('categories.show', $category->slug) }}" 
                       class="text-decoration-none text-dark">
                        <div class="card h-100 shadow-sm hover-card text-center p-4 border-0">
                            <div class="category-icon mb-3">
                                <i class="fas fa-folder fa-3x text-primary opacity-50"></i>
                            </div>
                            <h5 class="card-title mb-1 fw-bold">{{ $category->name }}</h5>
                            <p class="text-muted small mb-0">
                                {{ $category->articles_count }} articles
                            </p>
                        </div>
                    </a>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info text-center py-5">
                        <i class="fas fa-folder-open fa-3x d-block mb-3 text-muted"></i>
                        <h4>No categories yet</h4>
                        <p class="mb-0">Categories will appear here as articles are published.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection