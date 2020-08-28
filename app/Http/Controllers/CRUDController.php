<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;

class CRUDController extends Controller
{
    public function getListProduct(Request $request)
    {
        $query = Product::select('name', 'description', 'price', 'quantity', 'category_id', 'external_id', 'created_at', 'updated_at');

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

        return $this->aApi($query);
    }
}
