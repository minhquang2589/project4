<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Products;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    //
    public function getProductHome(Request $request)
    {
        
        $page = $request->input('page');
        $perPage = 36;
       
        $query = Products::select(
            'products.id',
            'products.name',
            'products.description',
            'products.price',
            DB::raw('CAST(SUM(product_variant.quantity) AS UNSIGNED) as total_quantity'),
            'discounts.quantity as discount_quantity',
            'discounts.discount',
            'discounts.status',
            DB::raw('(SELECT image FROM product_image WHERE product_id = products.id ORDER BY id LIMIT 1) as image1'),
            DB::raw('(SELECT image FROM product_image WHERE product_id = products.id ORDER BY id LIMIT 1 OFFSET 1) as image2')
        )
            ->leftJoin('product_variant', 'products.id', '=', 'product_variant.product_id')
            ->leftJoin('discounts', 'product_variant.discount_id', '=', 'discounts.id')
            ->groupBy(
                'products.id',
                'products.name',
                'products.description',
                'products.price',
                'discounts.quantity',
                'discounts.discount',
                'discounts.status'
            );

        $query->orderBy('products.created_at', 'desc');
        $productViews = $query->skip(($page - 1) * $perPage)
            ->take($perPage)
            ->get();
        $hasMore = $productViews->count() >= $perPage;
        return response()->json([
            'success' => true,
            'productViews' => $productViews,
            'hasMore' => $hasMore,
            'page' => $page,
        ]);
    }
}
