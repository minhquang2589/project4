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

class LikeController extends Controller
{
    //
    public function like(Request $request)
    {
        $userId = auth()->id();
        $productId = $request->productId;
        if (!$userId || !$productId) {
            return response()->json(['success' => false, 'message' => 'error']);
        }
        try {
            DB::beginTransaction();
            $existingLike = LikedProducts::where('user_id', $userId)
                ->where('product_id', $productId)
                ->first();
            if ($existingLike) {
                $existingLike->delete();
                DB::commit();
                return response()->json(['success' => true, 'message' => 'Product unliked']);
            }
            LikedProducts::create([
                'user_id' => $userId,
                'product_id' => $productId,
            ]);
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Product liked']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'error' => [$e->getMessage()],
            ]);
        }
    }
}
