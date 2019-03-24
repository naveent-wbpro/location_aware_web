<?php

namespace App\Libraries;

class CustomerThermometer {

    /**
     * @var string
     */
    protected $api_key = '41389d213d7b0338270c3c91ab67e134';

    /**
     * @var string
     */
    protected $api_url = 'https://app.customerthermometer.com/api.php';


    public function sendEmail($request)
    {
        $client = new \GuzzleHttp\Client();
        $res = $client->request('GET', $this->api_url, [
            'query' => [
                'apiKey' => $this->api_key,
                'getMethod' => 'sendEmail',
                'thermometerID' => 44350,
                'listID' => '73093',
                'emailAddress' => $request->customer_email,
                'firstName' => $request->customer_name,
                'custom1' => $request->company_id,
                'custom2' => $request->id,
                'custom3' => $request->customer_name,
                'custom4' => $request->company->name,
            ]
        ]);        
    }
}
