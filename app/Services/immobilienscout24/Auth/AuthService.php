<?php
namespace App\Services\immobilienscout24\Auth;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Subscriber\Oauth\Oauth1;
use App\Models\ScouteApi;
use App\Models\Openimmo\RealEstate;
/**
 * Class AuthService.
 */
class AuthService
{
    protected string $oauth1_key;
    protected string $oauth1_secret;
    protected string $is24_domain;
    protected string $oauth1_callbackUrl;
    protected string $scout24_token_url;

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
      try
        {
          $stack = HandlerStack::create();

          $middleware = new Oauth1(['consumer_key' => $this->oauth1_key, 'consumer_secret' => $this->oauth1_secret, 'request_method' => Oauth1::REQUEST_METHOD_HEADER, 'signature_method' => Oauth1::SIGNATURE_METHOD_HMAC, 'callback' => 'http://openmakler2/app/real-estates/' . $reast_estate_id . '/edit']);
          $stack->push($middleware);

          $client = new Client(['base_uri' => 'https://rest.sandbox-immobilienscout24.de/', 'handler' => $stack, 'verify' => false]);

          // Set the "auth" request option to "oauth" to sign using oauth
          $res = $client->post('restapi/security/oauth/request_token', ['auth' => 'oauth']);
          $statusCode = $res->getStatusCode();
          if ($statusCode == 200)
          {
              $content = $res->getBody()
                  ->getContents();
              parse_str($content, $res);
              ScouteApi::query()->truncate();
              $ScoutData = new ScouteApi();
              $ScoutData->oauth_token_secret = $res['oauth_token_secret'];
              $ScoutData->oauth_token = $res['oauth_token'];
              $ScoutData->save();
              $TokenUrl = $this->scout_redirect_url . $res['oauth_token'];
              return $TokenUrl;
          }
          else
          {
              return false;
          }
        } catch(\Exception $e)
          {
              return false;
          }
    }

    public function getAccessToken($token, $verifier, $id)
    {
        try
        {
            $stack = HandlerStack::create();
            $ScoutData = ScouteApi::latest()->first();
            //dd($ScoutData);
            $middleware = new Oauth1(['consumer_key' => $this->oauth1_key, 'consumer_secret' => $this->oauth1_secret, 'callback' => 'http://openmakler2/app/real-estates/' . $id . '/edit', 'token_secret' => $ScoutData->oauth_token_secret, 'verifier' => $verifier, 'token' => $ScoutData->oauth_token, 'request_method' => Oauth1::REQUEST_METHOD_HEADER, 'signature_method' => Oauth1::SIGNATURE_METHOD_HMAC]);

            $stack->push($middleware);

            $client = new Client(['base_uri' => 'https://rest.sandbox-immobilienscout24.de/', 'handler' => $stack, 'verify' => false]);
            //dd($client);
            // Set the "auth" request option to "oauth" to sign using oauth
            $res = $client->post('restapi/security/oauth/access_token', ['auth' => 'oauth']);
            $content = $res->getBody()
                ->getContents();
            parse_str($content, $res);
            ScouteApi::where('id', 1)->update(['oauth_token_secret' => $res['oauth_token_secret'], 'oauth_token' => $res['oauth_token'], 'verifier' => $verifier]);
            $statusCode = $res->getStatusCode();
            if ($statusCode == 200)
            {
                $content = $res->getBody()
                    ->getContents();
                parse_str($content, $res);
                ScouteApi::query()->truncate();
                $ScoutData = new ScouteApi();
                $ScoutData->oauth_token_secret = $res['oauth_token_secret'];
                $ScoutData->oauth_token = $res['oauth_token'];
                $ScoutData->save();
                $TokenUrl = $this->scout_redirect_url . $res['oauth_token'];
                return $TokenUrl;
            } else
              {
                return false;
              }
        } catch(\Exception $e)
          {
              return false;
          }
    }

    public function addProperty($record)
    {

        try
        {

            $headers = ['Content-Type' => 'application/xml', 'Accept' => 'application/xml;strict=true'];
            $body = '<realestates:apartmentBuy xmlns:realestates="http://rest.immobilienscout24.de/schema/offer/realestates/1.0" xmlns:xlink="http://www.w3.org/1999/xlink">
  <title>' . $record->objekttitel . '</title>
  <address>
  <street>' . $record
                ->geo->strasse . '</street>
  <houseNumber>' . $record
                ->geo->hausnummer . '</houseNumber>
  <postcode>' . $record
                ->geo->plz . '</postcode>
  <city>' . $record
                ->geo->ort . '</city>
  </address>
  <showAddress>true</showAddress>
  <price>
  <value>' . $record
                ->preis->kaufpreis . '</value>
  <currency>EUR</currency>
  </price>
  <livingSpace>' . $record
                ->flaechen->wohnflaeche . '</livingSpace>
  <numberOfRooms>' . $record
                ->flaechen->anzahl_wohn_schlafzimmer . '</numberOfRooms>
  <courtage>
  <hasCourtage>YES</hasCourtage>
  <courtage>7,14%</courtage>
  </courtage>
  </realestates:apartmentBuy>';
            $stack = HandlerStack::create();
            $ScoutData = ScouteApi::latest()->first();

            $middleware = new Oauth1(['consumer_key' => $this->oauth1_key, 'consumer_secret' => $this->oauth1_secret, 'callback' => 'http://openmakler2/app/real-estates/' . $record->id . '/edit', 'token_secret' => $ScoutData->oauth_token_secret, 'verifier' => $ScoutData->verifier, 'token' => $ScoutData->oauth_token, 'request_method' => Oauth1::REQUEST_METHOD_HEADER, 'signature_method' => Oauth1::SIGNATURE_METHOD_HMAC]);

            $stack->push($middleware);

            $client = new Client(['base_uri' => 'https://rest.sandbox-immobilienscout24.de/', 'handler' => $stack, 'verify' => false, 'headers' => $headers]);
            // Set the "auth" request option to "oauth" to sign using oauth
            $res = $client->post('restapi/api/offer/v1.0/user/me/realestate', ['auth' => 'oauth', 'body' => $body]);

            $statusCode = $res->getStatusCode();
            $statusCode = $res->getStatusCode();
            if ($statusCode == 201)
            {
                $content = $res->getBody()
                    ->getContents();
                //parse_str($content, $res);
                $xml = simplexml_load_string($content);
                $json = json_encode($xml);
                $array = json_decode($json, true);
                return $array;
            }
            else
            {
                return false;
            }

        }
        catch(\Exception $e)
        {
            return false;
        }

    }

    public function UpdateProperty($record)
    { //dd($record->scout_api_id);
        try
        {
            $headers = ['Content-Type' => 'application/xml', 'Accept' => 'application/xml;strict=true'];

            $body = '<realestates:apartmentBuy xmlns:realestates="http://rest.immobilienscout24.de/schema/offer/realestates/1.0" xmlns:xlink="http://www.w3.org/1999/xlink">
  <externalId>' . $record->scout_api_id . '</externalId>
  <title>' . $record->objekttitel . '</title>
  <address>
  <street>' . $record
                ->geo->strasse . '</street>
  <houseNumber>' . $record
                ->geo->hausnummer . '</houseNumber>
  <postcode>' . $record
                ->geo->plz . '</postcode>
  <city>' . $record
                ->geo->ort . '</city>
  </address>
  <showAddress>true</showAddress>
  <price>
  <value>' . $record
                ->preis->kaufpreis . '</value>
  <currency>EUR</currency>
  </price>
  <livingSpace>' . $record
                ->flaechen->wohnflaeche . '</livingSpace>
  <numberOfRooms>' . $record
                ->flaechen->anzahl_wohn_schlafzimmer . '</numberOfRooms>
  <courtage>
  <hasCourtage>YES</hasCourtage>
  <courtage>7,14%</courtage>
  </courtage>
  </realestates:apartmentBuy>';

            $stack = HandlerStack::create();
            $ScoutData = ScouteApi::latest()->first();

            $middleware = new Oauth1(['consumer_key' => $this->oauth1_key, 'consumer_secret' => $this->oauth1_secret, 'callback' => 'http://openmakler2/app/real-estates/' . $record->id . '/edit', 'token_secret' => $ScoutData->oauth_token_secret, 'verifier' => $ScoutData->verifier, 'token' => $ScoutData->oauth_token, 'request_method' => Oauth1::REQUEST_METHOD_HEADER, 'signature_method' => Oauth1::SIGNATURE_METHOD_HMAC]);

            $stack->push($middleware);

            $client = new Client(['base_uri' => 'https://rest.sandbox-immobilienscout24.de/', 'handler' => $stack, 'verify' => false, 'headers' => $headers]);

            $res = $client->put('restapi/api/offer/v1.0/user/me/realestate/' . $record->scout_api_id . '', ['auth' => 'oauth', 'body' => $body]);
            $statusCode = $res->getStatusCode();
            if ($statusCode == 200)
            {
                $content = $res->getBody()->getContents();
                parse_str($content, $res);
                return true;
            }
            else
            {
                return false;
            }
        }
        catch(\Exception $e)
        {
            return false;
        }
    }

    public function DeleteProperty($record)
    { //dd($record->scout_api_id);
        try
        {

            $stack = HandlerStack::create();
            $ScoutData = ScouteApi::latest()->first();

            $middleware = new Oauth1(['consumer_key' => $this->oauth1_key, 'consumer_secret' => $this->oauth1_secret, 'callback' => 'http://openmakler2/app/real-estates/' . $record->id . '/edit', 'token_secret' => $ScoutData->oauth_token_secret, 'verifier' => $ScoutData->verifier, 'token' => $ScoutData->oauth_token, 'request_method' => Oauth1::REQUEST_METHOD_HEADER, 'signature_method' => Oauth1::SIGNATURE_METHOD_HMAC]);

            $stack->push($middleware);

            $client = new Client(['base_uri' => 'https://rest.sandbox-immobilienscout24.de/', 'handler' => $stack, 'verify' => false, ]);

            $res = $client->Request('DELETE', 'restapi/api/offer/v1.0/user/me/realestate/' . $record->scout_api_id . '', ['auth' => 'oauth']);
            $statusCode = $res->getStatusCode();
            if ($statusCode == 200)
            {
                $content = $res->getBody()
                    ->getContents();
                parse_str($content, $res);
                RealEstate::where('id', $record->id)
                    ->update(['scout_api_id' => '']);
                return true;
            }
            else
            {
                return false;
            }
        }
        catch(\Exception $e)
        {
            return false;
        }
    }
}

