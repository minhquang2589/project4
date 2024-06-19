<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Products;
use App\Models\Images;
use App\Models\ProductVariants;
use App\Models\Discounts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

class CartController extends Controller
{
    //
    public function addcart(Request $request)
    {

        $quantityInput = $request->quantity;
        $productId = $request->input('product_id');
        $discountId = $request->input('discount_id');
        $product = Products::find($productId);

        $img = Images::where('product_id', $productId)->first();

        if (Redis::exists('cart')) {
            $cart = json_decode(Redis::get('cart'), true);
        } else {
            $cart = [];
        }
        if ($img) {
            $imageUrl = $img->image;
        } else {
            $imageUrl = null;
        }
        if (!$product) {
            return response()->json([
                'success' => false,
                'error' => ['Product not found.'],
            ]);
        }
        $size = $request->input('size');
        $color = $request->input('color');

        if (!$size  || !$color) {
            return response()->json([
                'success' => false,
                'error' => ['Please select size and color.'],
            ]);
        }
        $productVariant = ProductVariants::where('product_id', $product->id)
            ->whereHas('size', function ($query) use ($size) {
                $query->where('size', $size);
            })
            ->whereHas('color', function ($query) use ($color) {
                $query->where('color', $color);
            })
            ->first();
        if (!$productVariant || $productVariant->quantity <= 0) {
            return response()->json([
                'success' => false,
                'error' => ['This size is out of stock.']
            ]);
        }
        $discount = Discounts::where('id', $productVariant->discount_id)->first();
        if ($discount && $discount->remaining > 0) {
            $discountPercent = $discount->discount;
        } else {
            $discountPercent = null;
        }

        $totalQuantityInCart = 0;
        foreach ($cart as $item) {
            if ($item['id'] == $productId && $item['size'] == $size && $item['color'] == $color) {
                $totalQuantityInCart += $item['quantity'];
            }
        }

        $totalQuantityAvailable = $productVariant->quantity;
        $requestedQuantity = $totalQuantityInCart +  $quantityInput;
        if ($requestedQuantity > $totalQuantityAvailable) {
            return response()->json([
                'success' => false,
                'error' => ['Quantity exceeds the remaining stock.']
            ]);
        }

        $existingCartItem = null;
        foreach ($cart as $key => $item) {
            if ($item['id'] == $productId && $item['size'] == $size && $item['color'] == $color) {
                $existingCartItem = $key;
                break;
            }
        }

        if ($existingCartItem !== null) {
            $cart[$existingCartItem]['quantity'] += $quantityInput;
        } else {
            $priceAfterDiscount = $product->price;

            if ($discountPercent) {
                $priceAfterDiscount *= (1 - $discountPercent / 100);
            }
            $cart[] = [
                'id' => $productId,
                'user_id' =>$product->user_id,
                'name' => $product->name,
                'price' => $product->price,
                'image' => $imageUrl,
                'quantity' => $quantityInput,
                'discountPercent' => $discountPercent,
                'discountId' => $discountId,
                'size' => $size,
                'color' => $color,
            ];
        }
        $subtotal = 0;
        $totalWithoutVat = 0;
        $total = 0;
        $vatRate = 0;

        foreach ($cart as $index => $item) {
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
                    } else {
                        return response()->json([
                            'success' => false,
                            'error' => ['error1']
                        ]);
                    }
                } else {
                    return response()->json([
                        'success' => false,
                        'error' => ['error2']
                    ]);
                }
            }
        }

        $vat = $subtotal * $vatRate;
        $total = $totalWithoutVat + $vat;
        $cartQuantity = count($cart);
        $dataCart = [
            'total' => $total,
            'subtotal' => $subtotal,
            'vat' => $vat,
            'cartQuantity' => $cartQuantity,
        ];
        $dataProduct = [
            'id' => $productId,
            'quantity' => $quantityInput,
            'imageURL' => $imageUrl,
            'color' => $color,
            'size' => $size,
            'name' => $product->name,
        ];
        Redis::set('cart', json_encode($cart));
        Redis::set('dataCart', json_encode($dataCart));
        return response()->json([
            'success' => true,
            'message' => ['Add cart successfully'],
            'dataProduct' => $dataProduct,
        ]);
    }
    ///
    public function getCartQuantity()
    {
        $cartQuantity = count(json_decode(Redis::get('cart'), true));
        return response()->json(['cartQuantity' => $cartQuantity]);
    }
    ///
    public function cartView()
    {
        // Redis::del('cart');
        // Redis::del('dataCart');
        $cart = json_decode(Redis::get('cart'), true);
        $dataCart = json_decode(Redis::get('dataCart'), true);

        if (count($cart) > 0 && count($dataCart) > 0) {
            return response()->json([
                'success' => true,
                'cart' => $cart,
                'dataCart' => $dataCart,
            ]);
        } else {
            return response()->json([
                'success' => false,
            ]);
        }
    }
    ////
    public function removeItem($id, $size, $color, $quantity)
    {
        $cart = json_decode(Redis::get('cart'), true);
        $dataCart = json_decode(Redis::get('dataCart'), true);
        foreach ($cart as $key => $item) {
            if ($item['id'] == $id) {
                if ((!$size || (isset($item['size']) && $item['size'] == $size)) && (!$color || $item['color'] == $color)) {
                    if (!$quantity || ($quantity == $item['quantity'])) {
                        unset($cart[$key]);
                        Redis::set('cart', json_encode($cart));
                        $subtotal = 0;
                        $totalWithoutVat = 0;
                        $total = 0;
                        $vatRate = 0;
                        $vat = 0;
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
                                    } else {
                                        return response()->json([
                                            'success' => false,
                                            'message' => ['error']
                                        ]);
                                    }
                                } else {
                                    return response()->json([
                                        'success' => false,
                                        'message' => ['error']
                                    ]);
                                }
                            }
                        }

                        $vat = $subtotal * $vatRate;
                        $total = $totalWithoutVat + $vat;
                        $cartQuantity = count($cart);

                        $dataCart = [
                            'total' => $total,
                            'subtotal' => $subtotal,
                            'vat' => $vat,
                            'cartQuantity' => $cartQuantity,
                        ];
                        Redis::set('cart', json_encode($cart));
                        Redis::set('dataCart', json_encode($dataCart));
                        return response()->json([
                            'success' => true,
                            'message' => [' Removed successfully.'],
                            'cart' => $cart,
                        ]);
                    } else {
                        return response()->json([
                            'success' => false,
                            'message' => ['Quantity mismatch.'],
                            'cart' => $cart,
                        ]);
                    }
                }
            }
        }
        return response()->json(['error' => 'Product not found in cart.'], 404);
    }
    ////
    public function updateQuantity(Request $request, $id)
    {

        $newQuantity = $request->input('quantity');
        $color = $request->input('color');
        $size = $request->input('size');

        $cart = json_decode(Redis::get('cart'), true);
        if (empty($cart)) {
            return response()->json(['success' => false, 'message' => ['Cart is empty.']]);
        }
        $productVariant = ProductVariants::where('product_id', $id)
            ->whereHas('size', function ($query) use ($size) {
                $query->where('size', $size);
            })
            ->whereHas('color', function ($query) use ($color) {
                $query->where('color', $color);
            })
            ->first();
        if (!$productVariant) {
            return response()->json([
                'success' => false,
                'message' => ['error!'],
            ]);
        }
        $availableQuantity = $productVariant->quantity;
        if ($newQuantity > $availableQuantity) {
            return response()->json([
                'success' => false,
                'message' => ['The quantity exceeds the remaining stock.']
            ]);
        }
        foreach ($cart as $key => $item) {
            if ($item['id'] == $id && $item['color'] == $color && $item['size'] == $size) {

                if (!$productVariant) {
                    return response()->json([
                        'success' => false,
                        'message' => ['error!'],
                    ]);
                }
                $availableQuantity = $productVariant->quantity;
                if ($newQuantity > $availableQuantity) {
                    return response()->json([
                        'success' => false,
                        'message' => ['error!'],
                    ]);
                }
                $cart[$key]['quantity'] = $newQuantity;
                Redis::set('cart', json_encode($cart));
                return response()->json([
                    'success' => true,
                    'message' => ['Quantity update successfully!']
                ]);
            }
        }
        return response()->json(['success' => false, 'message' => 'Product not found in cart.']);
    }
    /////
    public function SubtotalTotal(Request $request)
    {
        $cart = json_decode(Redis::get('cart'), true);
        $subtotal = 0;
        $totalWithoutVat = 0;
        $total = 0;
        $vatRate = 0;
        $totalDiscountAmount = 0;
        $totalOriginalPrice = 0;
        $cartQuantity = 0;
        if (isset($cart) && count($cart) > 0) {
            $cartQuantity = count($cart);
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
                        } else {
                            return response()->json([
                                'success' => false,
                                'message' => ['error']
                            ]);
                        }
                    } else {
                        return response()->json([
                            'success' => false,
                            'message' => ['error']
                        ]);
                    }
                }
            }
        } else {
            $subtotal = 0;
            $totalWithoutVat = 0;
            $total = 0;
            $vatRate = 0;
            $totalDiscountAmount = 0;
            $totalOriginalPrice = 0;
            $cartQuantity = 0;
        }

        $vat = $subtotal * $vatRate;
        $total = $totalWithoutVat + $vat;

        return response()->json([
            'success' => true,
            'total' => $total,
            'subtotal' => $subtotal,
            'cartQuantity' => $cartQuantity,
            'VAT' => $vatRate,
            'totalDiscountAmount' => $totalDiscountAmount,
        ]);
    }
}
