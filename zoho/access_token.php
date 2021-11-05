<?php

//fuentes https://www.youtube.com/watch?v=ToCP_MwORAw

function generar_access_tokens()
{
    $post = [
        "refresh_token" => "1000.9cd8b17b6d31e76ac381d92ff630f995.53baee2923e34df8d5edf151e966bd45",
        "client_id" => "1000.7FJQ4A2KDH9S2IJWDYL13HATQFMA2H",
        "client_secret" => "c3f1d0589803f294a7c5b27e3968ae1658927da9d7",
        "grant_type" => "refresh_token"
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
generar_access_tokens();
