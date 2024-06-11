<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

use App\Models\Article;
use App\Models\Image;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): \Inertia\Response
    {
        $articles = Article::all();

        $images = [];
        
        foreach ($articles as $article) {

            $image = Image::where('articles_id', $article->id)->get();
            array_push($images, $image);
        }

        return Inertia::render('Index', compact('articles', 'images'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'image_path' => 'nullable|image|mimes:jpeg,jpg,png,gif,svg,webp|max:2048',
            'category' => 'required',
            'status' => 'nullable',
        ]);

        if ($request->hasFile('image_path')) {
            $path = Storage::disk('public')->put('image', $request->file('image_path'));
            $validatedData['image_path'] = '/storage/' . $path;
        } 

    }

    /**
     * Display the specified resource.
     */
    public function show(Article $article)
    {
        $images = Image::where('articles_id', $article->id)->get();

        return Inertia::render('Show', compact('article', 'images'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Article $article)
    {
        $images = Image::where('articles_id', $article->id)->get();

        return Inertia::render('Edit', compact('article', 'images'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Article $article)
    {
        // $article->update($request->all());

        $validatedData = $request->validate($this->rules());
        $article->update($validatedData);

        return redirect()->route('articles.show', $article);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        $article->delete();
        
        return redirect()->route('articles.index');
    }
}
