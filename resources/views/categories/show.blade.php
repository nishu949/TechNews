@extends('layouts.app')

@section('title', $category->name . ' - TechNews')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="mb-4">
            <div class="d-flex align-items-center gap-3 mb-2">
                <h1 class="display-6 fw-bold mb-0">
                    <i class="fas fa-folder-open me-2 text-primary"></i>{{ $category->name }}
                </h1>
                <span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2">
                    {{ $articles->total() }} articles
                </span>
            </div>
            <p class="text-muted">Articles in the "{{ $category->name }}" category</p>
        </div>
        
        <div class="row g-4">
            @forelse($articles as $article)
                <div class="col-md-6">
                    <article class="card h-100 shadow-sm hover-card border-0">
                        @if($article->featured_image)
                            <img src="{{ asset('storage/' . $article->featured_image) }}" 
                                 class="card-img-top" alt="{{ $article->title }}" 
                                 style="height: 180px; object-fit: cover;">
                        @else
                            <div class="card-img-top bg-gradient-primary" style="height: 180px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-newspaper text-white" style="font-size: 3rem; opacity: 0.3;"></i>
                            </div>
                        @endif
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">
                                <a href="{{ route('articles.show', $article->slug) }}" 
                                   class="text-decoration-none text-dark stretched-link">
                                    {{ $article->title }}
                                </a>
                            </h5>
                            <p class="card-text text-muted small flex-grow-1">
                                {{ Str::limit($article->excerpt ?? strip_tags($article->content), 100) }}
                            </p>
                            <div class="d-flex align-items-center mt-2">
                                <img src="{{ $article->author->profile_image }}" 
                                     alt="{{ $article->author->name }}" 
                                     class="rounded-circle me-2" width="28" height="28">
                                <small class="text-muted">{{ $article->author->name }}</small>
                                <span class="mx-2 text-muted">•</span>
                                <small class="text-muted">{{ $article->published_at->diffForHumans() }}</small>
                            </div>
                            @if($article->tags->count())
                                <div class="mt-2">
                                    @foreach($article->tags->take(3) as $tag)
                                        <a href="{{ route('tags.show', $tag->slug) }}" 
                                           class="tag-pill bg-light text-secondary text-decoration-none small">
                                            #{{ $tag->name }}
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </article>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info text-center py-5">
                        <i class="fas fa-folder-open fa-3x d-block mb-3 text-muted"></i>
                        <h4>No articles in this category</h4>
                        <p class="mb-0">Check back later for new content.</p>
                        <a href="{{ route('home') }}" class="btn btn-primary mt-3">
                            <i class="fas fa-home me-2"></i>Browse Articles
                        </a>
                    </div>
                </div>
            @endforelse
        </div>
        
        <div class="d-flex justify-content-center mt-4">
            {{ $articles->links() }}
        </div>
    </div>
    
    <!-- Sidebar -->
    <aside class="col-lg-4">
        <!-- All Categories -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white fw-bold">
                <i class="fas fa-folder-open me-2 text-primary"></i>All Categories
            </div>
            <div class="card-body p-0">
                @php
                    $categories = App\Models\Category::withCount('articles')->get();
                @endphp
                @foreach($categories as $cat)
                    <a href="{{ route('categories.show', $cat->slug) }}" 
                       class="d-flex justify-content-between align-items-center text-decoration-none px-3 py-2 border-bottom 
                              {{ $cat->id === $category->id ? 'bg-light fw-semibold' : '' }}
                              text-dark hover-bg">
                        <span>
                            @if($cat->id === $category->id)
                                <i class="fas fa-chevron-right text-primary me-2"></i>
                            @endif
                            {{ $cat->name }}
                        </span>
                        <span class="badge {{ $cat->id === $category->id ? 'bg-primary text-white' : 'bg-secondary bg-opacity-10 text-secondary' }}">
                            {{ $cat->articles_count }}
                        </span>
                    </a>
                @endforeach
            </div>
        </div>
        
        <!-- Popular Tags -->
        <div class="card shadow-sm">
            <div class="card-header bg-white fw-bold">
                <i class="fas fa-tags me-2 text-primary"></i>Popular Tags
            </div>
            <div class="card-body">
                @php
                    $tags = App\Models\Tag::withCount('articles')->orderBy('articles_count', 'desc')->limit(15)->get();
                @endphp
                <div class="d-flex flex-wrap gap-2">
                    @foreach($tags as $tag)
                        <a href="{{ route('tags.show', $tag->slug) }}" 
                           class="tag-pill bg-light text-secondary text-decoration-none">
                            #{{ $tag->name }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </aside>
</div>
@endsection

@push('styles')
<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #2d3748, #4a5568);
    }
    .tag-pill {
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
        transition: all 0.2s;
    }
    .tag-pill:hover {
        transform: translateY(-2px);
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        background: #2d3748 !important;
        color: white !important;
    }
    .hover-bg:hover {
        background-color: #f7fafc;
    }
</style>
@endpush