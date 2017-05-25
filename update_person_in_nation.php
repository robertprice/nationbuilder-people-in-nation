<?php

// Example showing how to add or update some details of a user in NationBuilder.
// Robert Price - May 2017

$access_token = 'set access token here';
$nation = 'set nation name here';
$url = "https://$nation.nationbuilder.com/api/v1/people/push?access_token=$access_token";

// details we want to update
$data = [
    'person' => [
        'email_opt_in' => true,
        'do_not_contact' => false,
        'do_not_call' => false,
        'first_name' => 'Robert',
        'last_name' => 'Price',
        'email' => 'robert.price@email.adress',
        'home_address' => [
            'zip' => 'AB1 2CD'
        ],
        'bio' => 'Software developer - robertprice.co.uk'
    ]
];
$json_data = json_encode($data);

$context = stream_context_create([
    'http' => [
        'method' => 'PUT',
        'header' => "Content-type: application/json\r\n" .
                    "Accept: application/json\r\n" .
                    "Connection: close\r\n" .
                    "Content-length: " . strlen($json_data) . "\r\n",
        'protocol_version' => 1.1,
        'content' => $json_data
    ],
    'ssl' => [
        'verify_peer' => false,
        'verify_peer_name' => false
    ]
]);

$rawdata = file_get_contents($url, false, $context);
if ($rawdata === false) {
    exit("Unable to update data at $url");
}

$data = json_decode($rawdata, true);
if (JSON_ERROR_NONE !== json_last_error()) {
    exit("Failed to parse json: " . json_last_error_msg());
}

echo "Updated details for {$data['person']['first_name']} {$data['person']['last_name']}\n";
