@extends('layouts.app')

@section('title', 'Developer News & Tutorials - TechNews')

@section('content')
<div class="row g-4">
    <!-- Main Content -->
    <div class="col-lg-8">
        <!-- Hero Section -->
        <div class="hero-section mb-5 pb-4">
            <h1 class="display-4 fw-bold mb-3" style="letter-spacing: -0.03em;">
                Developer News &amp; Tutorials
            </h1>
            <p class="lead text-muted" style="font-size: 1.25rem;">
                Explore Laravel, PHP, JavaScript, React, MySQL and modern web development.
            </p>
            
            <!-- Quick Category Pills -->
            <div class="d-flex flex-wrap gap-2 mt-3">
                @php
                    $quickCategories = App\Models\Category::limit(4)->get();
                @endphp
                @foreach($quickCategories as $cat)
                    <a href="{{ route('categories.show', $cat->slug) }}" 
                       class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 text-decoration-none"
                       style="font-weight: 600; border-radius: 20px;">
                        {{ $cat->name }}
                    </a>
                @endforeach
            </div>
        </div>
        
        <!-- Featured Article -->
        @if($articles->count() > 0)
            @php $featured = $articles->first(); @endphp
            <div class="card shadow-lg border-0 mb-5 overflow-hidden" style="border-radius: 16px; background: white;">
                <div class="row g-0">
                    <div class="col-md-6">
                        @if($featured->featured_image)
                            <img src="{{ asset('storage/' . $featured->featured_image) }}" 
                                 class="img-fluid w-100 h-100" alt="{{ $featured->title }}"
                                 style="object-fit: cover; min-height: 280px;">
                        @else
                            <div class="bg-gradient-primary h-100 d-flex align-items-center justify-content-center" style="min-height: 280px; background: linear-gradient(135deg, #2d3748, #4a5568);">
                                <i class="fas fa-newspaper text-white" style="font-size: 4rem; opacity: 0.3;"></i>
                            </div>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <div class="card-body d-flex flex-column h-100 p-4">
                            <div class="mb-2">
                                <a href="{{ route('categories.show', $featured->category->slug) }}" 
                                   class="badge bg-primary text-decoration-none px-3 py-2" style="border-radius: 20px;">
                                    {{ $featured->category->name }}
                                </a>
                            </div>
                            <h3 class="card-title h4 fw-bold">
                                <a href="{{ route('articles.show', $featured->slug) }}" 
                                   class="text-decoration-none text-dark stretched-link">
                                    {{ $featured->title }}
                                </a>
                            </h3>
                            <p class="card-text text-muted flex-grow-1">
                                {{ Str::limit($featured->excerpt ?? strip_tags($featured->content), 120) }}
                            </p>
                            <div class="d-flex align-items-center mt-2">
                                <img src="{{ $featured->author->profile_image }}" 
                                     alt="{{ $featured->author->name }}" 
                                     class="rounded-circle me-2" width="32" height="32">
                                <div>
                                    <div class="small fw-semibold">{{ $featured->author->name }}</div>
                                    <div class="small text-muted">
                                        <i class="far fa-clock me-1"></i>
                                        {{ $featured->published_at->diffForHumans() }}
                                        <span class="mx-1">•</span>
                                        {{ $featured->reading_time }} min read
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        
        <!-- Article Grid -->
        <div class="row g-4">
            @forelse($articles->skip(1) as $article)
                <div class="col-md-6">
                    <article class="card h-100 shadow-sm border-0" style="border-radius: 12px; overflow: hidden; background: white; transition: all 0.3s ease;">
                        @if($article->featured_image)
                            <img src="{{ asset('storage/' . $article->featured_image) }}" 
                                 class="card-img-top" alt="{{ $article->title }}" 
                                 style="height: 200px; object-fit: cover;">
                        @else
                            <div class="card-img-top" style="height: 200px; background: linear-gradient(135deg, #e2e8f0, #cbd5e0); display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-newspaper" style="font-size: 3rem; color: #a0aec0;"></i>
                            </div>
                        @endif
                        <div class="card-body d-flex flex-column p-4">
                            <div class="mb-2">
                                <a href="{{ route('categories.show', $article->category->slug) }}" 
                                   class="badge bg-primary text-decoration-none px-3 py-2" style="border-radius: 20px; font-weight: 500;">
                                    {{ $article->category->name }}
                                </a>
                            </div>
                            <h5 class="card-title fw-bold mb-2">
                                <a href="{{ route('articles.show', $article->slug) }}" 
                                   class="text-decoration-none text-dark stretched-link" 
                                   style="line-height: 1.3;">
                                    {{ $article->title }}
                                </a>
                            </h5>
                            <p class="card-text text-muted small flex-grow-1" style="line-height: 1.6;">
                                {{ Str::limit($article->excerpt ?? strip_tags($article->content), 100) }}
                            </p>
                            <div class="d-flex align-items-center mt-3 pt-2 border-top">
                                <img src="{{ $article->author->profile_image }}" 
                                     alt="{{ $article->author->name }}" 
                                     class="rounded-circle me-2" width="28" height="28">
                                <div class="flex-grow-1">
                                    <small class="text-muted d-block" style="line-height: 1.2;">{{ $article->author->name }}</small>
                                    <small class="text-muted" style="font-size: 0.7rem;">
                                        <i class="far fa-clock me-1"></i>
                                        {{ $article->published_at->diffForHumans() }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </article>
                </div>
            @empty
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="fas fa-inbox fa-4x d-block mb-4 text-muted opacity-50"></i>
                        <h4 class="fw-bold">No articles published yet</h4>
                        <p class="text-muted">Be the first to share your knowledge!</p>
                        @auth
                            @if(auth()->user()->isAuthor())
                                <a href="{{ route('articles.create') }}" class="btn btn-primary">
                                    <i class="fas fa-pen me-2"></i>Write an Article
                                </a>
                            @endif
                        @else
                            <a href="{{ route('register') }}" class="btn btn-primary">
                                <i class="fas fa-user-plus me-2"></i>Join as Author
                            </a>
                        @endauth
                    </div>
                </div>
            @endforelse
        </div>
        
        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $articles->links() }}
        </div>
    </div>
    
    <!-- Sidebar -->
    <aside class="col-lg-4">
        <!-- Categories Widget -->
        <div class="card shadow-sm border-0 mb-4" style="border-radius: 12px; background: white;">
            <div class="card-header bg-white border-0 pt-4">
                <h5 class="fw-bold mb-0">
                    <i class="fas fa-folder-open me-2 text-primary"></i>Categories
                </h5>
            </div>
            <div class="card-body p-0">
                @php
                    $categories = App\Models\Category::withCount('articles')->orderBy('name')->get();
                @endphp
                @foreach($categories as $category)
                    <a href="{{ route('categories.show', $category->slug) }}" 
                       class="d-flex justify-content-between align-items-center text-decoration-none text-dark px-4 py-2 border-bottom hover-bg"
                       style="transition: background 0.2s;">
                        <span>
                            <i class="fas fa-folder text-primary me-2" style="font-size: 0.8rem; opacity: 0.6;"></i>
                            {{ $category->name }}
                        </span>
                        <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3 py-1">
                            {{ $category->articles_count }}
                        </span>
                    </a>
                @endforeach
            </div>
        </div>
        
        <!-- Tags Widget -->
        <div class="card shadow-sm border-0 mb-4" style="border-radius: 12px; background: white;">
            <div class="card-header bg-white border-0 pt-4">
                <h5 class="fw-bold mb-0">
                    <i class="fas fa-tags me-2 text-primary"></i>Popular Tags
                </h5>
            </div>
            <div class="card-body">
                @php
                    $tags = App\Models\Tag::withCount('articles')->orderBy('articles_count', 'desc')->limit(15)->get();
                @endphp
                <div class="d-flex flex-wrap gap-2">
                    @foreach($tags as $tag)
                        <a href="{{ route('tags.show', $tag->slug) }}" 
                           class="tag-pill bg-light text-secondary text-decoration-none"
                           style="padding: 0.35rem 0.85rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; border: 1px solid #e2e8f0; transition: all 0.2s ease; background: #f7fafc;">
                            #{{ $tag->name }}
                        </a>
                    @endforeach
                </div>
                @if($tags->count() > 0)
                    <div class="text-center mt-3">
                        <a href="{{ route('tags.index') }}" class="btn btn-link btn-sm text-primary text-decoration-none fw-semibold">
                            View all tags <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- About Widget -->
        <div class="card shadow-sm border-0" style="border-radius: 12px; background: white;">
            <div class="card-header bg-white border-0 pt-4">
                <h5 class="fw-bold mb-0">
                    <i class="fas fa-info-circle me-2 text-primary"></i>About TechNews
                </h5>
            </div>
            <div class="card-body">
                <p class="text-muted small" style="line-height: 1.7;">
                    A developer blog and tech article publishing platform built with Laravel. 
                    Share your knowledge with the developer community.
                </p>
                @auth
                    @if(auth()->user()->isAuthor())
                        <a href="{{ route('articles.create') }}" class="btn btn-primary btn-sm w-100" style="border-radius: 8px;">
                            <i class="fas fa-pen me-2"></i>Write an Article
                        </a>
                    @endif
                @else
                    <a href="{{ route('register') }}" class="btn btn-primary btn-sm w-100" style="border-radius: 8px;">
                        <i class="fas fa-user-plus me-2"></i>Join as Author
                    </a>
                @endauth
            </div>
        </div>
    </aside>
</div>
@endsection

@push('styles')
<style>
    .hero-section {
        border-bottom: 2px solid #edf2f7;
    }
    
    .card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        background: #ffffff;
    }
    
    .card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 40px rgba(0,0,0,0.08) !important;
    }
    
    .hover-bg {
        transition: background 0.2s ease;
    }
    
    .hover-bg:hover {
        background-color: #f7fafc;
    }
    
    .tag-pill {
        transition: all 0.2s ease;
    }
    
    .tag-pill:hover {
        transform: translateY(-2px);
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        background: #2d3748 !important;
        color: white !important;
        border-color: #2d3748 !important;
    }
    
    .bg-opacity-10 {
        --bs-bg-opacity: 0.1;
    }
    
    .badge {
        font-weight: 600;
    }
    
    /* Card shadow for better visibility */
    .shadow-sm {
        box-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04) !important;
    }
    
    .shadow-lg {
        box-shadow: 0 10px 40px rgba(0,0,0,0.08) !important;
    }
    
    /* Border top for article meta */
    .border-top {
        border-top: 1px solid #edf2f7 !important;
    }
</style>
@endpush