<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Models\Images;
use App\Models\Discounts;
use App\Models\Cates;
use App\Models\Products;
use Illuminate\Support\Facades\DB;
use App\Models\Details;
use App\Models\ProductVariants;
use App\Models\Sizes;
use App\Models\Colors;
use App\Models\UserInfor;
use App\Models\User;
use App\Models\LikedProducts;
use Exception;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Session;



class RecentlyProductCrontroller extends Controller
{
    //
    public function addToRecently(Request $request)
    {

        try {
            $product = Products::find($request->productId);
            $recentlyViewedKey = $this->getRecentlyViewedKey();
            $recentlyViewed = Redis::lrange($recentlyViewedKey, 0, 120);
            if (!in_array($product->id, $recentlyViewed)) {
                Redis::lpush($recentlyViewedKey, $product->id);
                Redis::ltrim($recentlyViewedKey, 0, 120);
                Redis::expire($recentlyViewedKey, 15 * 24 * 60 * 60);
            }

            return response()->json([
                'success' => true,
                'message' => 'Recently Added!',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => [$e->getMessage()],
            ]);
        }
    }
    protected function getRecentlyViewed()
    {
        $recentlyViewedKey = $this->getRecentlyViewedKey();
        $recentlyViewedIds = Redis::lrange($recentlyViewedKey, 0, 120);
        return Products::whereIn('id', $recentlyViewedIds)->get();
    }
    private function getRecentlyViewedKey()
    {
        if (Auth::check()) {
            return  Auth::id();
        }

        return Session::getId();
    }
    ///
    public function getRecentlyProducts()
    {
        $data = $this->getRecentlyViewed();
        $recentlyProducts = Products::select(
            'products.id',
            'products.name',
            'products.description',
            'products.price',
            'products.user_id',
            DB::raw('SUM(product_variant.quantity) as total_quantity'),
            'discounts.quantity as discount_quantity',
            'discounts.discount',
            'discounts.status',
            'discounts.id as discount_id',
            DB::raw('(SELECT image FROM product_image WHERE product_id = products.id ORDER BY id LIMIT 1) as image')
        )
            ->leftJoin('product_variant', 'products.id', '=', 'product_variant.product_id')
            ->leftJoin('discounts', 'product_variant.discount_id', '=', 'discounts.id')
            ->groupBy(
                'products.id',
                'products.name',
                'products.description',
                'products.price',
                'products.user_id',
                'discounts.quantity',
                'discounts.discount',
                'discounts.status',
                'discounts.id'
            )
            ->get();
        $recentlyViewedIds = $data->pluck('id')->toArray();
        $filteredProducts = $recentlyProducts->filter(function ($product) use ($recentlyViewedIds) {
            return in_array($product->id, $recentlyViewedIds);
        })->take(11)->values();


        return response()->json([
            'success' => true,
            'data' => $filteredProducts->toArray(),
            'dataa' => $data
        ]);
    }
}
