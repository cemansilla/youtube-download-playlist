<?php
// Send access token in header
$request_headers = array(
    "Authorization: Bearer " . $_access_token
);

// If pagination
$query_page_token = "";
if(isset($_GET["page_token"])){
    $query_page_token = "&pageToken=".$_GET["page_token"];
}

$playlist_response = makeGETRequest("https://www.googleapis.com/youtube/v3/playlists?part=snippet&channelId=".$_channel_id."&key=".$_access_token."&maxResults=".TY_RPP.$query_page_token, $request_headers);
//d($response);

if(!isset($playlist_response["error"])){
    $playlist = $playlist_response["items"];
}
?>