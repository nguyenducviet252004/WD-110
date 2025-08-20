<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $categories = Category::where('is_active', 1)->get();
            return response()->json($categories);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Không thể lấy danh sách categories.'], 500);
        }
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        try {
            return response()->json($category);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Không thể lấy thông tin category: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
    }