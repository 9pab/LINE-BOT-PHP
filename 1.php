<?php
$arrayPostData['messages'][0]['type'] = "text";
$arrayPostData['messages'][0]['text'] = "Flex";

$arrayPostData['messages'][1]['type'] = "flex";
$arrayPostData['messages'][1]['altText'] = "Fund Portfolio";
$arrayPostData['messages'][1]['contents']['type'] = "bubble";
$arrayPostData['messages'][1]['contents']['size'] = "giga";
$arrayPostData['messages'][1]['contents']['body']['type'] = "box";
$arrayPostData['messages'][1]['contents']['body']['layout'] = "vertical";
$arrayPostData['messages'][1]['contents']['body']['contents'][0]['type'] = "text";
$arrayPostData['messages'][1]['contents']['body']['contents'][0]['text'] = "Hello 1";
$arrayPostData['messages'][1]['contents']['body']['contents'][1]['type'] = "text";
$arrayPostData['messages'][1]['contents']['body']['contents'][1]['text'] = "Hello 2";

$txt = '{"type": "flex","altText": "Flex Message","contents": {"type": "bubble","size": "giga","body": {"type": "box","contents": [{"type": "text","text": "World 1"},{"type": "text","text": "World 2"}]}}}';
array_push($arrayPostData['messages'],json_decode($txt, true));

var_dump(json_decode($txt,TRUE));
print "<BR>-----<BR>";
var_dump($arrayPostData);
