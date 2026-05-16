<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Str; // ← ده المهم

class CategoryController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       $categories = Category::all();
       return view('categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
          return view('categories.create');
     }

    /**
     * Store a newly created resource in storage.
     */

public function store(Request $request)
{
    $validatedData = $request->validate([
        'title'     => 'required|string',
        'summary'   => 'nullable|string',
        'photo'     => 'nullable|string',
        'status'    => 'required|in:active,inactive',
        'is_parent' => 'nullable|boolean',
        'parent_id' => 'nullable|exists:categories,id',
    ]);

   
    $slug = Str::slug($request->title);

$latestSlug = Category::where('slug', 'like', $slug . '%')
    ->latest('id')
    ->value('slug');

if ($latestSlug) {
    preg_match('/-(\d+)$/', $latestSlug, $matches);
    $count = isset($matches[1]) ? (int)$matches[1] + 1 : 1;
    $slug = $slug . '-' . $count;
}

    $validatedData['slug'] = $slug;
    $validatedData['is_parent'] = $request->input('is_parent', 0);

    $category = Category::create($validatedData);

    $message = $category
        ? 'Category successfully added'
        : 'Error occurred, Please try again!';

    return redirect()->route('categories.index')->with(
        $category ? 'success' : 'error',
        $message
    );
}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $category = Category::findOrFail($id);
        return view('categories.edit', compact('category'));    
    
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
 {
    $category = Category::findOrFail($id);

    $validatedData = $request->validate([
        'title'     => 'required|string',
        'summary'   => 'nullable|string',
        'photo'     => 'nullable|string',
        'status'    => 'required|in:active,inactive',
        'is_parent' => 'sometimes|in:1',
        'parent_id' => 'nullable|exists:categories,id',
    ]);

    // ✅ لو الـ title اتغير بس يعمل slug جديد
    if ($category->title !== $request->title) {
        $slug = Str::slug($request->title);
        $original = $slug;
        $count = 1;

        while (Category::where('slug', $slug)->where('id', '!=', $id)->exists()) {
            $slug = $original . '-' . $count;
            $count++;
        }

        $validatedData['slug'] = $slug;
    }

    $validatedData['is_parent'] = $request->input('is_parent', 0);

    $status = $category->update($validatedData);

    $message = $status
        ? 'Category successfully updated'
        : 'Error occurred, Please try again!';

    return redirect()->route('categories.index')->with(
        $status ? 'success' : 'error',
        $message
    );
     }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
         $category = Category::findOrFail($id);
        $child_cat_id = Category::where('parent_id', $id)->pluck('id');

        $status = $category->delete();

        if ($status && $child_cat_id->count() > 0) {
            Category::shiftChild($child_cat_id);
        }

        $message = $status
            ? 'Category successfully deleted'
            : 'Error while deleting category';

        return redirect()->route('categories.index')->with(
            $status ? 'success' : 'error',
            $message
        );
    }
}
