<?php

namespace Code4mk\Sslcommerz;

use GuzzleHttp\Client;

/**
 * Sslcommerz class
 * @author code4mk <hiremostafa@gmail.com>
 * @version 1.0.0
 */

class Sslcommerz
{

    private $amount;
    private $tnx;
    private $sslHost;

    private $customerData = [
        'cus_name'     => 'kamal',
        'cus_email'    => 'no@no.com',
        'cus_phone'    => '00000000000',
        'cus_add1'     => 'dhaka',
        'cus_city'     => 'Dhaka',
        'cus_postcode' => '1210',
        'cus_country'  => 'Bangladesh',
    ];

    private $emi = [
      'emi_option' => 0,
      'emi_max_inst_option' => 3,
      'emi_selected_inst' => 2,
      'emi_allow_only' => 0,
    ];

    public function __construct()
    {
        if (config('sslcommerz.sandbox_mode') === 'sandbox') {
            $this->sslHost = "https://sandbox.sslcommerz.com/";
        }else{
            $this->sslHost = "https://securepay.sslcommerz.com/";
        }

    }

    /**
     * Set transaction id.
     *
     * @param int|string $id
     * @author code4mk <hiremostafa@gmail.com>
     * @since v1.0.0
     * @version 1.0.0
     */
    public function tnx($id)
    {
        $this->tnx = $id;
        return $this;
    }

    /**
     * Set amount.
     *
     * @param int|float $amount
     * @author code4mk <hiremostafa@gmail.com>
     * @since v1.0.0
     * @version 1.0.0
     */
    public function amount($amount)
    {
        $this->amount = $amount;
        return $this;
    }


    /**
     * Set emi option.
     *
     * @param int $max_inst emi_max_inst_option
     * @param int $selected_inst emi_selected_inst
     * @param int $allow_only emi_allow_only(0,1)*
     * @author code4mk <hiremostafa@gmail.com>
     * @since v1.0.0
     * @version 1.0.0
     */
    public function emi($max_inst = '',$selected_inst = '',$allow_only = '')
    {
        $this->emi['emi_option'] = 1;
        $this->emi['emi_max_inst_option'] = $max_inst == '' ? 3 : $max_inst;
        $this->emi['emi_selected_inst'] = $selected_inst == '' ? 3 : $selected_inst;
        $this->emi['emi_allow_only'] = $allow_only == '' ? 0 : $allow_only;
        return $this;
    }

    /**
     * Set customer information.
     *
     * @param string $name
     * @param string $email
     * @param string $phone
     * @param string $add1
     * @param string $city
     * @param string $post_code
     * @param string $country
     * @author code4mk <hiremostafa@gmail.com>
     * @since v1.0.0
     * @version 1.0.0
     */
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

    /**
     * Get response where you can get GatewayPageURL
     *
     * @return object
     * @author code4mk <hiremostafa@gmail.com>
     * @since v1.0.0
     * @version 1.0.0
     */
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
            'emi_option' => $this->emi['emi_option'],
            'emi_selected_inst' => $this->emi['emi_selected_inst'],
            'emi_max_inst_option' => $this->emi['emi_max_inst_option'] ,
            'emi_allow_only' => $this->emi['emi_allow_only'],
            'value_a'=>'ref001_A',
            'value_b'=>'ref002_B',
            'value_c'=>'ref003_C',
            'value_d'=>'ref004_D',
        ];

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

    /**
     * Verify the transaction.
     *
     * @return object
     * @author code4mk <hiremostafa@gmail.com>
     * @since v1.0.0
     * @version 1.0.0
     */
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
