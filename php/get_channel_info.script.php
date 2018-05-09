<?php
// Validations on required data
if(isset($_POST["access_token"]) && isset($_POST["channel_id"])){
    // Capture data from form
    $_channel_id    = $_POST["channel_id"];
    $_access_token  = $_POST["access_token"];
}else if(isset($_SESSION["yt_access_token"])){
    // Capture data from session     
    $_access_token = $_SESSION["yt_access_token"];

    if(isset($_SESSION["yt_channel_id"])){
        $_channel_id = $_SESSION["yt_channel_id"];
    }else{
        // Request channel from Youtube API
        $request_headers = array(
            "Authorization: Bearer " . $_access_token
        );
        $response = makeGETRequest("https://www.googleapis.com/youtube/v3/channels?part=id&mine=true", $request_headers);
    
        if(!isset($response["error"])){
            // May we need to make some validations
            $_channel_id = $response["items"][0]["id"];
        }else{
            $error_message .= "Channel ID error :: " . $response["error"]["message"] . "<br />";
        }
    }
}
?>