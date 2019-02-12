<?php
// This sample uses the Apache HTTP client from HTTP Components (http://hc.apache.org/httpcomponents-client-ga/)
require_once 'HTTP/Request2.php';

$request = new Http_Request2('https://api.sec.or.th/FundDailyInfo/M0607_2547/dailynav/2019-02-11');
$url = $request->getUrl();

$headers = array(
    // Request headers
    'Ocp-Apim-Subscription-Key' => 'f68ef6fd0b4a4ce196d6255bd9a96ce4',
);

$request->setHeader($headers);

$parameters = array(
    // Request parameters
);

$url->setQueryVariables($parameters);

$request->setMethod(HTTP_Request2::METHOD_GET);

// Request body
$request->setBody("{body}");

try
{
    $response = $request->send();
    echo $response->getBody();
}
catch (HttpException $ex)
{
    echo $ex;
}

?>
