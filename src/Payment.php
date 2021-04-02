<?php

namespace Explicador\Payments;

use ErrorException;
use Explicador\Payments\Traits\AuthenticationHelper;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;

class Payment
{
    use AuthenticationHelper;
    private $token;
    
    public function getWallets()
    {

       $response = Http::withHeaders($this->getHeaders())
       ->post($this->getUrl('wallets/mpesa/get/all'), [
        'client_id' => env('E2payments_client_id'),
        'skip_domain_from_validation'=>1,
       ]);

        return json_decode((String)$response->getBody(), true);
    }

    public function getWallet($id){
        $response = Http::withHeaders($this->getHeaders())
        ->post($this->getUrl("wallets/mpesa/get/$id"), [
         'client_id' => env('E2payments_client_id'),
         'skip_domain_from_validation'=>1,
        ]);
         return json_decode((String)$response->getBody(), true);
    }

    public function getPayments(){
        $response = Http::withHeaders($this->getHeaders())
        ->post($this->getUrl("payments/mpesa/get/all"), [
         'client_id' => env('E2payments_client_id'),
         'skip_domain_from_validation'=>1,
        ]);
         return json_decode((String)$response->getBody(), true);
    }
    public function makeC2bPayment($amount, $phone, $reference, $walletId){
       
        $response = Http::withHeaders($this->getHeaders())
        ->post($this->getUrl("c2b/mpesa-payment/".$walletId), [
         'client_id' => env('E2payments_client_id'),
         'skip_domain_from_validation'=>1,
         'amount'=>$amount,
         'phone'=>$phone,
         'reference'=>$reference
        ]);
         return json_decode((String)$response->getBody(), true);
    }

    public function getUser(){
       
        $response = Http::withHeaders($this->getHeaders())
        ->post($this->getUrl("user"), [
         'client_id' => env('E2payments_client_id'),
         'skip_domain_from_validation'=>1,
        ]);
         return json_decode((String)$response->getBody(), true);
    }
    public function getPaginatedPayments($qtd, $next=null){
        $url = $this->getUrl("payments/mpesa/get/all/paginate/$qtd");
        if ($next) {
            $url = $next['next_page_url'];
         }
        $response = Http::withHeaders($this->getHeaders())
            ->post($url, [
            'client_id' => env('E2payments_client_id'),
            'skip_domain_from_validation'=>1,
            ]);
         return json_decode((String)$response->getBody(), true);
    }
    
}
