@extends('layouts.app')

@section('title', 'Search Results - TechNews')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="mb-4">
            <h1 class="display-6 fw-bold">
                <i class="fas fa-search me-2 text-primary"></i>Search Results
            </h1>
            @if(isset($keyword))
                <p class="text-muted">
                    Showing results for: <span class="fw-semibold text-dark">"{{ $keyword }}"</span>
                    <span class="badge bg-secondary bg-opacity-10 text-secondary ms-2">
                        {{ $articles->total() }} results
                    </span>
                </p>
            @endif
        </div>
        
        @if($articles->count() > 0)
            <div class="row g-4">
                @foreach($articles as $article)
                    <div class="col-md-6">
                        <article class="card h-100 shadow-sm hover-card border-0">
                            <div class="card-body">
                                <div class="mb-2">
                                    <a href="{{ route('categories.show', $article->category->slug) }}" 
                                       class="badge bg-primary text-decoration-none">
                                        {{ $article->category->name }}
                                    </a>
                                </div>
                                <h5 class="card-title">
                                    <a href="{{ route('articles.show', $article->slug) }}" 
                                       class="text-decoration-none text-dark stretched-link">
                                        {{ $article->title }}
                                    </a>
                                </h5>
                                <p class="card-text text-muted small">
                                    {!! Str::limit(strip_tags($article->content), 120) !!}
                                </p>
                                <div class="d-flex align-items-center mt-2">
                                    <img src="{{ $article->author->profile_image }}" 
                                         alt="{{ $article->author->name }}" 
                                         class="rounded-circle me-2" width="28" height="28">
                                    <small class="text-muted">{{ $article->author->name }}</small>
                                    <span class="mx-2 text-muted">•</span>
                                    <small class="text-muted">{{ $article->published_at->diffForHumans() }}</small>
                                </div>
                            </div>
                        </article>
                    </div>
                @endforeach
            </div>
            
            <div class="d-flex justify-content-center mt-4">
                {{ $articles->appends(['search' => $keyword])->links() }}
            </div>
        @else
            <div class="alert alert-info text-center py-5">
                <i class="fas fa-search fa-3x d-block mb-3 text-muted"></i>
                <h4>No results found</h4>
                <p class="mb-0">Try adjusting your search terms or browse our categories.</p>
                <a href="{{ route('home') }}" class="btn btn-primary mt-3">
                    <i class="fas fa-home me-2"></i>Return Home
                </a>
            </div>
        @endif
    </div>
    
    <!-- Sidebar -->
    <aside class="col-lg-4">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white fw-bold">
                <i class="fas fa-search me-2 text-primary"></i>Search Again
            </div>
            <div class="card-body">
                <form action="{{ route('articles.search') }}" method="GET">
                    <div class="input-group">
                        <input type="text" class="form-control" name="search" 
                               placeholder="Search articles..." value="{{ $keyword ?? '' }}">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Categories Widget -->
        <div class="card shadow-sm">
            <div class="card-header bg-white fw-bold">
                <i class="fas fa-folder-open me-2 text-primary"></i>Categories
            </div>
            <div class="card-body p-0">
                @php
                    $categories = App\Models\Category::withCount('articles')->get();
                @endphp
                @foreach($categories as $category)
                    <a href="{{ route('categories.show', $category->slug) }}" 
                       class="d-flex justify-content-between align-items-center text-decoration-none text-dark px-3 py-2 border-bottom hover-bg">
                        <span>{{ $category->name }}</span>
                        <span class="badge bg-secondary bg-opacity-10 text-secondary">{{ $category->articles_count }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </aside>
</div>
@endsection

@push('styles')
<style>
    .hover-bg:hover {
        background-color: #f7fafc;
    }
</style>
@endpush