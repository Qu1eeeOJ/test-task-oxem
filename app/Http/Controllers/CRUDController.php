<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Product;
use App\CategoriesAndProducts as CatAndProd;
use App\Category;

class CRUDController extends Controller
{
    public function getListProduct(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'price' => 'string|max:4',
            'created' => 'string|max:4',
            'page' => 'integer'
        ]);

        if ($validator->fails()) {
            return $this->eApi($validator);
        }

        $query = CatAndProd::join('products', function ($join) {
            $join->on('categories_and_products.product_id', '=', 'products.id');
        })
        ->select('products.*', 'categories_and_products.category_id');

        if ($request->input('price', 0)) {
            $query = $query->orderBy('price', $request->input('price'));
        }

        if ($request->input('created', 0)) {
            $query = $query->orderBy('created_at', $request->input('created'));
        }

        if ($request->input('page', 1) == 1) {
            $query = $query->take(50)->get();
        } else {
            $query = $query->skip(50 * ($request->input('page') - 1))->take(50)->get();
        }

        return $this->sApi($query);
    }

    public function getListProductInCategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return $this->eApi($validator);
        }

        $query = CatAndProd::where('category_id', $request->category_id)
        ->join('products', function ($join) {
            $join->on('categories_and_products.product_id', '=', 'products.id');
        })
        ->select('products.*', 'categories_and_products.category_id')
        ->get();

        return $this->sApi($query);
    }

    public function getProduct(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return $this->eApi($validator);
        }

        $query = CatAndProd::where('product_id', $request->product_id)
        ->join('products', function ($join) use ($request) {
            $join->where('products.id', $request->product_id);
        })
        ->select('products.*', 'categories_and_products.category_id')
        ->get();

        return $this->sApi($query);
    }

    public function createProduct(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:200',
            'description' => 'max:1000',
            'price' => 'required',
            'quantity' => 'required|integer',
            'category_ids' => 'required|array',
            'external_id' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return $this->eApi($validator);
        }

        $model = new Product;
        $model->name = $request->name;
        $model->description = $request->description;
        $model->price = $request->price;
        $model->quantity = $request->quantity;
        $model->external_id = $request->external_id;
        $model->save();

        foreach ($request->category_ids as $category_id) {
            $model = new CatAndProd;
            $model->category_id = $category_id;
            $model->product_id = $model->id;
            $model->save();
        }

        return $this->sApi(['id' => $model->id]);
    }

    public function deleteProduct(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return $this->eApi($validator);
        }

        try {
            Product::where('id', $request->product_id)->delete();
        } catch (\Exception $e) {
            return $this->eApi('No such product was found');
        }

        return $this->sApi();
    }

    public function getCategories()
    {
        $query = Category::all();

        return $this->sApi($query);
    }

    public function addCategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:200',
            'parent_id' => 'integer',
            'external_id' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return $this->eApi($validator);
        }

        $model = new Category;
        $model->name = $request->name;
        $model->parent_id = $request->parent_id;
        $model->external_id = $request->external_id;
        $model->save();

        return $this->sApi(['id' => $model->id]);
    }

    public function editCategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:200',
            'category_id' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return $this->eApi($validator);
        }

        try {
            Category::where('id', $request->category_id)->update(['name' => $request->name]);
        } catch (\Exception $e) {
            return $this->eApi('No such category was found');
        }

        return $this->sApi();
    }

    public function deleteCategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return $this->eApi($validator);
        }

        try {
            Category::where('id', $request->category_id)->delete();
        } catch (\Exception $e) {
            return $this->eApi('No such category was found');
        }

        return $this->sApi();
    }
}
