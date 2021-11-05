<?php

//fuentes https://www.youtube.com/watch?v=ToCP_MwORAw

function generar_refresh_tokens()
{
    //alcance ZohoCRM.modules.Cases.CREATE

    $post = [
        "code" => "1000.dc02312938060362b0b3d315665cd6e8.dec7fe58a5bd4c85497225b73bde050c",
        "redirect_uri" => "https://it.gruponobe.com/",
        "client_id" => "1000.7FJQ4A2KDH9S2IJWDYL13HATQFMA2H",
        "client_secret" => "c3f1d0589803f294a7c5b27e3968ae1658927da9d7",
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
