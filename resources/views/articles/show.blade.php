@extends('layouts.app')

@section('title', $article->title . ' - TechNews')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8 col-xl-7">
        <!-- Article Header -->
        <header class="mb-5">
            <!-- Category Badge -->
            <div class="mb-3">
                <a href="{{ route('categories.show', $article->category->slug) }}" 
                   class="badge bg-primary text-decoration-none px-3 py-2" 
                   style="font-size: 0.8rem; letter-spacing: 0.5px; text-transform: uppercase;">
                    {{ $article->category->name }}
                </a>
            </div>
            
            <!-- Title -->
            <h1 class="display-5 fw-bold mb-4" style="line-height: 1.2;">
                {{ $article->title }}
            </h1>
            
            <!-- Author & Meta Info -->
            <div class="d-flex align-items-center gap-3 flex-wrap">
                <div class="d-flex align-items-center">
                    <img src="{{ $article->author->profile_image }}" 
                         alt="{{ $article->author->name }}" 
                         class="rounded-circle" 
                         style="width: 48px; height: 48px; object-fit: cover;">
                    <div class="ms-3">
                        <div class="fw-semibold text-dark">{{ $article->author->name }}</div>
                        <div class="text-muted small">
                            <span>
                                <i class="far fa-calendar-alt me-1"></i>
                                {{ $article->published_at->format('M d, Y') }}
                            </span>
                            <span class="mx-2">•</span>
                            <span>
                                <i class="far fa-clock me-1"></i>
                                {{ $article->reading_time }} min read
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Tags -->
            @if($article->tags->count() > 0)
                <div class="mt-3 d-flex flex-wrap gap-2">
                    @foreach($article->tags as $tag)
                        <a href="{{ route('tags.show', $tag->slug) }}" 
                           class="badge bg-light text-secondary text-decoration-none px-3 py-2"
                           style="font-weight: 500; font-size: 0.75rem; border: 1px solid #e2e8f0;">
                            #{{ $tag->name }}
                        </a>
                    @endforeach
                </div>
            @endif
        </header>
        
        <!-- Featured Image -->
        @if($article->featured_image)
            <div class="mb-5">
                <img src="{{ asset('storage/' . $article->featured_image) }}" 
                     alt="{{ $article->title }}" 
                     class="img-fluid rounded-4 shadow-sm w-100"
                     style="max-height: 450px; object-fit: cover;">
            </div>
        @endif
        
        <!-- Article Content -->
        <div class="article-content">
            {!! $html !!}
        </div>
        
        <!-- Article Footer -->
        <footer class="mt-5 pt-4 border-top">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div class="text-muted small">
                    <i class="far fa-calendar-alt me-1"></i>
                    Published on {{ $article->published_at->format('F d, Y') }}
                </div>
                
                @auth
                    @if(auth()->user()->id === $article->user_id)
                        <div>
                            <a href="{{ route('articles.edit', $article->slug) }}" 
                               class="btn btn-outline-primary btn-sm me-2">
                                <i class="fas fa-edit me-1"></i>Edit
                            </a>
                            <button type="button" class="btn btn-outline-danger btn-sm" 
                                    data-bs-toggle="modal" data-bs-target="#deleteModal">
                                <i class="fas fa-trash me-1"></i>Delete
                            </button>
                        </div>
                    @endif
                @endauth
            </div>
        </footer>
        
        <!-- Comments Section -->
        <section class="mt-5 pt-4 border-top">
            <h3 class="h4 fw-bold mb-4">
                <i class="fas fa-comments me-2 text-primary"></i>
                Comments 
                <span class="badge bg-secondary bg-opacity-10 text-secondary ms-2">
                    {{ $article->comments->count() }}
                </span>
            </h3>
            
            <!-- Comment Form -->
            @auth
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body">
                        <form action="{{ route('comments.store', $article) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="comment" class="form-label fw-semibold small text-muted">
                                    Leave a comment as <span class="text-primary">{{ auth()->user()->name }}</span>
                                </label>
                                <textarea class="form-control @error('comment') is-invalid @enderror" 
                                          id="comment" name="comment" rows="3" 
                                          placeholder="Share your thoughts on this article..." 
                                          style="resize: vertical; border-radius: 12px;"></textarea>
                                @error('comment')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-2"></i>Post Comment
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <div class="alert alert-info border-0 d-flex align-items-center gap-3" style="border-radius: 12px;">
                    <i class="fas fa-info-circle fa-2x text-primary"></i>
                    <div>
                        <p class="mb-0">Please <a href="{{ route('login') }}" class="fw-semibold">login</a> to leave a comment.</p>
                    </div>
                </div>
            @endauth
            
            <!-- Comments List -->
            @forelse($article->comments as $comment)
                <div class="card shadow-sm border-0 mb-3" style="border-radius: 12px;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="d-flex align-items-center">
                                <img src="{{ $comment->user->profile_image }}" 
                                     alt="{{ $comment->user->name }}" 
                                     class="rounded-circle" 
                                     style="width: 36px; height: 36px; object-fit: cover;">
                                <div class="ms-2">
                                    <div class="fw-semibold">{{ $comment->user->name }}</div>
                                    <small class="text-muted">
                                        <i class="far fa-clock me-1"></i>
                                        {{ $comment->created_at->diffForHumans() }}
                                    </small>
                                </div>
                            </div>
                            @if(auth()->id() === $comment->user_id)
                                <form action="{{ route('comments.destroy', $comment) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-link text-danger p-0" 
                                            onclick="return confirm('Delete this comment?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                        <p class="mb-0 mt-2" style="color: #4a5568;">{{ $comment->comment }}</p>
                    </div>
                </div>
            @empty
                <div class="text-center text-muted py-5">
                    <i class="fas fa-comment-slash fa-3x d-block mb-3 text-muted opacity-50"></i>
                    <p class="mb-0">No comments yet. Be the first to share your thoughts!</p>
                </div>
            @endforelse
        </section>
    </div>
</div>

<!-- Delete Modal -->
@auth
    @if(auth()->user()->id === $article->user_id)
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 16px;">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Delete Article</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete "<strong>{{ $article->title }}</strong>"?</p>
                    <p class="text-danger small mb-0">⚠️ This action cannot be undone.</p>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form action="{{ route('articles.destroy', $article->slug) }}" method="POST">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash me-2"></i>Delete Article
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif
@endauth
@endsection

@push('styles')
<style>
    /* Article Content Styling */
    .article-content {
        font-size: 1.125rem;
        line-height: 1.9;
        color: #2d3748;
    }
    
    .article-content h1,
    .article-content h2,
    .article-content h3,
    .article-content h4 {
        font-weight: 800;
        margin-top: 2.5rem;
        margin-bottom: 1rem;
        letter-spacing: -0.02em;
        color: #1a202c;
    }
    
    .article-content h1 { font-size: 2.25rem; }
    .article-content h2 { font-size: 1.75rem; }
    .article-content h3 { font-size: 1.5rem; }
    .article-content h4 { font-size: 1.25rem; }
    
    .article-content p {
        margin-bottom: 1.5rem;
    }
    
    .article-content ul,
    .article-content ol {
        padding-left: 1.5rem;
        margin-bottom: 1.5rem;
    }
    
    .article-content li {
        margin-bottom: 0.5rem;
    }
    
    .article-content a {
        color: #4299e1;
        text-decoration: none;
        font-weight: 500;
    }
    
    .article-content a:hover {
        text-decoration: underline;
    }
    
    /* Code Blocks */
    .article-content pre {
        background: #1a202c;
        color: #f7fafc;
        padding: 1.5rem;
        border-radius: 12px;
        overflow-x: auto;
        margin: 1.5rem 0;
        position: relative;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    
    .article-content pre code {
        color: #e2e8f0;
        font-size: 0.9rem;
        line-height: 1.8;
        font-family: 'JetBrains Mono', 'Fira Code', monospace;
    }
    
    /* Inline Code */
    .article-content code:not(pre code) {
        background: #edf2f7;
        color: #2d3748;
        padding: 0.15rem 0.4rem;
        border-radius: 4px;
        font-size: 0.85em;
        font-family: 'JetBrains Mono', 'Fira Code', monospace;
    }
    
    /* Blockquotes */
    .article-content blockquote {
        border-left: 4px solid #4299e1;
        padding: 1rem 1.5rem;
        margin: 1.5rem 0;
        background: #f7fafc;
        border-radius: 0 8px 8px 0;
        font-style: italic;
        color: #4a5568;
    }
    
    .article-content blockquote p:last-child {
        margin-bottom: 0;
    }
    
    /* Images */
    .article-content img {
        max-width: 100%;
        height: auto;
        border-radius: 12px;
        margin: 1.5rem 0;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }
    
    /* Tables */
    .article-content table {
        width: 100%;
        border-collapse: collapse;
        margin: 1.5rem 0;
    }
    
    .article-content table th,
    .article-content table td {
        padding: 0.75rem 1rem;
        border: 1px solid #e2e8f0;
    }
    
    .article-content table th {
        background: #f7fafc;
        font-weight: 600;
    }
    
    /* Horizontal Rule */
    .article-content hr {
        border: none;
        height: 1px;
        background: linear-gradient(to right, #e2e8f0, transparent);
        margin: 2.5rem 0;
    }
    
    /* Mobile Responsive */
    @media (max-width: 768px) {
        .article-content {
            font-size: 1rem;
        }
        
        .article-content h1 { font-size: 1.75rem; }
        .article-content h2 { font-size: 1.5rem; }
        .article-content h3 { font-size: 1.25rem; }
        .article-content h4 { font-size: 1.1rem; }
    }
</style>
@endpush