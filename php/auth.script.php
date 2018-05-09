<?php
// If code exists may the user authorized the permissions request
// We need to validate it y and convert into an access token
// https://developers.google.com/youtube/v3/guides/auth/server-side-web-apps?hl=es-419#Exchange_Authorization_Code
// Get token value from callback uri
$code = isset($_GET["code"]) ? $_GET["code"] : false;
if($code){
    // Some request config
    $token_renew_url            = "https://accounts.google.com/o/oauth2/token";
    $token_renew_code           = $code;
    $token_renew_client_id      = TY_CLIENT_ID;
    $token_renew_client_secret  = TY_CLIENT_SECRET;
    $token_renew_redirect_uri   = TY_CALLBACK_URL;
    $token_renew_grant_type     = "authorization_code";

    $request_body = array(
        "code"          => $token_renew_code,
        "client_id"     => $token_renew_client_id,
        "client_secret" => $token_renew_client_secret,
        "redirect_uri"  => $token_renew_redirect_uri,
        "grant_type"    => $token_renew_grant_type
    );

    // Make POST request
    $response = makePOSTRequest($token_renew_url, $request_body);

    if(!isset($response["error"])){
        if(isset($response["access_token"])){
            $_SESSION["yt_access_token"] = $response["access_token"];

            header("Location: " . TY_CALLBACK_URL);
        }
    }else{
        $error_message .= "Auth error :: " . $response["error_description"] . "<br />";
        unset($_SESSION["yt_access_token"]);
    }    
}
?>