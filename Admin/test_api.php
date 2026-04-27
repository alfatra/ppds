<?php
$timestamp = time();
$consumerID = '123456';
$consumerPassword = '0034T2';
$data = $timestamp . $consumerID;
$signature = base64_encode(hash_hmac('sha256', $data, $consumerPassword, true));

echo "=== API Test Debug ===\n";
echo "Timestamp: " . $timestamp . "\n";
echo "ConsumerID: " . $consumerID . "\n";
echo "Data: " . $data . "\n";
echo "Signature: " . $signature . "\n\n";

$url = 'http://192.168.10.33/medinfras/test/api/diagnose/list';
echo "URL: " . $url . "\n\n";

$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 15,
    CURLOPT_VERBOSE => true,
    CURLOPT_STDERR => fopen('php://stderr', 'w'),
    CURLOPT_HTTPHEADER => array(
        'consumerID: ' . $consumerID,
        'timestamp: ' . $timestamp,
        'signature: ' . $signature,
        'Content-Type: application/json'
    ),
));

$response = curl_exec($curl);
$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
$curlError = curl_error($curl);
$curlInfo = curl_getinfo($curl);
curl_close($curl);

echo "\n=== Response ===\n";
echo "HTTP Code: " . $httpCode . "\n";
echo "Content Type: " . $curlInfo['content_type'] . "\n";

if ($curlError) {
    echo "Curl Error: " . $curlError . "\n";
} else {
    echo "Response Body:\n";
    echo $response . "\n";
    
    // Try to decode JSON
    $json = json_decode($response, true);
    if ($json) {
        echo "\nJSON Decoded:\n";
        echo json_encode($json, JSON_PRETTY_PRINT) . "\n";
    }
}
?>