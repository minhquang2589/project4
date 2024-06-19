<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
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
use App\Models\Rates;
use App\Models\LikedProducts;
use Exception;
use App\Models\Follows;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()->all(),
            ]);
        }

        $email = $request->email;
        $password = $request->password;

        $user = User::where('email', $email)->first();
        if ($user) {
            if (Hash::check($password, $user->password)) {
                Auth::login($user);
                Session::put('authenticated', true);

                return response()->json([
                    'success' => true,
                    'do-login' => 'Logged in successfully!',
                    'role' => $user->role,
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'errors' => ['Incorrect password!'],
                ]);
            }
        } else {
            return response()->json([
                'success' => false,
                'errors' => ['User does not exist!'],
            ]);
        }
    }
    ///
    public function checkAuth()
    {
        try {
            if (Auth::check()) {
                return response()->json([
                    'authenticated' => true,
                    'role' => Auth::user()->role,
                ]);
            } else {
                return response()->json(['authenticated' => false], 401);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function user()
    {
        try {
            if (Auth::check()) {
                $user = Auth::user();
                $likeProducts = LikedProducts::where('user_id', $user->id)->get();
                $follow = Follows::where('follow_user_id', $user->id)->count();
                $totalFollowing = DB::table('follows')
                    ->where('user_id', $user->id)
                    ->count();
                $averageRating = Rates::where('rate_user_id', $user->id)->avg('rating');
                $totalRates = Rates::where('rate_user_id', $user->id)->count();
                $countProduct = Products::where('user_id', $user->id)->count();

                return response()->json([
                    'success' => true,
                    'user' => $user,
                    'likeProducts' => $likeProducts,
                    'userInfor' => [
                        'follow' => $follow,
                        'totalFollowing' => $totalFollowing,
                        'averageRating' =>   $averageRating,
                        'totalRates' => $totalRates,
                        'countProduct' => $countProduct
                    ],
                ]);
            } else {
                return response()->json(['error' => 'Unauthenticated'], 401);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    ///
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return response()->json([
            'success' => true,
            'do-logout' => 'Logged out successfully!',
        ]);
    }
}
