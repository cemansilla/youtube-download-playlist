<?php
// Send access token in header
$request_headers = array(
    "Authorization: Bearer " . $_access_token
);

// Initialize for pagination
$query_page_token_videos = "";

// Initialize temporary videos array
$tmp_playlist_videos = array();

do{
    // First request
    $playlist_videos_response = makeGETRequest("https://www.googleapis.com/youtube/v3/playlistItems?part=id,contentDetails&playlistId=".$_playlist_id."&key=".$_access_token."&maxResults=2".$query_page_token_videos, $request_headers);

    if(!isset($playlist_videos_response["error"])){

        // If no error, add videos to playlist videos array
        foreach($playlist_videos_response["items"] as $kpv => $vpv){
            $tmp_playlist_videos[] = $vpv["contentDetails"]["videoId"];
            array_push($playlist_videos, $vpv);
        }
        
        if(isset($playlist_videos_response["nextPageToken"])){
            // If next page token exists
            $query_page_token_videos = "&pageToken=" . $playlist_videos_response["nextPageToken"];
            $more = true;
        }else{
            // If next page token doesn't exists break the loop
            $more = false;
        }
    }else{
        // If error, set message and break the loop
        $error_message .= "Get playlist videos error :: " . $playlist_videos_response["error"]["message"] . "<br />";
        
        break;
    }
}while($more);
?>