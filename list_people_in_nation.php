<?php

// An example of calling the Nationbuilder API to get the first X people in a nation.
// Robert Price - May 2017

$access_token = 'your access token goes here';
$nation = 'name of your nation goes here';
$limit = 10;
$url = "https://$nation.nationbuilder.com/api/v1/people?access_token=$access_token&limit=$limit";

$context = stream_context_create(array(
    'http' => array(
        'method' => 'GET',
        'header' => "Accept: application/json\r\n"
    ),
    'ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false
    )
));

$rawdata = file_get_contents($url, false, $context);
if ($rawdata === false) {
    exit("Unable to download data from $url");
}

$data = json_decode($rawdata, true);
if (JSON_ERROR_NONE !== json_last_error()) {
    exit("Failed to parse json: " . json_last_error_msg());
}

echo "First $limit people in $nation\n";
foreach($data['results'] as $person) {
    echo ' ' . $person['first_name'] . ' ' . $person['last_name'] . "\n";
}
