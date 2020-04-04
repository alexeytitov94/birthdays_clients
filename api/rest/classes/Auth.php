<?php

class Auth
{

    public function getAccessToken($request)
    {
        $ref = file_get_contents(
           'https://'.$request['PORTAL']."/oauth/token/?"
            ."client_id=app.5e29619e8134f6.36997739&"
            ."grant_type=refresh_token&"
            ."client_secret=heEizsI0e8qUCOzzTXnifuC11lhvfCskQ0uZWE95b6bT4Yb6x6&"
            ."redirect_uri=https%3A%2F%2Fsite.ru%3Aindex.php&"
            ."refresh_token=".$request["REFRESH_TOKEN"]
        );
        $ref = json_decode($ref);
        $ref = (array)$ref;
        $new_token = $ref['access_token'];
        $domain = 'https'.'://'.$ref['domain'];


        $data_query = array(
            "ACCESS_TOKEN" => $new_token,
            "DOMAIN" => $domain,
            "MEMBER_ID" => $ref['member_id'],
            "REFRESH_TOKEN" => $ref['refresh_token']
        );

        return $data_query;
    }
    
}