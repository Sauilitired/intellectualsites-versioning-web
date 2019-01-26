<?php

/**
 * GitHub requires an OAuth 2 token to give access to their api.
 */
$github_api_token = ""; // EDIT

if (!isset($_GET["revision"]) {
    http_response_code(400);
    exit("No revision sha provided");
}

/**
 * The query argument holding the short commit sha (the first 7 letters).
 */
$revision = $_GET["revision"];

/**
*  The repository that we are working with, defaults to FastASyncWorldEdit-1.13
*/
$repository = "FastAsyncWorldEdit-1.13";
if (isset($_GET["repository"]) {
    $repository = $_GET["repository"];   
}
    
/**
 * The base URL for our compare requests.
 */
$compareUrl = "https://api.github.com/repos/IntellectualSites/$repository/compare/$revision...master";

$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => $compareUrl,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => array(
        "cache-control: no-cache",
        "Authorization: Bearer $github_api_token",
        "User-Agent:  $repository
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
    exit("Please report this issue to an IntellectualSites developer. cUrl error #:" . $err);
} else {
    $json = json_decode($response, true);
    
    // get the commits section
    $commits = $json["commits"];

    // echo each commit message
    foreach($commits as $commit) {
        echo($commit["commit"]["message"] . "\n");
    }

}
?>
