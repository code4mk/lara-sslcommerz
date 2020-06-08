<?php

namespace Code4mk\Sslcommerz;

use GuzzleHttp\Client;

class Sslcommerz{

    private $amount;
    private $tnx;
    private $airTicket = [];
    private $emi = false;
    private $sslHost;

    public function __construct()
    {
        if (config('sslcommerz.sandbox_mode') === 'sandbox') {
            $this->sslHost = "https://sandbox.sslcommerz.com/";
        }else{
            $this->sslHost = "https://securepay.sslcommerz.com/";
        }

    }

    public function tnx($id)
    {
        $this->tnx = $id;
        return $this;
    }

    public function amount($amount)
    {
        $this->amount = $amount;
        return $this;
    }

    public function airlineTickets($data)
    {
        array_push($this->airTicket,$data);
        return $this;

    }

    public function emi($month,$selectedMonth)
    {
        $this->emi = true;
        $this->month = $month;
        $this->selectedMonth = $selectedMonth;
        return $this;
    }

    public function customer($name,$email,$add1,$city,$post_code,$country,$phone){
        $data['cus_name'] = $name;
        $data['cus_email'] = $email;
        $data['cus_add1'] = $add1;
        $data['cus_city'] = $city;
        $data['cus_postcode'] = $postcode;
        $data['cus_country'] = $country;
        $data['cus_phone'] = $phone;
    }


    public function getSession()
    {
        $data = [
            'store_id'=> config('sslcommerz.store_id'),
            'store_passwd'=> config('sslcommerz.store_password'),
            'total_amount' => $this->amount,
            'currency'=> config('sslcommerz.currency'),
            'tran_id'=> $this->tnx,
            'success_url'=> config('sslcommerz.success_url'),
            'fail_url'=> config('sslcommerz.fail_url'),
            'cancel_url'=> config('sslcommerz.cancel_url'),
            'ipn_url' => config('sslcommerz.ipn_url'),
            'cus_name'=> 'kamal',
            'cus_email'=> 'kamal@gmail.com',
            'cus_add1'=> 'dhaka',
            'cus_add2'=>'dhaka',
            'cus_city'=>'dhaka',
            'cus_state'=>'dhaka',
            'cus_postcode'=>'302',
            'cus_country'=>'Bangladesh',
            'cus_phone'=>'01711111111',
            'cus_fax'=>'01711111111',
            'product_name' => 'kamal',
            'product_category' => 'phone',
            'product_profile' => 'general',
            'shipping_method' => 'NO',
            'ship_name'=>'Customer Name',
            'ship_add1' =>'Dhaka',
            'ship_add2'=>'Dhaka',
            'ship_city'=>'Dhaka',
            'ship_state'=>'Dhaka',
            'ship_postcode'=>'1000',
            'ship_country'=>'Bangladesh',
            'multi_card_name'=>'mastercard,visacard,amexcard',
            'value_a'=>'ref001_A',
            'value_b'=>'ref002_B',
            'value_c'=>'ref003_C',
            'value_d'=>'ref004_D',
        ];

        if($this->emi){
            $data['emi'] = 1;
            $data['emi_max_inst_option'] = (int) $this->month;
            $data['emi_selected_inst'] = (int) $this->selectedMonth;
        }

        $http = new Client([
            'base_uri' => $this->sslHost,
        ]);

        $response = $http->post('gwprocess/v4/api.php',[
            'form_params' => $data
        ]);

        $body = json_decode($response->getBody());
        // return $body->redirectGatewayURL;
        return $body;

    }

    public function validate($request)
    {
        $http = new Client([
            'base_uri' => $this->sslHost,
        ]);

        $response = $http->get('validator/api/merchantTransIDvalidationAPI.php',[
            'query' => [
                'val_id' => $request->val_id,
                'store_id' => config('sslcommerz.store_id'),
                'store_passwd' => config('sslcommerz.store_password'),
                'format' => 'json'
            ]
        ]);

        return json_decode($response->getBody());
    }
}
