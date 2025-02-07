<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Customer;
use Stripe\Charge;
use App\Models\Reservation;

class PaymentController extends Controller
{
    public function checkout($id)
    {
        return view('checkout', compact('id'));
    }

    public function charge(Request $request)
    {
        try {
            Stripe::setApiKey(config('services.stripe.secret'));

            $customer = Customer::create(array('email' => $request->stripeEmail,
                                            'source' => $request->stripeToken
                                            )
                                        );

            $charge = Charge::create([
                'customer' => $customer->id,
                'amount' => $request->amount,
                'currency' => 'jpy',
                'description' => 'Test Payment',
            ]);

            $reservation = Reservation::find($request->reservation_id);
            $reservation->is_paid = true;
            $reservation->save();

            return redirect()->route('user.mypage')->with('message', '支払いが完了しました！');

        } catch (\Exception $e) {
            return back()->with('error', '支払いに失敗しました: ' . $e->getMessage());
        }

    }
}
