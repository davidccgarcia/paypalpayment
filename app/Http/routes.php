<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('paypal', function () {
    $paypal = new App\PayPal(1);
    $payment = $paypal->generate();

    return redirect($payment->getApprovalLink());
});

Route::get('payments/store', function (Illuminate\Http\Request $request) {
    $shopping_cart_id = 1;

    $paypal = new App\PayPal(1);
    dd($paypal->execute($request->paymentId, $request->PayerID));
});
