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
use App\Models\Rates;
use App\Models\Follows;
use App\Models\UserInfor;
use App\Models\User;
use App\Models\LikedProducts;
use Exception;

class FollowController extends Controller
{
    //
    public function follow($page)
    {
        if (Auth::check()) {
            try {
                DB::beginTransaction();
                $user = Auth::user();
                $perPage = 15;
                $followedUsers = DB::table('follows')
                    ->where('user_id', $user->id)
                    ->pluck('follow_user_id');

                $userStats = DB::table('users')
                    ->whereIn('users.id', $followedUsers)
                    ->leftJoin('rate', 'users.id', '=', 'rate.rate_user_id')
                    ->leftJoin('follows as followedBy', 'users.id', '=', 'followedBy.follow_user_id')
                    ->leftJoin('follows as follower', function ($join) use ($user) {
                        $join->on('users.id', '=', 'follower.follow_user_id')
                            ->where('follower.user_id', '=', $user->id);
                    })
                    ->leftJoin('products', 'users.id', '=', 'products.user_id')
                    ->select(
                        'users.id',
                        'users.name',
                        'users.image',
                        DB::raw('COUNT(DISTINCT rate.id) AS rate_count'),
                        DB::raw('COUNT(DISTINCT followedBy.id) AS follow'),
                        DB::raw('COUNT(DISTINCT follower.id) AS follower'),
                        DB::raw('COUNT(DISTINCT products.id) AS total_products')
                    )
                    ->groupBy('users.id', 'users.name', 'users.image',)
                    ->skip(($page - 1) * $perPage)
                    ->take($perPage)
                    ->get();
                $hasMore = $userStats->count() > 0;
                DB::commit();
                return response()->json([
                    'success' => true,
                    'hasMore' => $hasMore,
                    'data' => $userStats,
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'error' => [$e->getMessage()],
                ]);
            }
        } else {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }
    }
    ///
    public function follower($page, $id)
    {
        try {
            $perPage = 15;
            $page = request()->input('page', 1);
            DB::beginTransaction();
            $user = User::find($id);

            $userStats = DB::table('users')
                ->where('users.id', $user->id)
                ->leftJoin('rate', 'users.id', '=', 'rate.rate_user_id')
                ->leftJoin('follows as followedBy', 'users.id', '=', 'followedBy.follow_user_id')
                ->leftJoin('follows as follower', function ($join) use ($user) {
                    $join->on('users.id', '=', 'follower.follow_user_id')
                        ->where('follower.user_id', '=', $user->id);
                })
                ->leftJoin('products', 'users.id', '=', 'products.user_id')
                ->select(
                    'users.id',
                    'users.name',
                    'users.image',
                    DB::raw('COUNT(DISTINCT rate.id) AS rate_count'),
                    DB::raw('COUNT(DISTINCT followedBy.id) AS follow'),
                    DB::raw('COUNT(DISTINCT follower.id) AS follower'),
                    DB::raw('COUNT(DISTINCT products.id) AS total_products')
                )
                ->groupBy('users.id', 'users.name', 'users.image')
                ->skip(($page - 1) * $perPage)
                ->take($perPage)
                ->get();
            $hasMore = $userStats->count() > 0;
            DB::commit();
            return response()->json([
                'success' => true,
                'hasMore' => $hasMore,
                'data' => $userStats,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'error' => [$e->getMessage()],
            ]);
        }
    }
    ///
    public function handleFollow(Request $request)
    {
        $userId = auth()->id();
        if (!$userId || !$request->user_id) {
            return response()->json(['success' => false, 'error' => ['User not found!']]);
        }
        if ($userId == $request->user_id) {
            return response()->json(['success' => true, 'message' => ['followed!']]);
        }
        try {
            DB::beginTransaction();

            $existingFollow = Follows::where('user_id', $userId)
                ->where('follow_user_id', $request->user_id)
                ->first();
            if ($existingFollow) {
                $existingFollow->delete();
                DB::commit();
                return response()->json(['success' => true, 'message' => 'User Unfollowed']);
            }
            Follows::create([
                'user_id' => $userId,
                'follow_user_id' => $request->user_id,
            ]);
            DB::commit();
            return response()->json(['success' => true, 'message' => 'followed!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'error' => [$e->getMessage()],
            ]);
        }
    }
    ////
    public function userFollow($page, $id)
    {

        try {
            DB::beginTransaction();
            $perPage = 15;
            $followedByUsers = DB::table('follows')
                ->where('follow_user_id', $id)
                ->pluck('user_id');

            $userStats = DB::table('users')
                ->whereIn('users.id', $followedByUsers)
                ->leftJoin('rate', 'users.id', '=', 'rate.rate_user_id')
                ->leftJoin(DB::raw('(SELECT follow_user_id, COUNT(*) as follow_count FROM follows GROUP BY follow_user_id) as followCounts'), 'users.id', '=', 'followCounts.follow_user_id')
                ->leftJoin(DB::raw('(SELECT user_id, COUNT(*) as follower_count FROM follows WHERE follow_user_id = ' . $id . ' GROUP BY user_id) as followerCounts'), 'users.id', '=', 'followerCounts.user_id')
                ->leftJoin('products', 'users.id', '=', 'products.user_id')
                ->select(
                    'users.id',
                    'users.name',
                    'users.image',
                    DB::raw('COUNT(DISTINCT rate.id) AS rate_count'),
                    DB::raw('COALESCE(followCounts.follow_count, 0) AS follower'),
                    DB::raw('COALESCE(followerCounts.follower_count, 0) AS follow'),
                    DB::raw('COUNT(DISTINCT products.id) AS total_products')
                )
                ->groupBy('users.id', 'users.name', 'users.image', 'followCounts.follow_count', 'followerCounts.follower_count')
                ->skip(($page - 1) * $perPage)
                ->take($perPage)
                ->get();


            $hasMore = $userStats->count() > 0;
            DB::commit();
            return response()->json([
                'success' => true,
                'hasMore' => $hasMore,
                'data' => $userStats,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'error' => [$e->getMessage()],
            ]);
        }
    }
    ///
    public function userFollower($page, $id)
    {

        try {
            DB::beginTransaction();
            $perPage = 15;
            $followingUsers = DB::table('follows')
                ->where('user_id', $id)
                ->pluck('follow_user_id');

            $userStats = DB::table('users')
                ->whereIn('users.id', $followingUsers)
                ->leftJoin('rate', 'users.id', '=', 'rate.rate_user_id')
                ->leftJoin(DB::raw('(SELECT follow_user_id, COUNT(*) as follow_count FROM follows GROUP BY follow_user_id) as followCounts'), 'users.id', '=', 'followCounts.follow_user_id')
                ->leftJoin(DB::raw('(SELECT user_id, COUNT(*) as follower_count FROM follows GROUP BY user_id) as followerCounts'), 'users.id', '=', 'followerCounts.user_id')
                ->leftJoin('products', 'users.id', '=', 'products.user_id')
                ->select(
                    'users.id',
                    'users.name',
                    'users.image',
                    DB::raw('COUNT(DISTINCT rate.id) AS rate_count'),
                    DB::raw('COALESCE(followCounts.follow_count, 0) AS follower'),
                    DB::raw('COALESCE(followerCounts.follower_count, 0) AS follow'),
                    DB::raw('COUNT(DISTINCT products.id) AS total_products')
                )
                ->groupBy('users.id', 'users.name', 'users.image', 'followCounts.follow_count', 'followerCounts.follower_count')
                ->skip(($page - 1) * $perPage)
                ->take($perPage)
                ->get();


            $hasMore = $userStats->count() > 0;
            DB::commit();
            return response()->json([
                'success' => true,
                'hasMore' => $hasMore,
                'data' => $userStats,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'error' => [$e->getMessage()],
            ]);
        }
    }
    ///
    public function checkFollow($id)
    {
        try {
            DB::beginTransaction();
            $userId = auth()->id();
            if (!$userId && !$id) {
                return response()->json(['success' => false, 'error' => ['User not found!']]);
            }
            $isFollowing = DB::table('follows')
                ->where('user_id', $userId)
                ->where('follow_user_id', $id)
                ->exists();

            DB::commit();
            return response()->json([
                'success' => true,
                'isFollowing' => $isFollowing,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'error' => [$e->getMessage()],
            ]);
        }
    }
}
