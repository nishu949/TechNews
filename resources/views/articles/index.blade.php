@extends('layouts.app')

@section('title', 'TechNews')

@section('content')

<section class="container py-4">

    <h1 class="mb-4">Latest Developer Articles</h1>

    @forelse($articles as $article)

        <article class="card mb-4 shadow-sm">

            <div class="card-body">

                <header>

                    <h3>{{ $article->title }}</h3>

                    <time datetime="{{ $article->published_at }}">
                        {{ optional($article->published_at)->format('d M Y') }}
                    </time>

                </header>

                <section class="mt-3">

                    {{ $article->excerpt }}

                </section>

                <aside class="mt-3">

    <div>
        <strong>Category:</strong>

        {{ $article->category->name }}

    </div>


    @if($article->tags->count())

        <div class="mt-2">

            <strong>Tags:</strong>

            @foreach($article->tags as $tag)

                <a href="{{ route('tags.show', $tag->slug) }}"
                   class="badge bg-primary text-decoration-none">

                    {{ $tag->name }}

                </a>

            @endforeach

        </div>

    @endif


</aside>

                <a href="{{ route('articles.show', $article) }}"
                   class="btn btn-outline-primary mt-3">

                    Read More

                </a>

            </div>

        </article>

    @empty

        <div class="alert alert-info">

            No articles published yet.

        </div>

    @endforelse

</section>

@endsection