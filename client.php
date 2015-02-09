<?php

require('Ball.php');

if (count($argv) < 3) {
    printf("Usage: php %s wsdl-url rest-url verbose?\n", $argv[0]);
    print ("\n");
    print ("If third argument is given, details about soap faults\n");
    print ("will be printed.\n");
    exit(1);
}

/** delay between server-pollings in microseconds
 */
define('DELAY', 1* 1000 * 1000);
define('WSDL_URL', $argv[1]);
define('NODEJS_SERVICE_URL', $argv[2]);
if (count($argv) >= 4) {
    define('VERBOSE', true);
}
else {
    define('VERBOSE', false);
}

$soapclient = new SoapClient(WSDL_URL);
$curl_handler = curl_init(NODEJS_SERVICE_URL);
curl_setopt($curl_handler, CURLOPT_POST, 1);

do {
    try{
        $soapresponse = $soapclient->getBall();

        $ball = new Ball($soapresponse);

        print "\n";        
        $ball->greet();
        sleep($ball->getHoldTime());
        $ball->update();

        curl_setopt($curl_handler, CURLOPT_POSTFIELDS,
                    ['ball' => $ball->json()]);
        curl_exec($curl_handler);
    }
    catch(SoapFault $sf){
        print_soap_fault($sf);
    }
    usleep(DELAY);
} while(true);




function print_soap_fault($sf) {
    if (VERBOSE) {
        print "Soap Fault: ". $sf->faultstring ."\n";
    }
    else {
        print ".";
    }
}
?>
