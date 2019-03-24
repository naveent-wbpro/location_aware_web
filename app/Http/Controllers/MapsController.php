<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class MapsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $hashids = new \Hashids\Hashids('AMx05k50Tyx676FWdNcSThcr4ZpDf7FR');
        $id = $hashids->decode($id)[0];

        $data['code_snippet'] = \App\CodeSnippetWebsite::where('id', '=', $id)->first();
        if ($request->input('address')) {
            $coordinates = \App\Libraries\Coordinates::streetAddress($request->input('address'));

            if (!is_object($coordinates)) {
                $data['coordinates'] = ['error' => 'not_found'];
            } else {
                $data['coordinates'] = $coordinates;
            }
        } else {
            $data['coordinates'] = [];
        }

        if ($request->input('token')) {
            $prime_claim = \App\PrimeClaim::where('token', '=', $request->input('token'))->where('created_at', '>', \Carbon\Carbon::now()->subHour())->first();

            if (empty($prime_claim)) {
                $client = new \GuzzleHttp\Client();
                $token = $request->input('token');
                $auth = vsprintf("%s:%s", ['api_user@nationalwater.com', 'pE/YkaRFzk1d8wQI50SFOB7MUhjgX4wq']);
                $encoded = base64_encode($auth);

                try {
                    $response = $client->request('GET', env('PRIME_PC_URL') . $token, [
                        'headers' => [
                            'ContentType' => "application/json",
                            'Authorization' => 'Basic '.$encoded,
                        ] 
                    ]);

                    $contents = json_decode($response->getBody()->getContents());

                    if (!empty($contents)) {
                        $prime_claim = new \App\PrimeClaim();
                        $prime_claim->token = $request->input('token');
                        $prime_claim->claim_number = $contents[0]->ClaimNumber;
                        $prime_claim->insurance_company = $contents[0]->InsuranceCompany;
                        $prime_claim->insured = $contents[0]->Insured;
                        $prime_claim->insured_email = $contents[0]->InsuredEmail;
                        $prime_claim->insured_phone = $contents[0]->InsuredPhone;
                        $prime_claim->date_of_loss = \Carbon\Carbon::parse($contents[0]->DateOfLoss);
                        $prime_claim->street_address_1 = $contents[0]->StreetAddress1;
                        $prime_claim->street_address_2 = $contents[0]->StreetAddress2;
                        $prime_claim->zipcode = $contents[0]->Zipcode;
                        $prime_claim->city = $contents[0]->City;
                        $prime_claim->state_desc = $contents[0]->StateDesc;
                        $prime_claim->save();

                        $data['prime_id'] = $prime_claim->id;
                    } else {
                        $data['prime_id'] = ''; 
                    }
                } catch(Exception $e) {
                    $e->getMessage();
                }
            } else {
                $data['prime_id'] = $prime_claim->id; 
            }
        } else {
            $data['prime_id'] = '';
        }


        echo view('maps.show', $data);
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
    public function update(Request $request, $id)
    {
        //
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
