<?php
/**
 * My required scripts
 */
require_once("php/config/config.php");
require_once("php/functions.php");

/**
 * Initialize some variables
 */
$response           = null;
$error_message      = "";
$info_message       = "";
$_channel_id        = null;
$_access_token      = null;
$playlist           = array();
$_playlist_id       = false;
$playlist_videos    = array();

/**
 * Auth proccess
 * Check if "code" parameter exists in the URL and generate an access token from it
 */
include("php/auth.script.php");

/**
 * Get channel data proccess
 * From the access token try to obtain the user's channel info
 */
include("php/get_channel_info.script.php");

/**
 * Get playlists proccess
 */
include("php/get_playlists.script.php");

/**
 * Get playlist videos proccess
 * I only execute it if playlist id parameter id received
 */
if(isset($_GET["playlist_id"])){
    // Initialize temporary videos array on session
    unset($_SESSION["tmp_playlist_videos"]);

    $_playlist_id = $_GET["playlist_id"];
    include("php/get_playlist_videos.script.php");

    if(empty($playlist_videos)){
        $info_message .= "No videos found for the playlist " . $_playlist_id . "<br>";
    }

    if(isset($tmp_playlist_videos) && !empty($tmp_playlist_videos)){
        $_SESSION["tmp_playlist_videos"] = json_encode($tmp_playlist_videos);
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Youtube Playlist Download</title>

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.7.1/css/bulma.min.css">
        <script defer src="https://use.fontawesome.com/releases/v5.0.7/js/all.js"></script>
    </head>
    <body>
        <section class="section">
            <div class="container">
                <h1 class="title">Hello World!</h1>
                <p class="subtitle">Playing with <strong>Youtube</strong>!</p>

                <!-- Error messages -->
                <?php if(!empty($error_message)): ?>
                <div class="notification is-danger"><?php echo $error_message; ?></div>
                <?php endif; ?>

                <!-- Info messages -->
                <?php if(!empty($info_message)): ?>
                <div class="notification is-warning"><?php echo $info_message; ?></div>
                <?php endif; ?>

                <!-- Form -->
                <form method="post" action="<?php echo TY_CALLBACK_URL; ?>">
                    <div class="field">
                        <div class="control">
                            <input class="input" type="text" placeholder="Access token" value="<?php echo @$_access_token; ?>" name="access_token" required>
                        </div>
                        <p class="help">Insert manually or get from "Authorize" button</p>
                        <p class="help is-danger is-hidden">This field is required</p>
                    </div>
                    <div class="field has-addons">
                        <div class="control is-expanded">
                            <input class="input" type="text" placeholder="Channel ID" name="channel_id" value="<?php echo @$_channel_id; ?>" required>
                            <p class="help">Go to your Youtube channel and copy the ID from URL or get from "Authorize" button</p>
                            <p class="help is-danger is-hidden">This field is required</p>
                        </div>
                        <div class="control">
                            <a href="https://www.youtube.com" class="button is-info" target="_blank">Go to Youtube</a>                        
                        </div>
                    </div>
                    <div class="control has-text-right">
                        <a class="button is-primary" href="https://accounts.google.com/o/oauth2/auth?client_id=<?php echo TY_CLIENT_ID; ?>&redirect_uri=<?php echo urlencode(TY_CALLBACK_URL); ?>&scope=https://www.googleapis.com/auth/youtube&response_type=code">Authorize</a>
                        <button type="submit" class="button is-dark">Submit</button>
                    </div>
                </form>
                <hr class="hr">

                <!-- Playlists -->
                <p class="subtitle"><strong>Playlists</strong></p>
                <ul>
                    <table class="table is-hoverable is-fullwidth">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Playlist options</th>
                                <!-- If videos found... -->
                                <?php if($_playlist_id && !empty($playlist_videos)): ?>
                                <th>Download options</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($playlist as $k => $v): ?>
                            <tr>
                                <th>
                                    <?php echo $v["snippet"]["title"]; ?>
                                </th>
                                <td>
                                    <a href="<?php echo TY_CALLBACK_URL; ?>?playlist_id=<?php echo $v["id"]; ?>" class="button">                                        
                                        <span class="icon">
                                            <i class="fas fa-list"></i>
                                        </span>
                                        <span>Get list</span>
                                    </a>
                                    <a href="https://www.youtube.com/playlist?list=<?php echo $v["id"]; ?>" target="_blank" class="button">                                        
                                        <span class="icon">
                                            <i class="fab fa-youtube"></i>
                                        </span>
                                        <span>Youtube</span>
                                    </a>
                                </td>
                                <!-- If videos found... -->
                                <?php if($_playlist_id && !empty($playlist_videos)): ?>
                                <td>
                                    <a href="#" class="button is-link">                                        
                                        <span class="icon">
                                            <i class="fas fa-download"></i>
                                        </span>
                                        <span>MP3</span>
                                    </a>
                                    <a href="#" class="button is-link">                                        
                                        <span class="icon">
                                            <i class="fas fa-download"></i>
                                        </span>
                                        <span>Videos</span>
                                    </a>
                                </td>
                                <?php endif; ?>                                
                            </tr>                        
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </ul>
                <hr class="hr">

                <div class="has-text-right" role="navigation">
                    <?php if(isset($playlist_response["prevPageToken"])): ?>
                    <a href="<?php echo TY_CALLBACK_URL; ?>?page_token=<?php echo $playlist_response["prevPageToken"]; ?>" class="pagination-previous">Previous</a>
                    <?php endif; ?>
                    <?php if(isset($playlist_response["nextPageToken"])): ?>
                    <a href="<?php echo TY_CALLBACK_URL; ?>?page_token=<?php echo $playlist_response["nextPageToken"]; ?>" class="pagination-next">Next page</a>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <script src="js/script.js"></script>
    </body>
</html>