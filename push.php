<?php
$access_token = 'Bv/PUIP/rMX3jysSVM2dP1KVAZDJPIa2MLngjgcXFoZ35bH/h1vJiQdx0ZIrhsNqXR+XwnlDxxU1R9SSbKVSQFbKj03ZGEnRmakhwQw7qbSzyOqMkzrRuK9rH4/t82AY8ukObORKpvQCdLTXVtJWzQdB04t89/1O/w1cDnyilFU=';
$pushID = 'U3892909119edd655235f467088f954f6';

$arrayHeader = array();
$arrayHeader[] = "Content-Type: application/json";
$arrayHeader[] = "Authorization: Bearer {$access_token}";

//Message0
$url = 'http://ocb1.herokuapp.com/myporttobot.php';
$handle = curl_init($url);
curl_setopt($handle,  CURLOPT_RETURNTRANSFER, TRUE);
$mesg0 = curl_exec($handle);
curl_close($handle);

//Message1

//Messages
$arrayPostData['to'] = $pushID;
$arrayPostData['messages'][0]['type'] = "text";
$arrayPostData['messages'][0]['text'] = $mesg0;
$arrayPostData['messages'][1]['type'] = "image";
$arrayPostData['messages'][1]['originalContentUrl'] = "https://hcti.io/v1/image/cf816d13-1ba9-411a-b500-008c6db882a5.png";
$arrayPostData['messages'][1]['previewImageUrl'] = "https://hcti.io/v1/image/cf816d13-1ba9-411a-b500-008c6db882a5.png";
pushMsg($arrayHeader,$arrayPostData);

function pushMsg($arrayHeader,$arrayPostData){
	$strUrl = "https://api.line.me/v2/bot/message/push";
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,$strUrl);
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $arrayHeader);
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($arrayPostData));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	$result = curl_exec($ch);
	curl_close ($ch);
}
exit;
?>
