<?php

require('clientUtils.php');


/** delay between server-pollings in microseconds
 */
define('DELAY', 1* 1000 * 1000);
define('WSDL_URL', $argv[0]);
define('NODEJS_SERVICE_URL', $argv[1]);

$soapclient = new SoapClient(WSDL_URL);
$curl_handler = curl_init(NODEJS_SERVICE_URL);
curl_setopt($curl_handler, CURLOPT_POST, 1);

do {
    try{
        $soapball = $soapclient->getBall();
        $ball = convertBall($soapball);
        
        $time = time();
        $ballId = $ball->id;
        $lastHere = $time;

        if (isset($ball->payload->{'cmr-php-soap-client'} )){
            $lastHere = $ball->payload->{'cmr-php-soap-client'};
        }
        $roundTripTime = $time - $lastHere;

        print "Received ball $ballId; Roundtrip-time: $roundTripTime ms";
        sleep($ball->{'hold-time'});
        $updatedBall = updateBall($ball);

        $post = array();
        $post['ball'] = json_encode($updatedBall);
        curl_setopt($curl_handler, CURLOPT_POSTFIELDS, $post);

        $response = curl_exec($curl_handler);
    }
    catch(Exception $e){
        print "No Ball available: $e";
    }
    usleep(DELAY);
} while(true);

?>
