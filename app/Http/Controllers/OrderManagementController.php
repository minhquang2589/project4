<?php

namespace App\Http\Controllers;

use App\Models\Colors;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Products;
use App\Models\Images;
use App\Models\User;
use App\Models\ProductVariants;
use App\Models\Discounts;
use App\Models\Vouchers;
use App\Models\OrderNumbers;
use App\Models\OrderItems;
use App\Models\Orders;
use App\Models\UserInfor;
use App\Models\Payments;
use App\Models\Sizes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class OrderManagementController extends Controller
{
    //
    public function orders()
    {
        try {
            $datacustomer = DB::table('orders')
                ->join('customers', 'orders.customer_id', '=', 'customers.id')
                ->join('order_numbers', 'orders.order_number_id', '=', 'order_numbers.id')
                ->join('payment_methods', 'orders.payment_method_id', '=', 'payment_methods.id')
                ->select(
                    'customers.id as customer_id',
                    'customers.name as customer_name',
                    'customers.email',
                    'customers.address',
                    'customers.phone',
                    'customers.total_purchases',
                    'orders.total_amount',
                    'orders.status',
                    'order_numbers.order_number',
                    'payment_methods.name as payment_method'
                )
                ->get();
            return response()->json([
                'success' => true,
                'orders' => $datacustomer
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => ['Checkout failed: ' . $e->getMessage()],
            ]);
        }
    }
}
