<?php
require('Ball.php');

handle_cl_options($argv);

$soapClient = new SoapClient(WSDL_URL);
$restClient = curl_init(NODEJS_SERVICE_URL);
curl_setopt($restClient, CURLOPT_POST, true);

do {
    try{
        $soapResponse = $soapClient->getBall();

        $ball = new Ball($soapResponse);
        $ball->greet();
        sleep($ball->getHoldTime());
        $ball->update();

        /** @fixme providing postfields as an array did not work;
                   maybe because curl uses then content-type: multipart. */
        curl_setopt($restClient,
                    CURLOPT_POSTFIELDS,
                    'ball='. urlencode($ball->json()));
        curl_exec($restClient);
    }
    catch(SoapFault $sf){
        print_soap_fault($sf);
    }
    usleep(DELAY);
} while(true);



function handle_cl_options($argv) {
    if (count($argv) < 3) {
        printf("Usage: php %s wsdl-url rest-url verbose?\n", $argv[0]);
        print ("\n");
        print ("If third argument is given, details about balls and\n");
        print ("soap faults will be printed.\n");
        exit(1);
    }

    /** delay between server-pollings in microseconds */
    define('DELAY', 1* 1000 * 1000);
    define('WSDL_URL', $argv[1]);
    define('NODEJS_SERVICE_URL', $argv[2]);
    if (count($argv) >= 4) {
        define('VERBOSE', true);
    }
    else {
        define('VERBOSE', false);
    }
    Ball::$verbose = VERBOSE;
}

function print_soap_fault($sf) {
    if (VERBOSE) {
        print "Soap Fault: ". $sf->faultstring ."\n";
    }
    else {
        print ".";
    }
}
?>
