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
use App\Models\Notification;
use Illuminate\Support\Facades\DB;
use App\Models\Details;
use App\Models\ProductVariants;
use App\Models\Sizes;
use App\Models\Colors;
use App\Models\LikedProducts;
use App\Models\UserInfor;
use App\Models\User;
use Exception;





class ProductController extends Controller
{
    public function uploadFile(Request $request)
    {
        try {
            if ($request->hasFile('upload')) {
                $originName = $request->file('upload')->getClientOriginalName();
                $fileName = pathinfo($originName, PATHINFO_FILENAME);
                $extension = $request->file('upload')->getClientOriginalExtension();
                $fileName = $fileName . '_' . time() . '.' . $extension;
                $request->file('upload')->move(public_path('images'), $fileName);
                $url = asset('images/' . $fileName);
                return response()->json([
                    'url' => $url,
                    'uploaded' => 1,
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'uploaded' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
    ////
    public function upload(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'product_name' => 'required|string',
            'price' => 'required',
            'gender' => 'required',
            'status' => 'required',
            'category' => 'required|string',
            'colors.*' => 'required',
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',

        ], [
            // 'discountnumber.min' => 'Số giảm giá phải lớn hơn hoặc bằng 1.'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => $validator->errors()->all(),
            ]);
        }
        try {
            DB::beginTransaction();
            $user = Auth::user();


            $startDatetime = date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $request->input('start_datetime'))));
            $endDatetime = date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $request->input('end_datetime'))));

            $discount = new Discounts();
            $discount->discount = $request->input('discountnumber');
            $discount->quantity = $request->input('discountquantity');
            $discount->remaining = $request->input('discountquantity');
            $discount->status = 'Active';
            $discount->start_datetime = $startDatetime;
            $discount->end_datetime = $endDatetime;
            $discount->save();

            $product = new Products();
            $product->user_id = $user->id;
            $product->status = $request->status;
            $product->name = $request->product_name;
            $product->description =  $request->description;
            $product->price = $request->price;
            $product->category =  $request->category;
            $product->brand =  $request->brand;
            $product->save();
            $userInfo = UserInfor::firstOrCreate(['user_id' =>  $user->id]);
            $userInfo->increment('uploaded_product');

            $ProductCates = new Cates();
            $ProductCates->cate = $request->input('gender');
            $ProductCates->product_id = $product->id;
            $ProductCates->save();

            if ($request->details) {
                foreach ($request->details as $detail) {
                    $productDetails = new Details();
                    $productDetails->product_id =  $product->id;
                    $productDetails->detail = $detail;
                    $productDetails->save();
                }
            }

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $originName = $image->getClientOriginalName();
                    $fileName = pathinfo($originName, PATHINFO_FILENAME);
                    $extension = $image->getClientOriginalExtension();
                    $fileName = $fileName . '_' . time() . '.' . $extension;
                    $image->move(public_path('images'), $fileName);
                    $url = asset('images/' . $fileName);
                    $Images = new Images();
                    $Images->product_id = $product->id;
                    $Images->image = $fileName;
                    $Images->save();
                }
            } else {
                return response()->json([
                    'success' => false,
                    'error' => ['Please enter Images!'],
                ]);
            }

            $discountID = $discount->id;
            $discount = Discounts::find($discountID);
            $discount_id = NULL;
            if ($discount) {
                $discountQuantity = $discount->quantity;
                $discountValue = $discount->discount;
                if ($discountQuantity <= 0 || $discountValue <= 0 || $discountQuantity == NULL || $discountValue == NULL) {
                    $discount_id = NULL;
                } else {
                    $discount_id = $discount->id;
                }
            }
            if ($request->has('colors')) {
                $colors = json_decode($request->colors, true);
                foreach ($colors as $colorData) {
                    $colorName = $colorData['name'];
                    $colorModel = Colors::firstOrCreate(['color' => $colorName]);
                    $totalQuantity = 0;
                    foreach ($colorData['sizes'] as $sizeData) {
                        $sizeName = $sizeData['name'];
                        $quantity = $sizeData['quantity'];

                        if ($quantity !== null && $quantity > 0) {
                            $totalQuantity += $quantity;
                            $sizeModel = Sizes::firstOrCreate(['size' => $sizeName]);

                            ProductVariants::create([
                                'product_id' => $product->id,
                                'size_id' => $sizeModel->id,
                                'color_id' => $colorModel->id,
                                'discount_id' => $discount_id,
                                'quantity' => $quantity,
                            ]);
                        }
                    }
                    if ($totalQuantity === 0) {
                        return response()->json([
                            'success' => false,
                            'error' => ['Please enter color and quantity'],
                        ]);
                    }
                }
            } else {
                return response()->json([
                    'success' => false,
                    'error' => ['Please enter color and quantity'],
                ]);
            }
            $notification = new Notification();
            $notification->user_id = $user->id;
            $notification->product_id = $product->id;
            $notification->message = $user->name . ' just uploaded  product: ' . $request->product_name;
            $notification->user_id = $user->id;
            $notification->save();


            DB::commit();
            return response()->json([
                'success' => true,
                'message' => ['Product uploaded successfully!'],
            ]);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ]);
        }
    }
    ///
    public function view($id)
    {
        $product = Products::select(
            'products.id',
            'products.name',
            'products.description',
            'products.price',
            'products.user_id',
            'products.brand',
            'products.status as productStatus',
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
                'products.brand',
                'products.status',
                'discounts.status',
                'discounts.id'

            )
            ->find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => ['Product not found'],
            ]);
        }
        $productVariants = ProductVariants::where('product_id', $id)->get();
        $ProductDetailImg = Images::where('product_id', $id)->get();
        $sizeIds = $productVariants->pluck('size_id')->unique()->filter();
        $colorIds = $productVariants->pluck('color_id')->unique()->filter();
        $sizes = Sizes::whereIn('id', $sizeIds)->get();
        $colors = Colors::whereIn('id', $colorIds)->get();
        $productCates = Cates::where('product_id', $product->id)->get();
        $productDetails = Details::where('product_id', $product->id)->get();
        $countLike = LikedProducts::where('product_id', $product->id)->count();
        return response()->json([
            'success' => true,
            'product_info' => $productCates,
            'productDetails' => $productDetails,
            'ProductDetailImg' => $ProductDetailImg,
            'product' => $product,
            'productVariant' => $productVariants,
            'sizes' => $sizes,
            'colors' => $colors,
            'countLike' => $countLike,

        ]);
    }
    ///
    public function checkStock(Request $request)
    {
        $productId = $request->input('product_id');
        $sizeName = $request->input('size');
        $colorName = $request->input('color');
        $size = Sizes::where('size', $sizeName)->first();
        $color = Colors::where('color', $colorName)->first();

        if (!$size || !$color) {
            return response()->json([
                'success' => false,
                'message' => ['Size or color not found'],
            ]);
        }

        $product = Products::find($productId);
        $productVariant = ProductVariants::where('product_id', $product->id)
            ->whereHas('size', function ($query) use ($size) {
                $query->where('size', $size->size);
            })
            ->whereHas('color', function ($query) use ($color) {
                $query->where('color', $color->color);
            })
            ->first();
        if (!$productVariant) {
            return response()->json([
                'success' => false,
                'message' => ['Out of stock'],
            ]);
        }
        if ($productVariant->quantity > 0) {
            return response()->json([
                'success' => true,
                'size' => $sizeName,
                'color' => $colorName,
                'productVariant' => $productVariant,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => ['Out of stock'],
            ]);
        }
    }
    ///
    public function relatedProduct(Request $request)
    {
        $page = $request->input('page');
        $perPage = 30;
        $productsMore = Products::inRandomOrder()
            ->skip(($page - 1) * $perPage)
            ->take($perPage)
            ->select(
                'products.id',
                'products.name',
                'products.description',
                'products.price',
                DB::raw('SUM(product_variant.quantity) as total_quantity'),
                'discounts.quantity as discount_quantity',
                'discounts.discount',
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
                'discounts.discount'
            )
            ->get();
        $hasMore = $productsMore->count() > 0;
        return response()->json([
            'success' => true,
            'productsMore' => $productsMore,
            'hasMore' => $hasMore
        ]);
    }
}
