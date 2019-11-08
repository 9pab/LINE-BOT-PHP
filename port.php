<?php
$time = time();
$date = date('Y-m-d', $time);

$port = array(
    0 => array(
        'name' => 'KFLTF50',
        'id' => 'M0712_2547',
        'unit' => 1584.1752,
        'cost' => 55016.79,
    ),
    1 => array(
        'name' => 'KFLTFAST-D',
        'id' => 'M0535_2559',
        'unit' => 1322.1850,
        'cost' => 15000.00,
    ),
    2 => array(
        'name' => 'KFLTFDIV',
        'id' => 'M0607_2547',
        'unit' => 7373.6545,
        'cost' => 195524.29,
    ),
    3 => array(
        'name' => 'KFLTFEQ70D',
        'id' => 'M0357_2549',
        'unit' => 9122.5557,
        'cost' => 146000.00,
    ),
    4 => array(
        'name' => 'KFLTFSTARD',
        'id' => 'M0475_2560',
        'unit' => 3460.4705,
        'cost' => 33000.00,
    ),
    5 => array(
        'name' => 'KFSDIV',
        'id' => 'M0081_2550',
        'unit' => 5304.8621,
        'cost' => 58066.44,
    ),
    6 => array(
        'name' => 'KFSMART',
        'id' => 'M0167_2559',
        'unit' => 9684.9249,
        'cost' => 99749.22,
    ),
    7 => array(
        'name' => 'KTSTPLUS-A',
        'id' => 'M0304_2559',
        'unit' => 2897.6698,
        'cost' => 30000.00,
    ),
);


//Check response
do {
    $url = 'https://api.sec.or.th/FundDailyInfo/M0712_2547/dailynav/' . $date;
    $access_token = 'f68ef6fd0b4a4ce196d6255bd9a96ce4';
    $headers = array('Ocp-Apim-Subscription-Key:' . $access_token);

    $handle = curl_init($url);
    curl_setopt($handle, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);
    $response = curl_exec($handle);
    $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
    curl_close($handle);
    if ($httpCode == 204) {
        $time = $time - (24 * 60 * 60);
        $date = date('Y-m-d', $time);
    }
} while ($httpCode != 200);


//Loop to get current value
foreach ($port as $i => $v) {
    $url = 'https://api.sec.or.th/FundDailyInfo/' . $v['id'] . '/dailynav/' . $date;
    $access_token = 'f68ef6fd0b4a4ce196d6255bd9a96ce4';
    $headers = array('Ocp-Apim-Subscription-Key:' . $access_token);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

    $result = curl_exec($ch);
    curl_close($ch);

    $data = json_decode($result,true);
    $v['last_value'] = $v['unit']*$data['last_val'];
    $v['previous_value'] = $v['unit']*$data['previous_val'];
    $total_cost = $total_cost + $v['cost'];
    $total_last_value = $total_last_value + $v['last_value'];
    $total_previous_value = $total_previous_value + $v['previous_value'];
}

//Show result
print $total_cost . "<Br>";
print $total_last_value . "<Br>";
print $total_previous_value . "<Br>";

/*
?>