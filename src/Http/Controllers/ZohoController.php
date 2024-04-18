<?php

namespace Asciisd\Zoho\Http\Controllers;

use Zoho;
use zcrmsdk\oauth\ZohoOAuth;
use Illuminate\Routing\Controller;
use zcrmsdk\crm\setup\restclient\ZCRMRestClient;
use Asciisd\Zoho\Http\Requests\ZohoRedirectRequest;
use App\Classes\ZohoOptions;

class ZohoController extends Controller
{
    public function oauth2callback(ZohoRedirectRequest $request)
    {
        ZCRMRestClient::initialize(ZohoOptions::zohoOptions());
        $oAuthClient = ZohoOAuth::getClientInstance();
        $oAuthClient->generateAccessToken($request->code);

        return 'Zoho CRM has been set up successfully.';
    }

    public function oauth2callbackRX(ZohoRedirectRequest $request)
    {
        ZCRMRestClient::initialize(ZohoOptions::zohoOptions('zohoRX'));
        $oAuthClient = ZohoOAuth::getClientInstance();
        $oAuthClient->generateAccessToken($request->code);

        return 'ZohoRX CRM has been set up successfully.';
    }
}
