<?php

namespace Code4mk\Sslcommerz;

use GuzzleHttp\Client;

class Sslcommerz{

    private $amount;
    private $tnx;
    private $airTicket = [];
    private $emi = false;
    private $sslHost;

    private $customerData = [
        'cus_name'     => 'kamal',
        'cus_email'    => 'no@no.com',
        'cus_phone'    => '01000000000',
        'cus_add1'     => 'dhaka',
        'cus_city'     => 'Dhaka',
        'cus_postcode' => '1210',
        'cus_country'  => 'Bangladesh',
    ];

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

    public function customer($name = '', $email = '', $phone = '', $add1 = '', $city = '', $post_code = '', $country = ''){
        $this->customerData['cus_name'] = $name == '' ? $this->customerData['cus_name'] : $name;
        $this->customerData['cus_email'] = $email == '' ? $this->customerData['cus_email'] : $email;
        $this->customerData['cus_phone'] = $phone == '' ? $this->customerData['cus_phone'] : $phone;
        $this->customerData['cus_add1'] = $add1 == '' ? $this->customerData['cus_name'] : $add1;
        $this->customerData['cus_city'] = $city == '' ? $this->customerData['cus_city'] : $city;
        $this->customerData['cus_postcode'] = $post_code == '' ? $this->customerData['cus_name'] : $post_code;
        $this->customerData['cus_country'] = $country == '' ? $this->customerData['cus_name'] : $country;
        return $this;
    }

    public function getRedirectUrl()
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
            'cus_name'=> $this->customerData['cus_name'],
            'cus_email'=> $this->customerData['cus_email'],
            'cus_add1'=> $this->customerData['cus_add1'],
            'cus_city'=> $this->customerData['cus_city'],
            'cus_postcode'=> $this->customerData['cus_postcode'],
            'cus_country'=> $this->customerData['cus_country'],
            'cus_phone'=> $this->customerData['cus_phone'],
            'product_name' => 'ecom',
            'product_category' => 'ecom',
            'product_profile' => 'general',
            'shipping_method' => 'NO',
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

    public function verify($request)
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
