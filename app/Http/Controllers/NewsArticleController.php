<?php

namespace App\Http\Controllers;

use App\Models\NewsArticle;
use Illuminate\Http\Request;

class NewsArticleController extends Controller
{
    // ========== PUBLIC METHODS ==========
    
    public function index(Request $request)
    {
        $query = NewsArticle::query();

        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        $query->where('is_active', true);

        $news = $query->orderBy('published_at', 'desc')
            ->paginate(12);

        $featuredNews = NewsArticle::where('is_active', true)
            ->where('is_featured', true)
            ->latest('published_at')
            ->take(3)
            ->get();

        $latestNews = NewsArticle::where('is_active', true)
            ->latest('published_at')
            ->take(5)
            ->get();

        $categories = NewsArticle::where('is_active', true)
            ->select('category')
            ->distinct()
            ->pluck('category');

        return view('news.index', compact('news', 'featuredNews', 'categories', 'latestNews'));
    }

    public function show($id)
    {
        $article = NewsArticle::where('is_active', true)->findOrFail($id);

        $article->increment('views_count');

        $relatedNews = NewsArticle::where('is_active', true)
            ->where('category', $article->category)
            ->where('id', '!=', $article->id)
            ->latest('published_at')
            ->take(4)
            ->get();

        return view('news.show', compact('article', 'relatedNews'));
    }

    public function incrementViews($id)
    {
        try {
            $article = NewsArticle::findOrFail($id);
            $article->increment('views_count');
            
            return response()->json([
                'success' => true,
                'views_count' => $article->views_count
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to increment views'
            ], 500);
        }
    }
    
    // ========== ADMIN METHODS ==========
    
    public function adminIndex(Request $request)
{
    $query = NewsArticle::query();
    
    if ($request->has('status')) {
        $query->where('is_active', $request->status === 'active');
    }
    
    if ($request->has('category')) {
        $query->where('category', $request->category);
    }
    
    $articles = $query->latest('published_at')->paginate(20);
    
    $categories = NewsArticle::distinct()->pluck('category');
    
    // Hitung stats
    $totalArticles = NewsArticle::count();
    $activeArticles = NewsArticle::where('is_active', true)->count();
    $featuredArticles = NewsArticle::where('is_featured', true)->count();
    $totalViews = NewsArticle::sum('views_count');
    
    return view('admin.news.index', compact(
        'articles', 
        'categories', 
        'totalArticles', 
        'activeArticles', 
        'featuredArticles', 
        'totalViews'
    ));
}
    
    // TAMBAHKAN METHOD CREATE INI
    public function create()
    {
        return view('admin.news.create');
    }
    
    // TAMBAHKAN METHOD STORE INI
    public function store(Request $request)
{
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'category' => 'required|string|max:50',
        'author' => 'nullable|string|max:100',
        'excerpt' => 'nullable|string|max:500',
        'content' => 'required|string',
        'source' => 'nullable|string|max:100',
        'source_url' => 'nullable|url|max:255',
        'published_at' => 'required|date',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'image' => 'nullable|image|max:2048',
    ]);
    
    // Debug: Cek apakah file ada
    if ($request->hasFile('image')) {
        $file = $request->file('image');
        \Log::info('File details:', [
            'name' => $file->getClientOriginalName(),
            'size' => $file->getSize(),
            'mime' => $file->getMimeType(),
            'valid' => $file->isValid()
        ]);
        
        $imagePath = $file->store('news-images', 'public');
        \Log::info('Image stored at: ' . $imagePath);
        
        $validated['image_url'] = '/storage/' . $imagePath;
    } else {
        \Log::info('No image file in request');
    }
    
    \Log::info('Data to create:', $validated);
    
    $article = NewsArticle::create($validated);
    
    \Log::info('Article created:', $article->toArray());
    
    return redirect()->route('admin.news.index')
        ->with('success', 'Article created successfully!');
}
    
    // TAMBAHKAN METHOD EDIT INI
    public function edit($id)
    {
        $article = NewsArticle::findOrFail($id);
        return view('admin.news.edit', compact('article'));
    }
    
    // TAMBAHKAN METHOD UPDATE INI
    public function update(Request $request, $id)
    {
        $article = NewsArticle::findOrFail($id);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:50',
            'author' => 'nullable|string|max:100',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'source' => 'nullable|string|max:100',
            'source_url' => 'nullable|url|max:255',
            'published_at' => 'required|date',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'image' => 'nullable|image|max:2048',
            'remove_image' => 'boolean',
        ]);
        
        if ($request->has('remove_image') && $request->remove_image) {
            if ($article->image_url && file_exists(public_path($article->image_url))) {
                unlink(public_path($article->image_url));
            }
            $validated['image_url'] = null;
        }
        
        if ($request->hasFile('image')) {
            if ($article->image_url && file_exists(public_path($article->image_url))) {
                unlink(public_path($article->image_url));
            }
            
            $imagePath = $request->file('image')->store('news-images', 'public');
            $validated['image_url'] = '/storage/' . $imagePath;
        }
        
        $article->update($validated);
        
        return redirect()->route('admin.news.index')
            ->with('success', 'Article updated successfully!');
    }
    
    // TAMBAHKAN METHOD DESTROY INI
    public function destroy($id)
    {
        $article = NewsArticle::findOrFail($id);
        
        if ($article->image_url && file_exists(public_path($article->image_url))) {
            unlink(public_path($article->image_url));
        }
        
        $article->delete();
        
        return redirect()->route('admin.news.index')
            ->with('success', 'Article deleted successfully!');
    }
}