<?php
require_once("service-discovery.php");
$data = file_get_contents('php://input');

// Get the orders application url route from service discovery
//$ordersRoute = getAPIRoute("Orders-API");

$application = getenv("sgroup_name");
$applicationURI=$application;

//echo "\r\napplicationURI:" . $applicationURI;
 if (substr( $applicationURI, 0, 3 ) === "ui-") {
    $orderHost = "orders-api-" . substr($applicationURI, 3, 35);
    $ordersRoute = "orders-api-" . substr($applicationURI, 3);
 } else {
    $orderHost = str_replace("-ui-", "-orders-api-", $applicationURI);
    $ordersRoute = str_replace("-ui-", "-orders-api-", $applicationURI);
 }

$orderRoute = "http://" . $orderHost . ".mybluemix.net/JavaOrdersAPI-1.0/rest/orders";

$ordersURL=$orderRoute;
error_log("ordersURL is ");
error_log($ordersURL);


//$ordersURL = $ordersRoute . "/rest/orders";
//$ordersURL = "http://ms-ordersAPI-hyperfunctional-throstle.mybluemix.net/rest/orders";

function httpPost($data,$url){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,$url);
	curl_setopt($ch, CURLOPT_POST, true);  
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data); 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
	$output = curl_exec ($ch);
	$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	curl_close ($ch);
	return $code;
}

echo json_encode(array("httpCode" => httpPost($data, $ordersURL), "ordersURL" => $ordersURL));

?>
