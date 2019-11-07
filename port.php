<?php
$time = time();
$date = date('Y-m-d', $time);

//Set port
$MyPort = array(
    'KFLTF50' => array('M0712_2547', 1584.1752, 55016.7900),
    'KFLTFAST-D' => array('M0535_2559', 1322.1850, 15000.0000),
    'KFLTFDIV' => array('M0607_2547', 7373.6545, 195524.2900),
    'KFLTFEQ70D' => array('M0357_2549', 9122.5557, 146000.0000),
    'KFLTFSTARD' => array('M0475_2560', 3460.4705, 33000.0000),
    'KFSDIV' => array('M0081_2550', 5304.8621, 58066.4400),
    'KFSMART' => array('M0167_2559', 9684.9249, 99749.2200),
    'KTSTPLUS-A' => array('M0304_2559', 2897.6698, 30000.0000),
);

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

    $data = json_decode($result);
    print $i . " = " . $v['name'] . "   NAV = ". $data['last_val'] . "<br>";
}

//Show result
//print json_encode($port);

/*
foreach ($MyPort as $fund => $f_nav) {

    $url = 'https://api.sec.or.th/FundDailyInfo/' . $f_nav[0] . '/dailynav/' . $date;
    $access_token = 'f68ef6fd0b4a4ce196d6255bd9a96ce4';
    $headers = array('Ocp-Apim-Subscription-Key:' . $access_token);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

    $result = curl_exec($ch);
    curl_close($ch);

    $nav = json_decode($result);
    $amount = $nav->{'last_val'} * $f_nav[1];
    $last_amount = $nav->{'previous_val'} * $f_nav[1];
    $balance = number_format(($amount - $f_nav[2]), 2);
    $ratio = number_format(($amount - $f_nav[2]) / $f_nav[2] * 100, 2);
    $compare = number_format(($nav->{'last_val'}-$nav->{'previous_val'}) / $nav->{'previous_val'} * 100, 2);
    if ($balance > 0) {$balance = '+' . $balance;
        $ratio = '+' . $ratio;}
    if ($compare > 0) {
        $compare = '+' . $compare;
        $symbol = "🔵";
    } else {
        $symbol = "🔴";
    }

    print $symbol . " " . $fund . " [" . $compare . "%]\n";
    print "     " . $balance . " [" . $ratio . "%]\n";
    print "────────────\n";

    $GT = $GT + $amount;
    $last_GT = $last_GT + $last_amount;
    $cost = $cost + $f_nav[2];
}

$change = number_format($GT - $last_GT, 2);
$r_change = number_format(($GT - $last_GT) / $last_GT * 100, 2);
if ($change > 0) {$change = '+' . $change;
    $r_change = '+' . $r_change;}

print "Port summary\n";
print "   Cost : " . number_format($cost, 2) . "\n";
print "   Value : " . number_format($GT, 2) . "\n";
print "   Chg : " . $change . " [" . $r_change . "%]\n";
print "   P/L : " . number_format($GT - $cost, 2) . " [" . number_format(($GT - $cost) / $cost * 100, 2) . "%]\n";
print "═════════════\n";
*/
?>