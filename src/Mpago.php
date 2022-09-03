<?php

namespace Ales0sa\Mpago;

use Illuminate\Support\Facades\Http;

use \MercadoPago as MP;

class Mpago
{
    private $token;
    private $url;
    private $public_key;

    public function __construct()
    {

        $token = env('MP_TOKEN');
        
        $this->url = env('APP_URL', 'http://localhost:8000');
        
        $this->public_key = env('MP_PUBLIC_KEY');       
         
        
        MP\SDK::setAccessToken($token);

    }

    public function findPayment($id)
    {

        $payment = MP\Payment::find_by_id($id);
        
        return $payment;

    }

    public function newOrder($items, $ref)
    {
        
        $preference = new MP\Preference();

        $preference->external_reference = $ref;

        $preference->items = $items;

        $preference->back_urls = array(
            "success" => $this->url."/feedback",
            "failure" => $this->url."/feedback", 
            "pending" => $this->url."/feedback"
        );

        $preference->auto_return = "approved"; 

        $preference->save();

        return [
            'init_point' => $preference->init_point,
            'sandbox_init_point' => $preference->sandbox_init_point,
            'id' => $preference->id,
            'public_key' => $this->public_key
        ];

        
    }
    
    public function findOrder($id)
    {
        
        $preference = MP\Preference::find_by_id($id);
 
        return $preference;

    }

}