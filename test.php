<?php
$json_string = '{"0":{"name":"KFLTF50","id":"M0712_2547","unit":1584.1752,"cost":55016.79,"last_value":60012.675431519994,"previous_value":59333.53952327999},"1":{"name":"KFLTFAST-D","id":"M0535_2559","unit":1322.185,"cost":15000,"last_value":13280.290577,"previous_value":13137.759034},"2":{"name":"KFLTFDIV","id":"M0607_2547","unit":7373.6545,"cost":195524.29,"last_value":188378.43833874998,"previous_value":186386.8142583},"3":{"name":"KFLTFEQ70D","id":"M0357_2549","unit":9122.5557,"cost":146000,"last_value":139041.43270155002,"previous_value":137727.78468075002},"4":{"name":"KFLTFSTARD","id":"M0475_2560","unit":3460.4705,"cost":33000,"last_value":28510.816449500002,"previous_value":28100.05860115},"5":{"name":"KFSDIV","id":"M0081_2550","unit":5304.8621,"cost":58066.44,"last_value":48351.16561045,"previous_value":47838.18544538},"6":{"name":"KFSMART","id":"M0167_2559","unit":9684.9249,"cost":99749.22,"last_value":102428.73423489,"previous_value":102409.36438509},"7":{"name":"KTSTPLUS-A","id":"M0304_2559","unit":2897.6698,"cost":30000,"last_value":30497.974645000002,"previous_value":30492.46907238},"date":"2019-11-07","total_cost":632356.74,"total_last_value":610501.5279886599,"total_previous_value":605425.9750003299}';

$port = json_decode($json_string,TRUE);


//Set common variable and trim it out from array
$date = $port['date'];
$total_cost = $port['total_cost'];
$total_last_value = $port['total_last_value'];
$total_previous_value = $port['total_previos_vale'];
unset($port['date']);
unset($port['total_cost']);
unset($port['total_last_value']);
unset($port['total_previous_value']);


//Let's go output
foreach ($port as $i => $v) {
    print '<b>' . $v['name'] . '</b>' . '<br>';
    print num2($v['cost']) . '<br>';
    print num2($v['last_value']) . '<br>';
    print num2($v['last_value']-$v['cost']) . '(' . percent(($v['last_value']/$v['cost'])-1) . ')' . '<br>-------------------------<br>';
}


//format 4 decimal digits
function num4($number) {
    return number_format($number,4);
}

//format 2 decimal digits
function num2($number) {
    return number_format($number,2);
}

//format percentage
function percent($number) {
    return number_format($number*100,2).'%';
}

?>