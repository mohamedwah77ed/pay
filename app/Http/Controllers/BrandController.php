<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Brands;
use Illuminate\Support\Str;
class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    $brands = Brands::latest('id')->paginate(10);
    return view('brand.index', compact('brands'));
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('brand.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         $validatedData = $request->validate([
        'title'     => 'required|string',
        'status'    => 'required|in:active,inactive',

    ]);
    $slug = Str::slug($request->title);

$latestSlug = brands::where('slug', 'like', $slug . '%')
    ->latest('id')
    ->value('slug');

if ($latestSlug) {
    preg_match('/-(\d+)$/', $latestSlug, $matches);
    $count = isset($matches[1]) ? (int)$matches[1] + 1 : 1;
    $slug = $slug . '-' . $count;
}

    $validatedData['slug'] = $slug;
    $validatedData['is_parent'] = $request->input('is_parent', 0);

    $category = brands::create($validatedData);

    $message = $category
        ? 'Category successfully added'
        : 'Error occurred, Please try again!';

    return redirect()->route('brand.index')->with(
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
