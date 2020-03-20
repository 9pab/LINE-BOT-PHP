<?php
$time = time();
$date = date('Y-m-d',$time);

//Set port
$MyPort = array(
  'KFLTF50' => array('M0712_2547', 1638.7239, 60000.00),
  'KFLTFAST-D' => array('M0535_2559', 1322.1850, 15000.00),
  'KFLTFDIV' => array('M0607_2547', 8585.7650, 217800.00),
  'KFLTFEQ70D' => array('M0357_2549', 9131.9018, 144000.00),
  'KFLTFSTARD' => array('M0475_2560', 3460.4705, 33000.00),
  'KFSDIV' => array('M0081_2550' ,5304.8621, 58066.44),
);

//Check response
do {
  $url = 'https://api.sec.or.th/FundDailyInfo/M0712_2547/dailynav/' . $date;
  $access_token = 'f68ef6fd0b4a4ce196d6255bd9a96ce4';
  $headers = array('Ocp-Apim-Subscription-Key:'.$access_token,);
  $handle = curl_init($url);
  curl_setopt($handle, CURLOPT_CUSTOMREQUEST, "GET");
  curl_setopt($handle,  CURLOPT_RETURNTRANSFER, TRUE);
  curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);
  $response = curl_exec($handle);
  $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
  curl_close($handle);
  if ($httpCode == 204) {
    $time = $time-(24*60*60);
    $date = date('Y-m-d',$time);
  }
} while ($httpCode != 200);

foreach($MyPort as $fund => $f_nav) {
  $url = 'https://api.sec.or.th/FundDailyInfo/' . $f_nav[0] . '/dailynav/' . $date;
  $access_token = 'f68ef6fd0b4a4ce196d6255bd9a96ce4';
  $headers = array('Ocp-Apim-Subscription-Key:'.$access_token,);
  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
  $result = curl_exec($ch);
  curl_close($ch);
  $nav = json_decode($result);
  $amount = $nav->{'last_val'}*$f_nav[1];
  $last_amount = $nav->{'previous_val'}*$f_nav[1];
  $balance = number_format(($amount-$f_nav[2]),2);
  $ratio = number_format(($amount-$f_nav[2])/$f_nav[2]*100,2);
  $compare = number_format(($nav->{'last_val'}-$nav->{'previous_val'})/$nav->{'previous_val'}*100,2);
  if ($balance > 0) {$balance = '+'.$balance; $ratio = '+'.$ratio;}

  $GT = $GT+$amount;
  $last_GT = $last_GT+$last_amount;
  $cost = $cost+$f_nav[2];
}
$change = number_format($GT-$last_GT,2);
$r_change = number_format(($GT-$last_GT)/$last_GT*100,2);
if ($change > 0) {$change = '+'.$change;$r_change = '+'.$r_change;}

$data = array("value1" => $date, "value2" => $GT, "value3" => $cost);
$data_string = json_encode($data);

$ch = curl_init('https://maker.ifttt.com/trigger/NAV/with/key/bLkwIlTssz5qlFsS56rgws');
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Content-Length: ' . strlen($data_string)));
$result = curl_exec($ch);
?>
