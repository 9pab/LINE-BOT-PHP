<?php
require "vendor/autoload.php";

$url = 'http://www.altisclub.com/myport.php';
$handle = curl_init($url);
curl_setopt($handle,  CURLOPT_RETURNTRANSFER, TRUE);
$resp = curl_exec($handle);
curl_close($handle);

$text = $resp;

$access_token = 'Bv/PUIP/rMX3jysSVM2dP1KVAZDJPIa2MLngjgcXFoZ35bH/h1vJiQdx0ZIrhsNqXR+XwnlDxxU1R9SSbKVSQFbKj03ZGEnRmakhwQw7qbSzyOqMkzrRuK9rH4/t82AY8ukObORKpvQCdLTXVtJWzQdB04t89/1O/w1cDnyilFU=';
$channelSecret = 'd004cf371d24fc6b9a6941c8b1103860';
$pushID = 'U3892909119edd655235f467088f954f6';
$httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient($access_token);
$bot = new \LINE\LINEBot($httpClient, ['channelSecret' => $channelSecret]);

$textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($text);
$response = $bot->pushMessage($pushID, $textMessageBuilder);
echo $response->getHTTPStatus() . ' ' . $response->getRawBody();
?>
