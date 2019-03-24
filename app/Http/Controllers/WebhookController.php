<?php

namespace App\Http\Controllers;

use Laravel\Cashier\Http\Controllers\WebhookController as CashierController;

class WebhookController extends CashierController
{
    /**
     * Handle a Stripe webhook.
     *
     * @param  array  $payload
     * @return Response
     */
    public function handleChargeSucceeded($payload)
    {
        $event = new \App\StripeEvent();
        $event->event_id = $payload['id'];
        $event->customer_id = $payload['data']['object']['customer'];
        $event->type = 'charge.succeeded';
        $event->object = json_encode($payload['data']['object']);
        $event->save();
    }
}
