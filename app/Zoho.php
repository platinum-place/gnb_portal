<?php

namespace App;

use zcrmsdk\crm\setup\restclient\ZCRMRestClient;
use zcrmsdk\oauth\ZohoOAuth;

class Zoho
{
    function __construct()
    {
        ZCRMRestClient::initialize([
            "client_id" => "1000.7FJQ4A2KDH9S2IJWDYL13HATQFMA2H",
            "client_secret" => "c3f1d0589803f294a7c5b27e3968ae1658927da9d7",
            "currentUserEmail" => "tecnologia@gruponobe.com",
            "redirect_uri" => url("/"),
            "token_persistence_path" => base_path()
        ]);
    }

    //total access scope
    //aaaserver.profile.READ,ZohoCRM.users.ALL,ZohoCRM.modules.ALL,ZohoCRM.settings.all,ZohoCRM.settings.fields.ALL
    public function generateTokens($grant_token)
    {
        $oAuthClient = ZohoOAuth::getClientInstance();
        $oAuthClient->generateAccessToken($grant_token);
    }
}
