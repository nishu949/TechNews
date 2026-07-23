<?php

namespace App\Http\Controllers;

use App\Models\Tag;

class TagController extends Controller
{

    public function index()
    {
        $tags = Tag::withCount('articles')->get();

        return view('tags.index', compact('tags'));
    }


    public function show($slug)
    {

        $tag = Tag::where('slug', $slug)
            ->firstOrFail();


        $articles = $tag->articles()
            ->with([
                'author',
                'category',
                'tags'
            ])
            ->where('status', 'published')
            ->latest()
            ->paginate(6);


        return view('tags.show', compact(
            'tag',
            'articles'
        ));
    }

}