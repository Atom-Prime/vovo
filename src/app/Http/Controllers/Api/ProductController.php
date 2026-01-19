<?php

namespace App\Http\Controllers\Api;

use App\Filters\ProductFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class ProductController extends Controller
{
    public function index(Request $request, ProductFilter $filter): JsonResponse
    {
        $limit = $request->limit ?? 10;

        $products = $filter
            ->apply(Product::query())
            ->paginate($limit);

        return response()->json($products);
    }
    public function store(StoreProductRequest $request): JsonResponse
    {
        return response()->json(Product::create($request->validated()), 201);
    }

    public function show(int $id): JsonResponse
    {
        $product = Product::find($id);

        if (!empty($product)) {
            return response()->json([
                'result' => 'success',
                'data' => $product
            ]);
        }

        return response()->json([
            'result' => false,
            'message' => 'Not found'
        ], 404);
    }
}
