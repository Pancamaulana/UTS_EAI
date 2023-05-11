<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return response()->json($products);
    }

    public function name($name){
        $filterData = DB::table('products')->where('name','LIKE','%'.$name.'%')
            ->get();

        return response()->json($filterData);
    }

    public function show($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }
        return response()->json($product);
    }

    public function store(Request $request)
    {
       $product = new Product();
       $product->name = $request->name;
       $product->stock = $request->stock;
       $product->price = $request->price;
       $product->save();

        return response()->json($product, 201);
    }

    public function update(Request $request, $id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $validatedData = $request->validate([
            'name' => 'required',
            'stock' => 'required|numeric',
            'price' => 'required|numeric',
        ]);

        $product->name = $validatedData['name'];
        $product->stock = $validatedData['stock'];
        $product->price = $validatedData['price'];
        $product->save();

        return response()->json($product);
    }

    public function destroy($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $product->delete();

        return response()->json(['message' => 'Product deleted']);
    }

    public function filterByStock(Request $request)
    {
        $minStock = $request->query('min_stock');
        $products = Product::where('stock', '>=', $minStock)->get();

        return response()->json($products);
    }

}
