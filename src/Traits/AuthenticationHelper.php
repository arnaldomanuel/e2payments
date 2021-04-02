<?php
namespace Explicador\Payments\Traits;

use ErrorException;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;

trait AuthenticationHelper {
   
    private function generateBearerToken(){
    
        $client = new Client(['base_uri' => 'http://127.0.0.1:9000/']);
        $response = $client->request('POST', '/oauth/token', ['form_params' => [
            'grant_type' => 'client_credentials',
            'client_id'=>env('E2payments_client_id'),
            'client_secret' => env('E2payments_cliet_secret'),
        ]]);
        $myfile = fopen(storage_path('bearer.token.key'), "w") or die("Unable to open file!");
        $responseInJson = (String) $response->getBody();
        $responseInJson = json_decode($responseInJson, true);
        $token = $responseInJson['access_token'];
        fwrite($myfile, $token);
        fclose($myfile);
        $this->token = $token;
        return $token;
    }

    private function getHeaders(){
       return [
            'Accept'=> 'application/json',
            'Authorization'=>$this->token ? 'Bearer ' . $this->token: 'Bearer ' . $this->getBearerToken(),
            'Content-Type' => 'application/json'
       ];
    }
    private function getBearerToken(){
        try {
            $myfile = fopen(storage_path('bearer.token.key'), "r");
        } catch (ErrorException $ex) {
            return $this->generateBearerToken();
        }
        $token = fread($myfile,filesize(storage_path('bearer.token.key')));
        fclose($myfile);
        return $token;
    }
    private function getClient(){
        $client = new Client(['base_uri' => 'http://127.0.0.1:9000/']);
        return  $client;
    }
    private function getUrl($path){
        return 'http://127.0.0.1:9000/v1/'.$path;
    }
}