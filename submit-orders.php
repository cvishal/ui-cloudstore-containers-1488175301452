<?php
require_once("service-discovery.php");
$data = file_get_contents('php://input');
$services = getenv("VCAP_SERVICES");
$services_json = json_decode($services, true);

// Get the orders application url route from service discovery
//$ordersRoute = getAPIRoute("Orders-API");

$application = getenv("sgroup_name");
$applicationURI=$application;
error_log("order applicationURI  is ");
error_log($applicationURI);
//echo "\r\napplicationURI:" . $applicationURI;
if (substr( $applicationURI, 0, 3 ) === "ui-") {
    $orderHost = "orders-api-" . substr($applicationURI, 3, 35);
} else {
    $orderHost = str_replace("-ui-", "-orders-api-", $applicationURI);
}
error_log("Order host  is ");
error_log($orderHost);
$orderRoute = "http://" . $orderHost . ".mybluemix.net/JavaOrdersAPI-1.0/rest/orders";
error_log("orderRoute is ");
error_log($orderRoute);
$ordersURL=$orderRoute;
error_log("ordersURL is ");
error_log($ordersURL);

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
