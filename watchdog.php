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
				case "1" :
					$url = 'http://ocb1.herokuapp.com/myporttobot.php';
					$handle = curl_init($url);
					curl_setopt($handle,  CURLOPT_RETURNTRANSFER, TRUE);
					$resp = curl_exec($handle);
					curl_close($handle);
					
					$arrayPostData['messages'][0]['type'] = "text";
					$arrayPostData['messages'][0]['text'] = $resp;
					break;
				case "2" :
					$arrayPostData['messages'][0]['type'] = "text";
					$arrayPostData['messages'][0]['text'] = $content;
					$arrayPostData['messages'][1]['type'] = "text";
					$arrayPostData['messages'][1]['text'] = "You select #2";
					break;
				case "3" :
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
					$arrayPostData['messages'][0]['text'] = "กด 1 ดูพอร์ตการลงทุน\nกด 2 แสดง content\nกด 3 Flex message giga";
			}
		} else {
			// Build message to reply back when is not 'text' format
			$arrayPostData['messages'][0]['type'] = "text";
			$arrayPostData['messages'][0]['text'] = $content."\n\nกรุณาส่งเป็นข้อความเท่านั้น";
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
