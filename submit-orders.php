<?php
require_once("service-discovery.php");
$data = file_get_contents('php://input');

// Get the orders application url route from service discovery
//$ordersRoute = getAPIRoute("Orders-API");

$application = getenv("VCAP_APPLICATION");
$application_json = json_decode($application, true);
$applicationURI = $application_json["application_uris"][0];

//echo "\r\napplicationURI:" . $applicationURI;
if (substr( $applicationURI, 0, 3 ) === "ui-") {
    $ordersRoute = "orders-api-" . substr($applicationURI, 3);
} else {
    $ordersRoute = str_replace("-ui-", "-orders-api-", $applicationURI);
}

$ordersHost = "http://" . $ordersRoute;
$ordersURL = $ordersHost . "/rest/orders";

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
