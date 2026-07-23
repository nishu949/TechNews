@extends('layouts.app')

@section('title', 'Author Dashboard - TechNews')

@section('content')
<div class="row">
    <!-- Stats Cards -->
    <div class="col-12 mb-4">
        <div class="row g-3">
            <div class="col-md-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">Total Articles</h6>
                                <h2 class="fw-bold mb-0">{{ $stats['total_articles'] ?? 0 }}</h2>
                            </div>
                            <div class="bg-primary bg-opacity-10 p-3 rounded-circle">
                                <i class="fas fa-newspaper text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">Published</h6>
                                <h2 class="fw-bold mb-0">{{ $stats['published_articles'] ?? 0 }}</h2>
                            </div>
                            <div class="bg-success bg-opacity-10 p-3 rounded-circle">
                                <i class="fas fa-check-circle text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">Total Comments</h6>
                                <h2 class="fw-bold mb-0">{{ $stats['total_comments'] ?? 0 }}</h2>
                            </div>
                            <div class="bg-info bg-opacity-10 p-3 rounded-circle">
                                <i class="fas fa-comments text-info"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">My Articles</h6>
                                <h2 class="fw-bold mb-0">{{ $articles->total() }}</h2>
                            </div>
                            <div class="bg-warning bg-opacity-10 p-3 rounded-circle">
                                <i class="fas fa-pen-fancy text-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Comments -->
    <div class="col-lg-6 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0">
                    <i class="fas fa-comment-dots me-2 text-primary"></i>Recent Comments
                </h5>
            </div>
            <div class="card-body">
                @if(isset($stats['recent_comments']) && $stats['recent_comments']->count() > 0)
                    @foreach($stats['recent_comments'] as $comment)
                        <div class="border-bottom pb-3 mb-3">
                            <div class="d-flex align-items-start">
                                <img src="{{ $comment->user->profile_image }}" 
                                     alt="{{ $comment->user->name }}" 
                                     class="rounded-circle me-2" width="32" height="32">
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <span class="fw-semibold">{{ $comment->user->name }}</span>
                                            <span class="text-muted small">on</span>
                                            <a href="{{ route('articles.show', $comment->article->slug) }}" 
                                               class="text-decoration-none text-primary">
                                                {{ Str::limit($comment->article->title, 30) }}
                                            </a>
                                        </div>
                                        <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                                    </div>
                                    <p class="mb-0 small text-muted">{{ Str::limit($comment->comment, 100) }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="text-center text-muted py-3">
                        <i class="fas fa-comment-slash fa-2x d-block mb-2"></i>
                        <p class="mb-0">No comments on your articles yet.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="col-lg-6 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white">
                <h5 class="fw-bold mb-0">
                    <i class="fas fa-bolt me-2 text-primary"></i>Quick Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-3">
                    <a href="{{ route('articles.create') }}" class="btn btn-primary">
                        <i class="fas fa-pen me-2"></i>Write New Article
                    </a>
                    <a href="{{ route('profile.edit') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-user-edit me-2"></i>Edit Profile
                    </a>
                    <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-globe me-2"></i>View Your Published Articles
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- My Articles List -->
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0">
                    <i class="fas fa-list me-2 text-primary"></i>My Articles
                </h5>
                <span class="badge bg-secondary">{{ $articles->total() }}</span>
            </div>
            <div class="card-body p-0">
                @if($articles->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Title</th>
                                    <th>Category</th>
                                    <th>Status</th>
                                    <th>Published</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($articles as $article)
                                    <tr>
                                        <td>
                                            <a href="{{ route('articles.show', $article->slug) }}" 
                                               class="text-decoration-none text-dark fw-semibold">
                                                {{ Str::limit($article->title, 40) }}
                                            </a>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary bg-opacity-10 text-primary">
                                                {{ $article->category->name }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($article->status === 'published')
                                                <span class="badge bg-success">Published</span>
                                            @else
                                                <span class="badge bg-warning">Draft</span>
                                            @endif
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                {{ $article->published_at ? $article->published_at->format('M d, Y') : 'Not published' }}
                                            </small>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('articles.show', $article->slug) }}" 
                                                   class="btn btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('articles.edit', $article->slug) }}" 
                                                   class="btn btn-outline-secondary">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-outline-danger" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#deleteModal{{ $article->id }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                            
                                            <!-- Delete Modal -->
                                            <div class="modal fade" id="deleteModal{{ $article->id }}" tabindex="-1">
                                                <div class="modal-dialog modal-sm">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h6 class="modal-title">Delete Article?</h6>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p class="small">Are you sure you want to delete "{{ Str::limit($article->title, 30) }}"?</p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                                                            <form action="{{ route('articles.destroy', $article->slug) }}" method="POST">
                                                                @csrf @method('DELETE')
                                                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer bg-white">
                        {{ $articles->links() }}
                    </div>
                @else
                    <div class="text-center text-muted py-5">
                        <i class="fas fa-newspaper fa-3x d-block mb-3 text-muted opacity-50"></i>
                        <p class="mb-0">You haven't written any articles yet.</p>
                        <a href="{{ route('articles.create') }}" class="btn btn-primary mt-3">
                            <i class="fas fa-pen me-2"></i>Write Your First Article
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection