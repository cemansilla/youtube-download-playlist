<?php
// Required
set_time_limit(0);

// Some harcoded data to test
// TODO: make dynamic
$vid = "Sxxe_liDRww";       // The youtube video ID
$extension = "mp4";         // The format of the video. mp4 | 3gp

// Decode the data
parse_str(file_get_contents("http://youtube.com/get_video_info?video_id=".$vid), $info);

// The video's location info
$streams = $info['url_encoded_fmt_stream_map'];
$streams = explode(',', $streams);

foreach($streams as $stream){
    // Decode the stream
    parse_str($stream, $data);

    if(stripos($data['type'], $extension) !== false){
        // If we've found the right stream that matches with the extension...

        // The video on Youtube
        $video = fopen($data['url'], 'r');

        // The location where we'll save the file
        // TODO: work with subfolders, created on the fly based on playlist's name
        $file = fopen('video.mp4', 'w');

        // Copy it to the file
        stream_copy_to_stream($video,$file);

        // Close handlers
        fclose($video);
        fclose($file);

        // Break the loop
        break;
    }
}
?>