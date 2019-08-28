<?php
   $access_token = 'Bv/PUIP/rMX3jysSVM2dP1KVAZDJPIa2MLngjgcXFoZ35bH/h1vJiQdx0ZIrhsNqXR+XwnlDxxU1R9SSbKVSQFbKj03ZGEnRmakhwQw7qbSzyOqMkzrRuK9rH4/t82AY8ukObORKpvQCdLTXVtJWzQdB04t89/1O/w1cDnyilFU=';
   $channelSecret = 'd004cf371d24fc6b9a6941c8b1103860';
   $pushID = 'U3892909119edd655235f467088f954f6';
   
   $arrayHeader = array();
   $arrayHeader[] = "Content-Type: application/json";
   $arrayHeader[] = "Authorization: Bearer {$access_Token}";

   #ตัวอย่าง Message Type "Text + Sticker"
   $arrayPostData['to'] = $pushID;
   $arrayPostData['messages'][0]['type'] = "text";
   $arrayPostData['messages'][0]['text'] = "สวัสดีจ้าาา";
   $arrayPostData['messages'][1]['type'] = "sticker";
   $arrayPostData['messages'][1]['packageId'] = "2";
   $arrayPostData['messages'][1]['stickerId'] = "34";
   pushMsg($arrayHeader,$arrayPostData);
   echo "test";

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
