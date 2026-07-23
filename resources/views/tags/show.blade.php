@extends('layouts.app')

@section('title', '#' . $tag->name . ' - TechNews')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="mb-4">
            <div class="d-flex align-items-center gap-3 mb-2">
                <h1 class="display-6 fw-bold mb-0">
                    <i class="fas fa-tag me-2 text-primary"></i>#{{ $tag->name }}
                </h1>
                <span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2">
                    {{ $articles->total() }} articles
                </span>
            </div>
            <p class="text-muted">Articles tagged with "{{ $tag->name }}"</p>
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
                        </div>
                    </article>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info text-center py-5">
                        <i class="fas fa-tag fa-3x d-block mb-3 text-muted"></i>
                        <h4>No articles with this tag</h4>
                        <p class="mb-0">Check out other tags or browse our latest articles.</p>
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
        <!-- Popular Tags -->
        <div class="card shadow-sm">
            <div class="card-header bg-white fw-bold">
                <i class="fas fa-tags me-2 text-primary"></i>Popular Tags
            </div>
            <div class="card-body">
                @php
                    $allTags = App\Models\Tag::withCount('articles')->orderBy('articles_count', 'desc')->limit(20)->get();
                @endphp
                <div class="d-flex flex-wrap gap-2">
                    @foreach($allTags as $t)
                        <a href="{{ route('tags.show', $t->slug) }}" 
                           class="tag-pill {{ $t->id === $tag->id ? 'bg-primary text-white' : 'bg-light text-secondary' }} 
                                  text-decoration-none">
                            #{{ $t->name }}
                            <span class="badge {{ $t->id === $tag->id ? 'bg-white text-primary' : 'bg-secondary bg-opacity-10 text-secondary' }} 
                                          ms-1">{{ $t->articles_count }}</span>
                        </a>
                    @endforeach
                </div>
                <div class="text-center mt-3">
                    <a href="{{ route('tags.index') }}" class="btn btn-link btn-sm text-primary">
                        <i class="fas fa-arrow-left me-1"></i>View all tags
                    </a>
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
    }
</style>
@endpush