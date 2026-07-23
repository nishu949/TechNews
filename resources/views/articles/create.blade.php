@extends('layouts.app')

@section('title', 'Create Article')

@section('content')

<div class="container py-4">

    <h2 class="mb-4">Publish New Article</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('articles.store') }}"
          method="POST"
          enctype="multipart/form-data">

        @csrf

        <div class="mb-3">
            <label class="form-label">Article Title</label>

            <input type="text"
                   name="title"
                   class="form-control"
                   value="{{ old('title') }}"
                   required>
        </div>

        <div class="mb-3">
            <label class="form-label">Category</label>

            <select name="category_id"
                    class="form-select"
                    required>

                <option value="">Choose Category</option>

                @foreach($categories as $category)

                    <option value="{{ $category->id }}">
                        {{ $category->name }}
                    </option>

                @endforeach

            </select>
        </div>

        <div class="mb-3">

    <label class="form-label">
        Tags
    </label>


    <select name="tags[]"
            class="form-select"
            multiple>


        @foreach($tags as $tag)

           <option value="{{ $tag->id }}"
    {{ in_array($tag->id, old('tags', [])) ? 'selected' : '' }}>

    {{ $tag->name }}

</option>

        @endforeach


    </select>

    <small class="text-muted">
        Hold CTRL to select multiple tags
    </small>

</div>

        <div class="mb-3">

            <label class="form-label">Excerpt</label>

            <textarea
                name="excerpt"
                rows="3"
                class="form-control">{{ old('excerpt') }}</textarea>

        </div>

        <div class="mb-3">

            <label class="form-label">Article Content</label>

            <textarea
                name="content"
                rows="12"
                class="form-control"
                required>{{ old('content') }}</textarea>

        </div>

        <div class="mb-4">

            <label class="form-label">Featured Image</label>

            <input
                type="file"
                name="featured_image"
                class="form-control">

        </div>

        <button class="btn btn-primary">

            Publish Article

        </button>

    </form>

</div>

@endsection