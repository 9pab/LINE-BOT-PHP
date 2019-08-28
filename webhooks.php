<?php // webhooks.php
require "vendor/autoload.php";
require_once('vendor/linecorp/line-bot-sdk/line-bot-sdk-tiny/LINEBotTiny.php');

$access_token = 'Bv/PUIP/rMX3jysSVM2dP1KVAZDJPIa2MLngjgcXFoZ35bH/h1vJiQdx0ZIrhsNqXR+XwnlDxxU1R9SSbKVSQFbKj03ZGEnRmakhwQw7qbSzyOqMkzrRuK9rH4/t82AY8ukObORKpvQCdLTXVtJWzQdB04t89/1O/w1cDnyilFU=';
$pushID = 'U3892909119edd655235f467088f954f6';

$arrayHeader = array();
$arrayHeader[] = "Content-Type: application/json";
$arrayHeader[] = "Authorization: Bearer {$access_token}";

// Get POST body content
$content = file_get_contents('php://input');
// Parse JSON
$events = json_decode($content, true);
// Validate parsed JSON data
if (!is_null($events['events'])) {
	// Loop through each event
	foreach ($events['events'] as $event) {
			$UID = $event['source']['userId'];
			$replyToken = $event['replyToken'];
			$arrayPostData['replyToken'] = $replyToken;

			
		// Reply only when message sent is in 'text' format
		if ($event['type'] == 'message' && $event['message']['type'] == 'text') {
			$arrayPostData['messages'][0]['type'] = "text";
			$arrayPostData['messages'][0]['text'] = "Hello\n".$UID."\n"."replyToken\n".$replyToken;
			pushMsg($arrayHeader,$arrayPostData);
			
		} else {
			// Build message to reply back when is not 'text' format
			$arrayPostData['messages'][0]['type'] = "text";
			$arrayPostData['messages'][0]['text'] = "กรุณาส่งเป็นข้อความเท่านั้น";
			pushMsg($arrayHeader,$arrayPostData);
		}
	}
}

function pushMsg($arrayHeader,$arrayPostData){
	$strUrl = "https://api.line.me/v2/bot/message/reply";
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
