<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class PlansController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['user'] = \Auth::user();

        return view('plans.index', $data)->render();
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        \Auth::user()->newSubscription('main', 'lasingle')->quantity(100)->create();
        return redirect('/billing');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $quantity = ($request->input('additional_users') * 5) + 45;
        if (\Auth::user()->subscribed('main')) {
            \Auth::user()->subscription('main')->updateQuantity($quantity);
        } else {
            \Auth::user()->newSubscription('main', 'lasingle')->quantity($quantity)->create();
        }
        if ($request->input('coupon') != '') {
            try {
                \Auth::user()->applyCoupon($request->input('coupon'));
            } catch (\Stripe\Error\InvalidRequest $e) {
                session()->flash('error', $e->getMessage());
                return redirect()->back();
            }
        }
        return redirect('/billing');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
