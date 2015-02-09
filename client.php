<?php

require('Ball.php');

if (count($argv) != 3) {
    printf("Usage: php %s wsdl-url rest-url\n", $argv[0]);
    exit(1);
}

/** delay between server-pollings in microseconds
 */
define('DELAY', 1* 1000 * 1000);
define('WSDL_URL', $argv[1]);
define('NODEJS_SERVICE_URL', $argv[2]);

$soapclient = new SoapClient(WSDL_URL);
$curl_handler = curl_init(NODEJS_SERVICE_URL);
curl_setopt($curl_handler, CURLOPT_POST, 1);

do {
    try{
        $soapresponse = $soapclient->getBall();

        $ball = new Ball($soapresponse);        
        $ball->greet();
        sleep($ball->getHoldTime());
        $ball->update();

        curl_setopt($curl_handler, CURLOPT_POSTFIELDS,
                    ['ball' => $ball->json()]);
        curl_exec($curl_handler);
    }
    catch(Exception $e){
        print "No Ball available: $e";
    }
    usleep(DELAY);
} while(true);

?>
