<?php

namespace App\Http\Controllers;
use App\Mail\OrderShipped;
use Illuminate\Support\Facades\Mail;


use Illuminate\Http\Request;

class SendMailController extends Controller
{
    //
    public function sendMail(
        $orderDate,
        $element,
        $orderNumber,
        $cart,
        $DETACheckout
    ) {
        $response = Mail::to($element['email'])->send(new OrderShipped(
            $orderDate,
            $element,
            $orderNumber,
            $cart,
            $DETACheckout
        ));
    }
}
