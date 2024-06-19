<?php

namespace App\Http\Controllers;

use App\Models\LikedProducts;
use App\Models\Products;
use App\Models\Sliders;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Session;

class SliderController extends Controller
{
    //
    public function slider()
    {
        $slider = Sliders::where('status', 1)->get();
        return response()->json([
            'success' => true,
            'slider' => $slider
        ]);
    }
    ///
    public function HandleUpload(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'link_url' => 'required|string',
            'status' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => $validator->errors()->all(),
            ]);
        }
        try {
            DB::beginTransaction();
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $fileName = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('images'), $fileName);
            } else {
                return response()->json([
                    'success' => true,
                    'error' => ['Please choose an image to upload.']
                ]);
            }
            $slider = new Sliders();
            $slider->name = $request->input('name');
            $slider->link_url = $request->input('link_url');
            $slider->image = $fileName;
            $slider->status = $request->status;
            $slider->save();
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => ['slider upload successfully!']
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'error' => [$e->getMessage()]
            ]);
        }
    }
    ////
    public function sliderEditView()
    {
        $slider = Sliders::all();
        if ($slider) {
            return response()->json([
                'success' => true,
                'dataSlider' => $slider
            ]);
        }
    }

    ////
    public function removeSlider($id)
    {
        try {
            DB::beginTransaction();
            $slider = Sliders::find($id);
            if (!$slider) {
                return response()->json([
                    'success' => false,
                    'error' => ['Slider not found.']
                ]);
            }
            $slider->delete();
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => ['Slider deleted successfully.']
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'error' => ['Failed to delete Slider.']
            ]);
        }
    }
    ///
    public function editSliderView($id)
    {
        $sliderUpdate = Sliders::findOrFail($id);
        return response()->json([
            'success' => true,
            'dataSlider' => $sliderUpdate
        ]);
    }
    ////
    public function handleUpdate(Request $request)
    {


        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'link_url' => 'required|string',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => $validator->errors()->all(),
            ]);
        }
        try {
            DB::beginTransaction();
            $editViewSlider = Sliders::find($request->sliderId);
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $fileName = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('images'), $fileName);
                $editViewSlider->image = $fileName;
            }
            $editViewSlider->name = $request->name;
            $editViewSlider->status = $request->status;
            $editViewSlider->link_url = $request->link_url;
            $editViewSlider->save();
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => ['Update Slider successfully!']
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'error' => ['Failed to updated Slider.']
            ]);
        }
    }
    ///
    public function getSliderSale()
    {
        $discountProduct = Products::select(
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
            ->where('discounts.quantity', '>', 0)
            ->where('discounts.status', 'Active')
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
            ->inRandomOrder()
            ->take(11)
            ->get();
        return response()->json([
            'success' => true,
            'sliderSale' =>  $discountProduct,
        ]);
    }
    ///
    public function getRecentlyProducts(Request $request)
    {
        try {
            if (Auth::check()) {
                $recentlyViewedKey =  Auth::id();
            } else {
                $recentlyViewedKey =  Session::getId();
            }
            $recentlyViewedIds = Redis::lrange($recentlyViewedKey, 0, 120);
            $products =  Products::whereIn('id', $recentlyViewedIds)->get();

            $page = $request->input('page');
            $perPage = 36;
            $recentlyProducts = Products::select(
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
                    'products.user_id',
                    'discounts.quantity',
                    'discounts.discount',
                    'discounts.status',
                    'discounts.id'
                )
                ->get();
            $recentlyViewedIds = $products->pluck('id')->toArray();
            $filteredProducts = $recentlyProducts->filter(function ($recentlyProducts) use ($recentlyViewedIds) {
                return in_array($recentlyProducts->id, $recentlyViewedIds);
            })->take($perPage)->values();
            $hasMore = $filteredProducts->count() >= $perPage;
            return response()->json([
                'success' => true,
                'data' => $filteredProducts->toArray(),
                'hasMore' => $hasMore,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => [$e->getMessage()],
            ]);
        }
    }
    ///
    public function getDiscountProducts(Request $request)
    {
        try {
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
                ->where('discounts.quantity', '>', 0)
                ->where('discounts.status', 'Active')
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
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => [$e->getMessage()],
            ]);
        }
    }
}
