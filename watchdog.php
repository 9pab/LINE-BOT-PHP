<?php // webhooks.php

$access_token = 'rf5P0JaObU4jOLTNFEeST6eeSSMs9gwLfcAu6Pg61hn/8CnPkaC1mlpOn0uao6lATZ7c5Ta7BiftI/tFY6DmW40J3qkD0ZcHJiWjzDOz1E/D3bjeolHIvc7mKsbQY7R+/A8GUr8/O76K9JH8kvFJOAdB04t89/1O/w1cDnyilFU=';

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
			$command = $event['message']['text'];
			
			switch ($command) {
				case "เปิดไฟ" :
					$url = 'https://maker.ifttt.com/trigger/GarageON/with/key/bLkwIlTssz5qlFsS56rgws';
					$handle = curl_init($url);
					curl_setopt($handle,  CURLOPT_RETURNTRANSFER, TRUE);
					$resp = curl_exec($handle);
					curl_close($handle);

					$arrayPostData['messages'][0]['type'] = "text";
					$arrayPostData['messages'][0]['text'] = "เปิดไฟแล้วจ้า 💡";
					$arrayPostData['messages'][1]['type'] = "sticker";
					$arrayPostData['messages'][1]['packageId'] = 11537;
					$arrayPostData['messages'][1]['stickerId'] = 52002740;
				break;

				case "ปิดไฟ" :
					$url = 'https://maker.ifttt.com/trigger/GarageOFF/with/key/bLkwIlTssz5qlFsS56rgws';
					$handle = curl_init($url);
					curl_setopt($handle,  CURLOPT_RETURNTRANSFER, TRUE);
					$resp = curl_exec($handle);
					curl_close($handle);

					$arrayPostData['messages'][0]['type'] = "text";
					$arrayPostData['messages'][0]['text'] = "ปิดไฟให้แล้วจ้า";
					$arrayPostData['messages'][1]['type'] = "sticker";
					$arrayPostData['messages'][1]['packageId'] = 11537;
					$arrayPostData['messages'][1]['stickerId'] = 52002751;
				break;

				case "ฝุ่น" :
					$url = 'https://api.airvisual.com/v2/nearest_city?key=1c65f3ba-d673-424e-8bd0-bccda70491cf&lat=13.7863959&lon=100.5697047';
					$handle = curl_init($url);
					curl_setopt($handle,  CURLOPT_RETURNTRANSFER, TRUE);
					$resp = curl_exec($handle);
					curl_close($handle);
					$AQIdata = json_decode($resp,true);
					$pm25 = $AQIdata[data][current][pollution][aqius];

					$arrayPostData['messages'][0]['type'] = "text";
					$arrayPostData['messages'][0]['text'] = "ค่าฝุ่นละอองที่สุทธิสารขณะนี้ ". $pm25;

					$url = 'https://api.airvisual.com/v2/nearest_city?key=1c65f3ba-d673-424e-8bd0-bccda70491cf&lat=13.6922608&lon=100.532048';
					$handle = curl_init($url);
					curl_setopt($handle,  CURLOPT_RETURNTRANSFER, TRUE);
					$resp = curl_exec($handle);
					curl_close($handle);
					$AQIdata = json_decode($resp,true);
					$pm25 = $AQIdata[data][current][pollution][aqius];

					$arrayPostData['messages'][1]['type'] = "text";
					$arrayPostData['messages'][1]['text'] = "ค่าฝุ่นละอองที่สาธุฯขณะนี้ ". $pm25;

				break;

				case "testFlex" :
					$arrayPostData['messages'][0]['type'] = "text";
					$arrayPostData['messages'][0]['text'] = "Flex";

					$arrayPostData['messages'][1]['type'] = "flex";
					$arrayPostData['messages'][1]['altText'] = "Fund Portfolio";
					$arrayPostData['messages'][1]['contents']['type'] = "bubble";
					$arrayPostData['messages'][1]['contents']['size'] = "giga";
					$arrayPostData['messages'][1]['contents']['header']['type'] = "box";
					$arrayPostData['messages'][1]['contents']['header']['layout'] = "vertical";
					$arrayPostData['messages'][1]['contents']['header']['contents'][0]['type'] = "text";
					$arrayPostData['messages'][1]['contents']['header']['contents'][0]['text'] = "Portfolio";
					$arrayPostData['messages'][1]['contents']['body']['type'] = "box";
					$arrayPostData['messages'][1]['contents']['body']['layout'] = "horizontal";
					$arrayPostData['messages'][1]['contents']['body']['contents'][0]['type'] = "text";
					$arrayPostData['messages'][1]['contents']['body']['contents'][0]['text'] = "Hello 1";
					$arrayPostData['messages'][1]['contents']['body']['contents'][1]['type'] = "text";
					$arrayPostData['messages'][1]['contents']['body']['contents'][1]['text'] = "Hello 2";

					$txt = '{"type": "flex","altText": "Flex Message","contents": {"type": "bubble","size": "giga","body": {"type": "box","layout": "vertical","contents": [{"type": "text","text": "World 1"},{"type": "text","text": "World 2"}]}}}';
					array_push($arrayPostData['messages'],json_decode($txt, TRUE));
				break;

				default :
					$arrayPostData['messages'][0]['type'] = "text";
					$arrayPostData['messages'][0]['text'] = "\"เปิดไฟ\" หรือ \"ปิดไฟ\"";
			}
		} else {
			// Build message to reply back when is not 'text' format
			$arrayPostData['messages'][0]['type'] = "text";
			$arrayPostData['messages'][0]['text'] = "กรุณาส่งเป็นข้อความเท่านั้น";
		}
	pushMsg($arrayHeader,$arrayPostData);
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
