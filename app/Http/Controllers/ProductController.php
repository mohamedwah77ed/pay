<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with(['cat_info', 'sub_cat_info'])->get();
       return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
         $categories = Category::all();
    return view('products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $validatedData = $request->validate([
        'title' => 'required|string',
        'price' => 'required|numeric',
        'summary' => 'required|string',
        'description' => 'nullable|string',
        'cat_id' => 'required|exists:categories,id',
        'child_cat_id' => 'nullable|exists:categories,id',
        'image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
    ]);

    $validatedData['image'] = null;
    if ($request->hasFile('image')) {
        $imageName = time() . '_' . $request->image->getClientOriginalName();
        $request->image->move(public_path('uploads'), $imageName);
        $validatedData['image'] = $imageName;
    }

    $slug = Str::slug($request->title);
    $original = $slug;
    $count = 1;
    while (Product::where('slug', $slug)->exists()) {
        $slug = $original . '-' . $count;
        $count++;
    }
    $validatedData['slug'] = $slug;

    $product = Product::create($validatedData);

    if ($product) {
        session()->flash('success', 'Product added successfully');
    } else {
        session()->flash('error', 'Something went wrong!');
    }

    return redirect()->route('products.index');
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
