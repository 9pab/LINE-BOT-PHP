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

/*
//Messages
$arrayPostData['to'] = $pushID;
$arrayPostData['messages'][0]['type'] = "text";
$arrayPostData['messages'][0]['text'] = $mesg0;
$arrayPostData['messages'][1]['type'] = "sticker";
$arrayPostData['messages'][1]['packageId'] = "2";
$arrayPostData['messages'][1]['stickerId'] = "34";
$arrayPostData['messages'][2]['type'] = "flex";
$arrayPostData['messages'][2]['altText'] = "Fund Portfolio";
$arrayPostData['messages'][2]['contents']['type'] = "bouble:";
$arrayPostData['messages'][2]['contents']['body']['type'] = "box";
$arrayPostData['messages'][2]['contents']['body']['layout'] = "vertical";
$arrayPostData['messages'][2]['contents']['body']['contents'][0]['type'] = "text";
$arrayPostData['messages'][2]['contents']['body']['contents'][0]['text'] = "Hello 1";
$arrayPostData['messages'][2]['contents']['body']['contents'][1]['type'] = "text";
$arrayPostData['messages'][2]['contents']['body']['contents'][1]['text'] = "Hello 2";
*/

$arrayPostData = array(
	'to' => $pushID,
	'messages' => array(
		0 => array(
			"type" => "text",
			"text" => "Test flex message",
		),
		1 => array (
			'type' => 'flex',
			'altText' => 'this is a flex message',
			'contents' => 
			array (
			  'type' => 'bubble',
			  'body' => 
			  array (
				'type' => 'box',
				'layout' => 'vertical',
				'contents' => 
				array (
				  0 => 
				  array (
					'type' => 'text',
					'text' => 'hello',
				  ),
				  1 => 
				  array (
					'type' => 'text',
					'text' => 'world',
				  ),
				),
			  ),
			),
		),
	),
);

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
