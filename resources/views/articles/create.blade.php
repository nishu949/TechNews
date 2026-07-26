@extends('layouts.app')

@section('title', 'Create Article')

@section('content')

<style>
    body {
        background-color: #121212;
    }

    .form-control,
    .form-select {
        background-color: #1f1f1f;
        color: white;
        border: 1px solid #444;
    }

    .form-control::placeholder {
        color: #aaa;
    }

    .form-control:focus,
    .form-select:focus {
        background-color: #1f1f1f;
        color: white;
        border-color: #0d6efd;
        box-shadow: none;
    }

    option {
        background-color: #1f1f1f;
        color: white;
    }

    label,
    h2,
    small {
        color: white !important;
    }

</style>


<div class="container py-4">

    <div class="card bg-dark shadow-lg border-secondary">

        <div class="card-body">

            <h2 class="mb-4 text-white">
                Publish New Article
            </h2>


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

                    <label class="form-label">
                        Article Title
                    </label>


                    <input type="text"
                           name="title"
                           class="form-control"
                           value="{{ old('title') }}"
                           placeholder="Enter article title"
                           required>

                </div>



                <div class="mb-3">

                    <label class="form-label">
                        Category
                    </label>


                    <select name="category_id"
                            class="form-select"
                            required>


                        <option value="">
                            Choose Category
                        </option>


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


                    <small>
                        Hold CTRL to select multiple tags
                    </small>


                </div>




                <div class="mb-3">

                    <label class="form-label">
                        Excerpt
                    </label>


                    <textarea
                        name="excerpt"
                        rows="3"
                        class="form-control"
                        placeholder="Write short description">{{ old('excerpt') }}</textarea>


                </div>




                <div class="mb-3">

                    <label class="form-label">
                        Article Content
                    </label>


                    <textarea
                        name="content"
                        rows="12"
                        class="form-control"
                        placeholder="Write article content"
                        required>{{ old('content') }}</textarea>


                </div>




                <div class="mb-4">

                    <label class="form-label">
                        Featured Image
                    </label>


                    <input
                        type="file"
                        name="featured_image"
                        class="form-control">


                </div>



                <button class="btn btn-primary px-4">

                    Publish Article

                </button>



            </form>


        </div>

    </div>


</div>

@endsection