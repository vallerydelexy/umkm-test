<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Enterprise;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProductController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index() {
        $products = Product::all();
        return response()->json(['data' => $products], Response::HTTP_OK);
    }

    public function indexByEnterprise($enterprise_id) {
        $products = Enterprise::find($enterprise_id)->products;
        return response()->json(['data' => $products], Response::HTTP_OK);
    }
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        try {
            $request->validate([
                'sku' => 'required',
                'name' => 'required',
                'description' => 'required',
                'price' => 'required|numeric',
                'image' => 'required|array',
            ]);

            $product = Product::create($request->all());

            return response()->json(['data' => $product], Response::HTTP_CREATED);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $id) {
        try {
            $product = Product::findOrFail($id);

            return response()->json(['data' => $product], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(['error' => 'Product not found'], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product) {
        try {
            $product = Product::findOrFail($id);

            $request->validate([
                'sku' => 'required',
                'name' => 'required',
                'description' => 'required',
                'price' => 'required|numeric',
                'image' => 'required|array',
            ]);

            $product->update($request->all());

            return response()->json(['data' => $product], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(['error' => 'Product not found or something went wrong. Please try again.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product) {
        try {
            $product = Product::findOrFail($id);
            $product->delete();

            return response()->json(['message' => 'Product deleted successfully'], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(['error' => 'Product not found or something went wrong. Please try again.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
