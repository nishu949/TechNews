@extends('layouts.app')

@section('title', 'Tags - TechNews')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="display-6 fw-bold">
                <i class="fas fa-tags me-2 text-primary"></i>Tags
            </h1>
            <span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2">
                {{ $tags->count() }} tags
            </span>
        </div>
        
        <div class="d-flex flex-wrap gap-3">
            @forelse($tags as $tag)
                <a href="{{ route('tags.show', $tag->slug) }}" 
                   class="tag-pill bg-light text-secondary text-decoration-none px-4 py-3 shadow-sm"
                   style="font-size: clamp(0.85rem, 1.5vw, 1.1rem);">
                    #{{ $tag->name }}
                    <span class="badge bg-secondary bg-opacity-10 text-secondary ms-2">
                        {{ $tag->articles_count }}
                    </span>
                </a>
            @empty
                <div class="col-12">
                    <div class="alert alert-info text-center py-5 w-100">
                        <i class="fas fa-tags fa-3x d-block mb-3 text-muted"></i>
                        <h4>No tags yet</h4>
                        <p class="mb-0">Tags will appear here as articles are published.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .tag-pill {
        border-radius: 9999px;
        transition: all 0.2s;
        font-weight: 600;
        border: 1px solid #e2e8f0;
    }
    .tag-pill:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.15);
        background: #2d3748 !important;
        color: white !important;
        border-color: #2d3748;
    }
    .tag-pill:hover .badge {
        background: white !important;
        color: #2d3748 !important;
    }
</style>
@endpush