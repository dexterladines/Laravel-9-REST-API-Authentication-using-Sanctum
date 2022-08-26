<?php

namespace App\Http\Controllers\API;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Resources\ProductResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Validator as FacadesValidator;
use App\Http\Controllers\API\BaseController as BaseController;

class ProductController extends BaseController
{
    public function index()
    {
        $products = Product::all();

        return $this->sendResponse(ProductResource::collection($products), 'Products retrieved successfully.');
    }

    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required',
            'detail' => 'required'
        ]);

        if($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $product = Product::create($input);

        return $this->sendResponse(new ProductResource($product), 'Product created succesfully.');
    }

    public function show($id)
    {
        $product = Product::find($id);

        if (is_null($product)) {
            return $this->sendError('Product not found.');
        }

        return $this->sendResponse(new ProductResource($product), 'Product retrieved succesfully.');
    }

    public function update(Request $request, Product $product)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required',
            'detail' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $product->name = $input['name'];
        $product->detail = $input['detail'];
        $product->save();

        return $this->sendResponse(new ProductResource($product), 'Product updated succesfully.');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return $this->sendResponse([], 'Product deleted successfully.');
    }
}
