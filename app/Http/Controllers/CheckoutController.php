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
use App\Models\SellerNotification;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;


class CheckoutController extends Controller
{
    //
    public function checkoutView(Request $request)
    {
        $cart = json_decode(Redis::get('cart'), true);
        $dataCart = json_decode(Redis::get('dataCart'), true);
        $dataInVoucher = json_decode(Redis::get('dataInVoucher'), true);

        if (!$cart) {
            return response()->json([
                'success' => false,
                'cart' => false,
            ]);
        }
        if ($cart) {
            $cartQuantity = count($cart);
        } else {
            $cartQuantity = 0;
        }
        $subtotal = 0;
        $totalWithoutVat = 0;
        $total = 0;
        $vatRate = 0;
        $vat = 0;
        $totalDiscountAmount = 0;
        $totalOriginalPrice = 0;

        foreach ($cart as $item) {
            if (is_array($item) && isset($item['id'])) {
                $discount = $item['discountPercent'] ?? 0;
                $product = Products::find($item['id']);
                if ($product) {
                    $productPrice = $product->price;
                    $productVariant = ProductVariants::where('product_id', $product->id)
                        ->whereHas('size', function ($query) use ($item) {
                            $query->where('size', $item['size']);
                        })
                        ->whereHas('color', function ($query) use ($item) {
                            $query->where('color', $item['color']);
                        })
                        ->first();
                    if ($productVariant) {
                        if ($productVariant->discount_id) {
                            $discountData = Discounts::where('id', $productVariant->discount_id)
                                ->where('start_datetime', '<=', Carbon::now())
                                ->where('end_datetime', '>=', Carbon::now())
                                ->first();
                            if ($discountData) {
                                $discount = $discountData->discount;
                            }
                        }
                        $discountAmount = ($productPrice * $discount) / 100;
                        $subtotal += ($productPrice - $discountAmount) * $item['quantity'];
                        $totalWithoutVat += $productPrice * $item['quantity'];
                        $totalDiscountAmount += $discountAmount * $item['quantity'];
                        $totalOriginalPrice += $productPrice * $item['quantity'];
                    } else {
                        return response()->json([
                            'success' => false,
                            'message' => ['error!'],
                        ]);
                    }
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => ['error!'],
                    ]);
                }
            }
        }


        if ($dataInVoucher && $dataInVoucher['voucherCode']) {
            $voucherCode = $dataInVoucher['voucherCode'];
            if ($voucherCode) {
                $voucher = Vouchers::where('voucher_code', $voucherCode)
                    ->where('voucher_quantity', '>', 0)
                    ->where('status', '=', 'Active')
                    ->where('discount_value', '>', 0)
                    ->first();
                $VoucherValue = $voucher->discount_value;
                $voucherDiscountPercentage = ($VoucherValue / 100) * $subtotal;
                $subtotal -= $voucherDiscountPercentage;
                $totalDiscountAmount += $voucherDiscountPercentage;
            }
        }

        $vat = $subtotal * $vatRate;
        $total = $totalWithoutVat + $vat;
        $DETACheckout = [
            'voucherCode' => $voucherCode ?? null,
            'checkoutTotal' => $total,
            'checkoutTotal' => $total,
            'checkoutSubtotal' => $subtotal,
            'VoucherValue' => $VoucherValue ?? null,
            'totalDiscountAmount' => $totalDiscountAmount ?? null,
            'cartCheckout' => $cart,
            'cartQuantity' => $cartQuantity,
        ];
        Redis::set('DETACheckout', json_encode($DETACheckout));
        return response()->json([
            'success' => true,
            'DETACheckout' => $DETACheckout,
        ]);
    }
    ///
    public function process(Request $request)
    {
        $DETACheckout = json_decode(Redis::get('DETACheckout'), true);
        $cart = json_decode(Redis::get('cart'), true);
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|regex:/^\d{10}$/',
            'address' => 'required|string|max:255',
            'note' => 'max:1000',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()->all(),
            ]);
        }
        $payment = $request->input('payment');
        switch ($payment) {
            case 'qr':
                $payment = 'Thanh toán bằng mã QR code';
                break;
            case 'meet':
                $payment = 'Thanh toán khi nhận hàng';
                break;
            case 'paypal':
                $payment = 'Thanh toán bằng Paypal';
                break;
            case 'bank':
                $payment = 'Chuyển khoản';
                break;
            default:
                break;
        }
        $element = [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'address' => $request->input('address'),
            'notes' => $request->input('note'),
            'phone' => $request->input('phone'),
            'payment' =>  $payment,
            'subtotal' => $DETACheckout['checkoutSubtotal'],
            'total' => $DETACheckout['checkoutTotal'],
        ];
        $paymentName = $request->input('payment');
        if (!$paymentName) {
            return response()->json([
                'success' => false,
                'message' => ['Please select payment method'],
            ]);
        }
        $dataCheckout = [
            'element' => $element,
            'cart' => $cart,
            'paymentName' => $paymentName,
            'totalDiscountAmount' => $DETACheckout['totalDiscountAmount'],
            'subtotal' => $DETACheckout['checkoutSubtotal'],
            'total' => $DETACheckout['checkoutTotal'],
        ];
        Redis::set('element', json_encode($element));
        return response()->json([
            'success' => true,
            'data' => $dataCheckout,
        ]);
    }
    //
    public function paymentView()
    {

        function getTime()
        {
            $currentDateTime = Carbon::now('Asia/Ho_Chi_Minh');
            return $currentDateTime->format('d/m/Y H:i:s');
        }
        $cart = json_decode(Redis::get('cart'), true);
        $element = json_decode(Redis::get('element'), true);
        $DETACheckout = json_decode(Redis::get('DETACheckout'), true);
        $time = getTime();
        $orderNumber = $element['phone'];
        if (
            !$orderNumber
            ||  !$cart
            ||  !$element
            ||  !$DETACheckout
        ) {
            return response()->json([
                'success' => false,
                'payment' => false,
            ]);
        }
        Redis::set('time', json_encode($time));
        return response()->json([
            'success' => true,
            'data' => $DETACheckout,
            'orderNumber' => $orderNumber,
            'element' => $element,
            'time' => $time,
        ]);
    }
    ///
    public function handlecheckout(Request $request)
    {

        try {
            DB::beginTransaction();
            $user = Auth::user();

            $time = json_decode(Redis::get('time'), true);
            $cart = json_decode(Redis::get('cart'), true);
            $element = json_decode(Redis::get('element'), true);
            $DETACheckout = json_decode(Redis::get('DETACheckout'), true);
            
            function generateOrderNumber()
            {
                if (!Redis::get('orderNumber')) {
                    $unique = false;
                    $randomDigits = '';
                    while (!$unique) {
                        $randomDigits = sprintf('%07d', mt_rand(0, 9999999));
                        $exists = OrderNumbers::where('order_number', $randomDigits)->exists();
                        if (!$exists) {
                            $unique = true;
                        }
                    }
                    Redis::set('orderNumber', json_encode($randomDigits));
                    return $randomDigits;
                } else {
                    return  json_decode(Redis::get('orderNumber'), true);
                }
            }

            if ($cart) {
                $voucherCode = $DETACheckout['voucherCode'];
                if ($voucherCode) {
                    $voucher = Vouchers::where('voucher_code', $voucherCode)->first();
                    if ($voucher) {
                        if (
                            $voucher->discount_value <= 0
                            || $voucher->voucher_quantity <= 0
                            || $voucher->status != 'Active'
                        ) {
                            return response()->json([
                                'success' => false,
                                'error' => ['error 1'],
                            ]);
                        }
                    } else {
                        return response()->json([
                            'success' => false,
                            'error' => ['Voucher not found'],
                        ]);
                    }
                }

                foreach ($cart as $item) {
                    $productId = $item['id'];
                    $discountId = $item['discountId'];
                    $sizeName = $item['size'];
                    $colorName = $item['color'];
                    $quantity = $item['quantity'];
                    $productVariant = ProductVariants::whereHas('size', function ($query) use ($sizeName) {
                        $query->where('size', $sizeName);
                    })->whereHas('color', function ($query) use ($colorName) {
                        $query->where('color', $colorName);
                    })->where('product_id', $productId)->first();

                    if (!$productVariant || $productVariant->quantity < $quantity) {
                        return response()->json([
                            'success' => false,
                            'error' => ['Out of stock'],
                        ]);
                    }

                    if ($discountId) {
                        $discount = Discounts::find($discountId);
                        if (
                            $discount->remaining < $quantity
                            || $discount->status == 'Used'
                            || $discount->status == 'Expired'
                            || $discount->discount <= 0
                        ) {
                            return response()->json([
                                'success' => false,
                                'error' => ['Invalid discount'],
                            ]);
                        }
                    }
                }
            } else {
                return response()->json([
                    'success' => false,
                    'error' => ['Cart is empty'],
                ]);
            }


            $email = $element['email'];
            $orderNumber =generateOrderNumber();



            $payment = Payments::firstOrCreate(['name' => $element['payment']]);
            $ordernumber = OrderNumbers::create(['order_number' => $orderNumber]);

            $status = $this->getOrderStatus($element['payment']);


            $order = new Orders();
            $order->buyer_user_id = $user->id;
            $order->total_amount = $element['subtotal'];
            $order->notes = $element['notes'] ?? null;
            $order->voucher_id = Vouchers::where('voucher_code', $DETACheckout['voucherCode'])->value('id');
            $order->total_discount = $DETACheckout['totalDiscountAmount'] ?? 0;
            $order->payment_method_id = $payment->id;
            $order->status = $status;
            $order->order_number_id = $ordernumber->id;
            $order->order_date = $time;
            $order->save();

            foreach ($cart as &$item) {
                $item['finalPrice'] = isset($item['price']) && isset($item['discountPercent']) ?
                    $item['price'] - ($item['price'] * $item['discountPercent']) / 100 : $item['price'];

                $orderItem = new OrderItems();
                $orderItem->order_id = $order->id;
                $orderItem->product_id = $item['id'];
                $orderItem->color_id = Colors::where('color', $item['color'])->value('id');
                $orderItem->size_id = Sizes::where('size', $item['size'])->value('id');

                $orderItem->quantity = $item['quantity'];
                $orderItem->price = $item['finalPrice'];
                $orderItem->save();

                $notificationSeller = new SellerNotification();
                $notificationSeller->seller_id = $item['user_id'];
                $notificationSeller->order_id = $order->id;
                $notificationSeller->message = 'New order has been placed successfully.';
                $notificationSeller->save();
            }

            // $emailController = new SendMailController();
            // $emailController->sendMail($time, $element, $orderNumber, $cart, $DETACheckout);
            // $this->sendNotification($ordernumber, $order->id);
            // $this->checkoutSuccess($cart, $DETACheckout);
            // $this->clearCacheData();

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => $cart,
            ]);

            return response()->json([
                'success' => true,
                'message' => ['Checkout successfully.'],
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'error' => ['Checkout failed: ' . $e->getMessage()],
            ]);
        }
    }

    protected function checkoutSuccess($cart, $DETACheckout)
    {
        try {
            DB::beginTransaction();
            $user = Auth::user();
            $UserInfor = UserInfor::where('user_id',  $user->id)->first();


            $voucherCode = $DETACheckout['voucherCode'];
            if ($voucherCode) {
                $voucher = Vouchers::where('voucher_code', $voucherCode)->first();
                if ($voucher) {
                    $voucher->voucher_quantity -= 1;
                    if ($voucher->voucher_quantity <= 0) {
                        $voucher->status = 'Expired';
                    }
                    $voucher->save();
                }
            }
            foreach ($cart as $item) {
                $discountId = $item['discountId'];
                $productId = $item['id'];
                $sizeName = $item['size'];
                $colorName = $item['color'];
                $quantity = $item['quantity'];
                $user_id = $item['user_id'];

                $productVariant = ProductVariants::whereHas('size', function ($query) use ($sizeName) {
                    $query->where('size', $sizeName);
                })->whereHas('color', function ($query) use ($colorName) {
                    $query->where('color', $colorName);
                })->where('product_id', $productId)->first();

                if ($productVariant) {
                    $productVariant->quantity = max(0, $productVariant->quantity - $quantity);
                    $productVariant->sales_count += $quantity;
                    $productVariant->save();
                }
                if ($UserInfor) {
                    $UserInfor->increment('total_purchases', $item['quantity']);
                } else {
                    $UserInfor = new UserInfor();
                    $UserInfor->user_id = $user->id;
                    $UserInfor->total_purchases += $item['quantity'];
                    $UserInfor->save();
                }
                UserInfor::where('id', $user_id)->increment('sold_purchases', $item['quantity']);
                User::where('id', $user_id)->increment('sold_purchases', $item['quantity']);
                User::where('id', $user->id)->increment('total_purchases', $item['quantity']);
                if ($discountId) {
                    $discount = Discounts::find($discountId);
                    if ($discount) {
                        $discount->remaining = max(0, $discount->remaining - $quantity);
                        if ($discount->remaining <= 0) {
                            $discount->status = 'Expired';
                            ProductVariants::where('discount_id', $discountId)->update(['discount_id' => null]);
                        }
                        $discount->save();
                    }
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'error' => [$e->getMessage()],
            ]);
        }
    }

    protected function getOrderStatus($paymentMethod)
    {
        switch ($paymentMethod) {
            case 'Thanh toán bằng mã QR code':
            case 'Chuyển khoản':
                return 'Đang chờ xác nhận';
            case 'Thanh toán khi nhận hàng':
                return 'Chưa thanh toán';
            case 'Thanh toán bằng Paypal':
                return 'Thanh toán bằng Paypal';
            default:
                return 'Pedding';
        }
    }
    // protected function sendNotification($orderNumber, $orderId)
    // {
    //     $notificationSeller = new SellerNotification();
    //     $notificationSeller->seller_id = auth()->user()->id;
    //     $notificationSeller->order_id = $orderId;
    //     $notificationSeller->message = 'New order #' . $orderNumber . ' has been placed successfully.';
    //     $notificationSeller->save();
    //     $notification = new Notification();
    // }

    protected function clearCacheData()
    {
        Redis::del([
            'cart', 'time', 'data', 'subtotal', 'voucherCode', 'aruvoucher',
            'DETACheckout', 'element', 'dataCart', 'total', 'voucherDiscountPercent',
            'cartQuantity', 'orderNumber'
        ]);
    }
}
