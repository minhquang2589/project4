<?php

namespace App\Http\Controllers;

use App\Models\LikedProducts;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    //
    public function account(Request $request, $id)
    {
        $currentUser = $request->user();
        if ($currentUser->id != $id) {
            return response()->json([
                'success' => false,
                'error' => 'You do not have permission to access this user\'s information!'
            ], 403);
        }
        $user = User::find($id);
        $likedProducts = DB::table('liked_products')
            ->join('products', 'liked_products.product_id', '=', 'products.id')
            ->leftJoin('product_variant', 'products.id', '=', 'product_variant.product_id')
            ->leftJoin('discounts', 'product_variant.discount_id', '=', 'discounts.id')
            ->where('liked_products.user_id', $user->id)
            ->select(
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
            ->limit(3)
            ->get();
        return response()->json([
            'success' => true,
            'user' => $user,
            'likedProducts' => $likedProducts
        ]);
    }
    ///
    public function accountEdit($id)
    {
        $user = User::find($id);
        return response()->json([
            'success' => true,
            'user' => $user
        ]);
    }
    //
    public function accountUpdate(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'fullname' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|regex:/^\d{10}$/',
            'birthday' => 'required|date',
            'gender' => 'required|in:Male,Female',
            'address' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:600',

        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => $validator->errors()->all(),
            ]);
        }
        $user = User::findOrFail($request->userId);
        if (!$user) {
            return response()->json([
                'success' => false,
                'error' => ['user not found!']
            ]);
        }
        try {
            DB::beginTransaction();
            $user->name = $request->fullname;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->birthday = $request->birthday;
            $user->descriptions = $request->description;
            if ($request->hasFile('avatar')) {
                $image = $request->file('avatar');
                $fileName = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('images'), $fileName);
                $user->image = $fileName;
            }
            $user->gender = $request->gender;
            $user->address = $request->address;
            $user->save();
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => ['User update successfully!']
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'error' => [' failed: ' . $e->getMessage()],
            ]);
        }
    }
    ///
    public function likedProducts($page, $id)
    {

        $perPage = 15;
        $likedProducts = DB::table('liked_products')
            ->skip(($page - 1) * $perPage)
            ->take($perPage)
            ->join('products', 'liked_products.product_id', '=', 'products.id')
            ->leftJoin('product_variant', 'products.id', '=', 'product_variant.product_id')
            ->leftJoin('discounts', 'product_variant.discount_id', '=', 'discounts.id')
            ->where('liked_products.user_id', $id)
            ->select(
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

        $hasMore = $likedProducts->count() > 0;

        return response()->json([
            'success' => true,
            'productsMore' => $likedProducts,
            'hasMore' => $hasMore
        ]);
    }
}
