@extends('layouts.app')

@section('title', 'Reader Dashboard - TechNews')

@section('content')
<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h4 class="fw-bold mb-0">
                    <i class="fas fa-user me-2 text-primary"></i>My Activity
                </h4>
                <span class="badge bg-secondary">{{ $comments->total() }} comments</span>
            </div>
            <div class="card-body">
                @if($comments->count() > 0)
                    @foreach($comments as $comment)
                        <div class="border-bottom pb-3 mb-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <p class="mb-1">{{ $comment->comment }}</p>
                                    <small class="text-muted">
                                        <i class="far fa-clock me-1"></i>
                                        {{ $comment->created_at->diffForHumans() }}
                                        on
                                        <a href="{{ route('articles.show', $comment->article->slug) }}" 
                                           class="text-decoration-none">
                                            {{ Str::limit($comment->article->title, 40) }}
                                        </a>
                                    </small>
                                </div>
                                <form action="{{ route('comments.destroy', $comment) }}" method="POST" class="ms-2">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-link text-danger p-0" 
                                            onclick="return confirm('Delete this comment?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                    <div class="mt-3">
                        {{ $comments->links() }}
                    </div>
                @else
                    <div class="text-center text-muted py-5">
                        <i class="fas fa-comment-slash fa-3x d-block mb-3 text-muted opacity-50"></i>
                        <p class="mb-0">You haven't commented on any articles yet.</p>
                        <a href="{{ route('home') }}" class="btn btn-primary mt-3">
                            <i class="fas fa-newspaper me-2"></i>Read Articles
                        </a>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Become an Author -->
        <div class="card shadow-sm border-0 mt-4">
            <div class="card-body text-center">
                <h5 class="fw-bold">
                    <i class="fas fa-pen-fancy me-2 text-primary"></i>Want to share your knowledge?
                </h5>
                <p class="text-muted small mb-3">
                    Become an author and start publishing your own articles on TechNews.
                </p>
                
                <!-- ✅ This form uses the POST route -->
                <form action="{{ route('switch.to.author') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary btn-lg px-4">
                        <i class="fas fa-user-edit me-2"></i>Switch to Author
                    </button>
                </form>
                
                <p class="mt-2 text-muted small">
                    <i class="fas fa-info-circle me-1"></i>
                    You will be redirected to dashboard after switching.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection