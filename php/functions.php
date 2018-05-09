<?php
/**
 * Prints pre-formatted element for debugging
 * 
 * @param array
 * @return void
 */
function d($el){ echo "<pre>"; print_r($el); echo "</pre>"; }

/**
 * Example of a POST request. Feel free to make it at your own way.
 * @param   string    $url
 * @param   array     $fields
 * @return  array
 */
function makePOSTRequest($url, $fields){
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($fields));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($curl);
    curl_close($curl);

    return json_decode($result, true);
}

/**
 * Example of a GET request. Feel free to make it at your own way.
 * @param   string    $url
 * @param   array     $fields
 * @param   mixed     $headers
 * @return  array
 */
function makeGETRequest($url, $headers = false){
    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");

    if($headers){        
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    }    

    $result = curl_exec($ch);
    curl_close ($ch);

    return json_decode($result, true);
}
?>