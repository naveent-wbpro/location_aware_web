<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Error\Card;

class BillingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $user = \Auth::user();
        if ($user->stripe_id == null) {
            //Create stripe customer if doesn't exist
            $user->createAsStripeCustomer(null);
        }

        return view('billing.index', compact('user'))->render();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        //
        return view('billing.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //
        $user = \Auth::user();
        try {
            $user->updateCard($request->input('stripeToken'));
        } catch (Card $e) {
            return redirect('/billing')->with('error', 'Error updating card info: '.$e->getMessage());
        }

        return redirect('/billing')->with('success', 'Credit card updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        $user = \App\User::find(\Auth::user()->id);
        try {
            foreach ($user->asStripeCustomer()->sources->all()->data as $card) {
                $card->delete();
            }

            $user->card_brand = null;
            $user->card_last_four = null;
            $user->save();

            return redirect('/billing')->with('success', 'Credit card deleted successfully.');
        } catch (Card $e) {
            return redirect('/billing')->with('error', $e->getMessage());
        }
    }
}
