<?php
$time = time();
$date = date('Y-m-d',$time);

//Set port
$MyPort = array(
  'KFLTF50' => array('M0712_2547', 1584.1752, 55016.7900),
  'KFLTFAST-D' => array('M0535_2559', 1322.1850, 15000.0000),
  'KFLTFDIV' => array('M0607_2547', 7373.6545, 195524.2900),
  'KFLTFEQ70D' => array('M0357_2549', 9122.5557, 146000.0000),
  'KFLTFSTARD' => array('M0475_2560', 3460.4705, 33000.0000),
  'KFSDIV' => array('M0081_2550' ,5304.8621, 58066.4400),
  'KFSMART' => array('M0167_2559', 9684.9249, 99749.2200),
  'KTSTPLUS-A' => array('M0304_2559', 2897.6698, 30000.0000),
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

print '<link href="https://fonts.googleapis.com/css?family=Roboto:300" rel="stylesheet">';
print '<style>table, th, td {font-family: "Roboto", sans-serif; border: 0px solid white; padding:5px} tr:hover {background-color: #EEEEEE;font-weight: bold;}</style>';

print "<table cellspacing=0><tr><td colspan=7><h2>Lastest NAV date : " . $date . "</h2></td></tr>";
print "<tr bgcolor=#FFCC00><td align=center><b>Fund</b></td><td align=center width=80px><b>NAV</b></td><td align=center width=110px><b>Units</b></td><td align=center width=110px><b>Amount</b></td><td align=center width=110px><b>Cost</b></td><td align=center width=100px><b>Balance</b></td><td align=center width=80px><b>%</b></td></tr>\n";

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
  if ($amount > $f_nav[2]) {
    print "<tr><td>".$fund."</td><td align=right>".number_format($nav->{'last_val'},4)."</td><td align=right>".number_format($f_nav[1],4)."</td><td align=right><font color=#00CC00>".number_format($amount,2)."</font></td><td align=right><font color=#00CC00>".number_format($f_nav[2],2)."</font></td><td align=right><font color=#00CC00>".number_format(($amount-$f_nav[2]),2)."</font></td><td align=right><font color=#00CC00>".number_format(($amount-$f_nav[2])/$f_nav[2]*100,2)."%</font></td></tr>\n";
  } else {
     print "<tr><td>".$fund."</td><td align=right>".number_format($nav->{'last_val'},4)."</td><td align=right>".number_format($f_nav[1],4)."</td><td align=right><font color=#FF0000>".number_format($amount,2)."</font></td><td align=right><font color=#FF0000>".number_format($f_nav[2],2)."</font></td><td align=right><font color=#FF0000>".number_format(($amount-$f_nav[2]),2)."</font></td><td align=right><font color=#FF0000>".number_format(($amount-$f_nav[2])/$f_nav[2]*100,2)."%</font></td></tr>\n";
  }
  
  $GT = $GT+$amount;
  $cost = $cost+$f_nav[2];
}
print "<tr bgcolor=#CCCCCC><td colspan=3><b>Summary</b></td><td align=right><b>".number_format($GT,2)."</b></td><td align=right><b>".number_format($cost,2)."</b></td><td align=right><b>".number_format($GT-$cost,2)."</b></td><td align=right><b>".number_format(($GT-$cost)/$cost*100,2)."%</b></td></tr></table>\n";

/*
$to = "piboonsak@gmail.com";
$subject = "Port summary for ".$date;

$message = "
<html>
<head><title>HTML email</title></head>
<body>
<h2>Port summary for ".$date."</h2>
<li>Cost : ".number_format($cost,2)."</li></br>
<li>Value : ".number_format($GT,2)."</li><br>
<li>P/L : ".number_format($GT-$cost,2)." [".number_format(($GT-$cost)/$cost*100,2)."%]</li></br>
</body>
</html>
";
*/

/* Always set content-type when sending HTML email
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

// More headers
$headers .= 'From: <noreply@altisclub.com>' . "\r\n";

mail($to,$subject,$message,$headers);
*/
?>
