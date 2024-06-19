<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Products;
use App\Models\Images;
use App\Models\User;
use App\Models\ProductVariants;
use App\Models\Discounts;
use App\Models\Rates;
use App\Models\Follows;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{
    //
    public function userData($id)
    {
        $userId = DB::table('products')
            ->where('id', $id)
            ->value('user_id');
        $user = User::select(
            'Users.id',
            'Users.name',
            'Users.image',
            'Users.descriptions',
        )->find($userId);
        $follow = Follows::where('follow_user_id', $user->id)->count();
        $totalFollowing = DB::table('follows')
            ->where('user_id', $user->id)
            ->count();
        $averageRating = Rates::where('rate_user_id', $user->id)->avg('rating');
        $totalRates = Rates::where('rate_user_id', $user->id)->count();

        return response()->json([
            'success' => true,
            'data' => $user,
            'follow' => $follow,
            'totalFollowing' => $totalFollowing,
            'totalRates' => $totalRates,
        ]);
    }
    //
    public function userProfile($id)
    {

        $user = User::find($id);
        $follow = Follows::where('follow_user_id', $id)->count();
        $totalFollowing = DB::table('follows')
            ->where('user_id', $id)
            ->count();
        $averageRating = Rates::where('rate_user_id', $id)->avg('rating');
        $totalRates = Rates::where('rate_user_id', $id)->count();
        $countProduct = Products::where('user_id', $id)->count();
        return response()->json([
            'success' => true,
            'data' => $user,
            'follow' => $follow,
            'totalFollowing' => $totalFollowing,
            'totalRates' => $totalRates,
            'countProduct' => $countProduct,
            'id' => $id,
        ]);
    }
    ///
    public function userProducts(Request $request)
    {
        $page = $request->input('page');
        $perPage = 36;
        $query = Products::select(
            'products.id',
            'products.name',
            'products.description',
            'products.price',
            'products.user_id',
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
                'discounts.status',
                'products.user_id',

            );

        $query->orderBy('products.created_at', 'desc');
        $query->where('user_id', $request->id);
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
    ///
    public function userPosts($page, $id)
    {
        $perPage = 15;
        $postProducts = Products::select(
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
            ->orderBy('products.created_at', 'desc')
            ->where('products.user_id', $id)
            ->skip(($page - 1) * $perPage)
            ->take($perPage)
            ->get();
        $hasMore = $postProducts->count() > 0;
        return response()->json([
            'success' => true,
            'postProducts' => $postProducts,
            'hasMore' => $hasMore,
        ]);
    }

    //
    public function accountPosts($id)
    {
        $postProducts = Products::select(
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
            ->where('products.user_id', $id)
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
            ->orderBy('products.created_at', 'desc')
            ->take(3)
            ->get();

        return response()->json([
            'success' => true,
            'postProducts' => $postProducts
        ]);
    }
}
