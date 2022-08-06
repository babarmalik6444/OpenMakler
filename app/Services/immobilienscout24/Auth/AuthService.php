<?php

namespace App\Services\immobilienscout24\Auth;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Subscriber\Oauth\Oauth1;

/**
 * Class AuthService.
 */
class AuthService
{
   protected string   $oauth1_key;
   protected string   $oauth1_secret;
   protected string   $is24_domain;
   protected string   $oauth1_callbackUrl;
   protected string   $scout24_token_url;

   function __construct()
   {
       $this->oauth1_key = config('scout24.scout24_oauth1_key');
       $this->oauth1_secret = config('scout24.scout24_oauth1_secret');
       $this->is24_domain = config('scout24.scout24_is24_domain');
       $this->oauth1_callbackUrl = config('scout24.scout24_oauth1_callbackUrl');
       $this->scout_url = config('scout24.scout24_Url');
       $this->scout_redirect_url = config('scout24.scout24_token_url');
   }

  public function getRequestToken($reast_estate_id)
  {
      $stack = HandlerStack::create();

      $middleware = new Oauth1([
          'consumer_key'    => $this->oauth1_key,
          'consumer_secret' => $this->oauth1_secret,
          'request_method' => Oauth1::REQUEST_METHOD_QUERY,
          'signature_method' => Oauth1::SIGNATURE_METHOD_HMAC,
          'callback' => 'http://openmakler/app/real-estates/'.$reast_estate_id.'/edit'
      ]);
      $stack->push($middleware);

      $client = new Client([
          'base_uri' => 'https://rest.sandbox-immobilienscout24.de/',
          'handler' => $stack,
          'verify' => false
      ]);

// Set the "auth" request option to "oauth" to sign using oauth
      $res = $client->post('restapi/security/oauth/request_token', ['auth' => 'oauth']);
      $statusCode = $res->getStatusCode();
      if($statusCode==200)
      {
          $content = $res->getBody()->getContents();
          parse_str($content, $res);
          $TokenUrl = $this->scout_redirect_url.$res['oauth_token'];
          return $TokenUrl;
      }
      else
      {
          return false;
      }
  }

  public function confirmAccess($response)
  {   dd($response['oauth_token']);
        $client = new Client(['verify' => false]);
        $headers = [
          'Authorization' => 'OAuth oauth_consumer_key="OpenmaklerKey",oauth_token="'.$response['oauth_token'].'",oauth_signature_method="HMAC-SHA1",oauth_timestamp="1659117498",oauth_nonce="qap9Js6E72S",oauth_version="1.0",oauth_callback="http://openmakler/app/real-estates/21/edit",oauth_signature="qkSotJNrOBzdtqTN7TIzhY3Dlug="',
          'Cookie' => 'SESSION=ODY5YmRjOTgtZDQyZi00ZWRjLWIxN2YtMzk0ZjNiOWQ3NTE5'
        ];
        $request = new Request('GET', 'https://rest.sandbox-immobilienscout24.de/restapi/security/oauth/confirm_access?oauth_token='.$response['oauth_token'], $headers);
        $res = $client->sendAsync($request)->wait();
        return $res->getBody()->getContents();
  }

  public function getAccessToken($verifier)
  {
        $client = new Client(['verify' => false]);
        $headers = [
                    'Authorization' => 'OAuth oauth_consumer_key="OpenmaklerKey",oauth_token="0c710b4d-c364-4768-8f48-386ee8679740",oauth_signature_method="HMAC-SHA1",oauth_timestamp="1659128604",oauth_nonce="GuoZIsXyzKd",oauth_version="1.0",oauth_callback="http%3A%2F%2Fopenmakler%2Fapp%2Freal-estates%2F21%2Fedit",oauth_verifier="nbYCQ1",oauth_signature="Y3wK3WYzhUFmkyUcHQqeEPS8lsM%3D"',
                    'Cookie' => 'SESSION=OGI2MGE1OWItNTBjZS00MDM0LWI2ZWQtYmI3YTE5MGU2MDFk'
                  ];
        $request = new Request('GET', 'https://rest.sandbox-immobilienscout24.de/restapi/security/oauth/access_token', $headers);
        $res = $client->sendAsync($request)->wait();
        $content = $res->getBody()->getContents();
        parse_str($content, $res);
        dd($res);
        return $res;

  }


public function addProperty($record)
{
    $client = new Client(['verify' => false]);
    $nonce = mt_rand();
    $headers = [
      'Content-Type' => 'application/xml',
      'Cookie' => 'SESSION=ODBmYjc2MzctOTBkNC00MDE3LThjZDQtNGMxODJiZmMxYzE4'
    ];
    $body = '<realestates:apartmentBuy xmlns:realestates="http://rest.immobilienscout24.de/schema/offer/realestates/1.0" xmlns:xlink="http://www.w3.org/1999/xlink">
     <title>'.$record->objekttitel.'</title>
     <address>
       <street>'.$record->geo->strasse.'</street>
       <houseNumber>'.$record->geo->hausnummer.'</houseNumber>
       <postcode>'.$record->geo->plz.'</postcode>
        <city>'.$record->geo->ort.'</city>
     </address>
     <showAddress>true</showAddress>
     <price>
       <value>'.$record->preis->kaufpreis.'</value>
       <currency>EUR</currency>
     </price>
     <livingSpace>'.$record->flaechen->wohnflaeche.'</livingSpace>
     <numberOfRooms>'.$record->flaechen->anzahl_wohn_schlafzimmer.'</numberOfRooms>
     <courtage>
       <hasCourtage>YES</hasCourtage>
       <courtage>7,14%</courtage>
     </courtage>
     </realestates:apartmentBuy>';
    $request = new Request('POST', 'https://rest.sandbox-immobilienscout24.de/restapi/api/offer/v1.0/user/me/realestate?oauth_consumer_key=OpenmaklerKey&oauth_token=07b0aead-0185-4e18-9c1b-2681019e007e&oauth_signature_method=HMAC-SHA1&oauth_timestamp=1659457545&oauth_nonce='.$nonce.'&oauth_version=1.0&oauth_verifier=kocanH&oauth_signature=iyR6aWbB%2FHa6Begf91pJBrlf5QQ%3D', $headers, $body);
    $res = $client->sendAsync($request)->wait();
    $statusCode = $res->getStatusCode();
    if($statusCode==201)
    {
      $content = $res->getBody()->getContents();
      //parse_str($content, $res);
      $xml = simplexml_load_string($content);
      $json = json_encode($xml);
      $array = json_decode($json,TRUE);
      return $array;
    }
    else
    {
      return "Something went worng";
    }
}

public function UpdateProperty($record)
{    //dd($record->scout_api_id);
        $client = new Client(['verify' => false]);
        $headers = [
          'Content-Type' => 'application/xml',
          'Cookie' => 'SESSION=ZmFlNGJkNjUtMjA1MS00MDBiLTg2YzctMjI2NmEyNWYzYzYz'
        ];
        $body = '<realestates:apartmentBuy xmlns:realestates="http://rest.immobilienscout24.de/schema/offer/realestates/1.0" xmlns:xlink="http://www.w3.org/1999/xlink">
         <externalId>'.$record->scout_api_id.'</externalId>
         <title>'.$record->objekttitel.'</title>
         <address>
           <street>'.$record->geo->strasse.'</street>
           <houseNumber>'.$record->geo->hausnummer.'</houseNumber>
           <postcode>'.$record->geo->plz.'</postcode>
            <city>'.$record->geo->ort.'</city>
         </address>
         <showAddress>true</showAddress>
         <price>
           <value>'.$record->preis->kaufpreis.'</value>
           <currency>EUR</currency>
         </price>
         <livingSpace>'.$record->flaechen->wohnflaeche.'</livingSpace>
         <numberOfRooms>'.$record->flaechen->anzahl_wohn_schlafzimmer.'</numberOfRooms>
         <courtage>
           <hasCourtage>YES</hasCourtage>
           <courtage>7,14%</courtage>
         </courtage>
         </realestates:apartmentBuy>';
        $request = new Request('PUT', 'https://rest.sandbox-immobilienscout24.de/restapi/api/offer/v1.0/user/me/realestate/'.$record->scout_api_id.'?oauth_consumer_key=OpenmaklerKey&oauth_token=07b0aead-0185-4e18-9c1b-2681019e007e&oauth_signature_method=HMAC-SHA1&oauth_timestamp=1659457998&oauth_nonce=Qc96PLInXyH&oauth_version=1.0&oauth_verifier=kocanH&oauth_signature=zZuTGOF5obVpktxmTeyJX8qy1Do%3D', $headers, $body);
        $res = $client->sendAsync($request)->wait();
        $statusCode = $res->getStatusCode();
        if($statusCode==200)
        {
          $content = $res->getBody()->getContents();
          parse_str($content, $res);
          return $res;
        }
        else
        {
          return "Something went worng";
        }
  }

  public function DeleteProperty($record)
{       dd($record->scout_api_id);
        $client = new Client(['verify' => false]);
        $headers = [
          'Cookie' => 'SESSION=NDQ0MDdjZTEtMGYwMy00MWRkLThjZWYtMTI1YTM3MmNmZDJi'
        ];
        $request = new Request('DELETE', 'https://rest.sandbox-immobilienscout24.de/restapi/api/offer/v1.0/user/me/realestate/'.$record->scout_api_id.'?oauth_consumer_key=OpenmaklerKey&oauth_token=07b0aead-0185-4e18-9c1b-2681019e007e&oauth_signature_method=HMAC-SHA1&oauth_timestamp=1659457594&oauth_nonce=BUDP818lGPG&oauth_version=1.0&oauth_verifier=kocanH&oauth_signature=SFPaBMU55+r7F8DQ+fn0g90YKAw=');
        $res = $client->sendAsync($request)->wait();
        $statusCode = $res->getStatusCode();
        if($statusCode==200)
        {
          $content = $res->getBody()->getContents();
          parse_str($content, $res);
          return $res;
        }
        else
        {
          return "Something went worng";
        }
  }
}
