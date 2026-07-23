@extends('layouts.app')

@section('title', 'Edit Article - TechNews')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0" style="border-radius: 12px;">
            <div class="card-header bg-white border-0 pt-4">
                <h2 class="h4 fw-bold mb-0">
                    <i class="fas fa-edit me-2 text-primary"></i>Edit Article
                </h2>
            </div>
            <div class="card-body">
                <form action="{{ route('articles.update', $article->slug) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <!-- Title -->
                    <div class="mb-3">
                        <label for="title" class="form-label fw-semibold">Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" 
                               id="title" name="title" value="{{ old('title', $article->title) }}" 
                               placeholder="Enter your article title" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Category -->
                    <div class="mb-3">
                        <label for="category_id" class="form-label fw-semibold">Category <span class="text-danger">*</span></label>
                        <select class="form-select @error('category_id') is-invalid @enderror" 
                                id="category_id" name="category_id" required>
                            <option value="">Select a category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" 
                                    {{ old('category_id', $article->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Excerpt -->
                    <div class="mb-3">
                        <label for="excerpt" class="form-label fw-semibold">Excerpt</label>
                        <textarea class="form-control @error('excerpt') is-invalid @enderror" 
                                  id="excerpt" name="excerpt" rows="2" 
                                  placeholder="Brief summary of your article (optional)">{{ old('excerpt', $article->excerpt) }}</textarea>
                        <small class="text-muted">A brief summary of your article. Max 500 characters.</small>
                        @error('excerpt')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Content -->
                    <div class="mb-3">
                        <label for="content" class="form-label fw-semibold">Content <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('content') is-invalid @enderror" 
                                  id="content" name="content" rows="12" 
                                  placeholder="Write your article in Markdown..." required>{{ old('content', $article->content) }}</textarea>
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            Supports Markdown formatting. Use ``` for code blocks.
                        </small>
                        @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Tags -->
                    <div class="mb-3">
                        <label for="tags" class="form-label fw-semibold">Tags</label>
                        <select class="form-select @error('tags') is-invalid @enderror" 
                                id="tags" name="tags[]" multiple size="5">
                            @foreach($tags as $tag)
                                <option value="{{ $tag->id }}" 
                                    {{ in_array($tag->id, old('tags', $article->tags->pluck('id')->toArray())) ? 'selected' : '' }}>
                                    {{ $tag->name }}
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Hold Ctrl/Cmd to select multiple tags.</small>
                        @error('tags')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Featured Image -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Featured Image</label>
                        
                        <!-- Current Image Preview -->
                        @if($article->featured_image)
                            <div class="mb-2 p-2 border rounded" style="background: #f8f9fa;">
                                <div class="d-flex align-items-center gap-3">
                                    <img src="{{ asset('storage/' . $article->featured_image) }}" 
                                         alt="Current featured image" 
                                         class="img-thumbnail" 
                                         style="max-height: 100px; width: auto; border-radius: 8px;">
                                    <div>
                                        <span class="badge bg-success">Current</span>
                                        <div class="small text-muted mt-1">Current featured image</div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="alert alert-light mb-2">
                                <i class="fas fa-image me-2 text-muted"></i>
                                <span class="text-muted small">No featured image uploaded.</span>
                            </div>
                        @endif
                        
                        <!-- File Input -->
                        <input type="file" class="form-control @error('featured_image') is-invalid @enderror" 
                               id="featured_image" name="featured_image" accept="image/*">
                        <small class="text-muted">Upload a new image to replace the current one. Max 2MB.</small>
                        @error('featured_image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Submit Buttons -->
                    <div class="d-flex gap-2 mt-4 pt-3 border-top">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update Article
                        </button>
                        <a href="{{ route('articles.show', $article->slug) }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Markdown Preview (Optional) -->
        <div class="card shadow-sm border-0 mt-4" style="border-radius: 12px;">
            <div class="card-header bg-white border-0 pt-4">
                <h3 class="h6 fw-bold mb-0">
                    <i class="fas fa-eye me-2 text-primary"></i>Markdown Preview
                </h3>
            </div>
            <div class="card-body">
                <p class="text-muted small">
                    <i class="fas fa-info-circle me-1"></i>
                    Preview your article formatting before updating.
                </p>
                <div id="preview" class="article-content small" style="max-height: 300px; overflow-y: auto; padding: 1rem; background: #f8f9fa; border-radius: 8px;">
                    <span class="text-muted">Start typing to see preview...</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .article-content code {
        background: #e9ecef;
        padding: 0.1rem 0.3rem;
        border-radius: 3px;
        font-size: 0.85rem;
    }
    .article-content pre {
        background: #1a202c;
        color: #f7fafc;
        padding: 1rem;
        border-radius: 4px;
        overflow-x: auto;
    }
    .article-content pre code {
        background: transparent;
        color: #e2e8f0;
        padding: 0;
    }
</style>
@endpush

@push('scripts')
<script>
    // Simple Markdown Preview
    document.getElementById('content').addEventListener('input', function() {
        const preview = document.getElementById('preview');
        const text = this.value;
        
        if (!text.trim()) {
            preview.innerHTML = '<span class="text-muted">Start typing to see preview...</span>';
            return;
        }
        
        // Very basic markdown preview
        let html = text
            .replace(/^### (.*$)/gm, '<h3>$1</h3>')
            .replace(/^## (.*$)/gm, '<h2>$1</h2>')
            .replace(/^# (.*$)/gm, '<h1>$1</h1>')
            .replace(/\*\*(.*)\*\*/gm, '<strong>$1</strong>')
            .replace(/\*(.*)\*/gm, '<em>$1</em>')
            .replace(/`([^`]+)`/gm, '<code>$1</code>')
            .replace(/```([\s\S]*?)```/gm, '<pre><code>$1</code></pre>')
            .replace(/\n\n/g, '<br><br>')
            .replace(/\n/g, '<br>');
        
        preview.innerHTML = html;
    });
</script>
@endpush