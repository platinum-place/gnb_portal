<?php

//fuentes https://www.youtube.com/watch?v=ToCP_MwORAw

function generar_refresh_tokens()
{
    //alcance ZohoCRM.modules.Cases.CREATE

    $post = [
        "code" => "",
        "redirect_uri" => "",
        "client_id" => "",
        "client_secret" => "",
        "grant_type" => "authorization_code"
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://accounts.zoho.com/oauth/v2/token");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/x-www-form-urlencoded',
    ));

    $result = curl_exec($ch);
    echo ('<pre>');
    print_r(json_decode($result, true));
    echo ('</pre>');

    curl_close($ch);
}
generar_refresh_tokens();
