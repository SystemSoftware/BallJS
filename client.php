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
    $soapball = $soapclient->getBall();
    $ball = convertBall($soapball);
    sleep($ball->{'hold-time'});
    $updatedBall = updateBall($ball);

    $post = array();
    $post['ball'] = json_encode($updatedBall);
    curl_setopt($curl_handler, CURLOPT_POSTFIELDS, $post);

    $response = curl_exec($curl_handler);

    usleep(DELAY);
} while(true);

// immer wieder soap-server pollen
// wenn  ein ball zurueck kommt
//    hop erhoehen
//    rundenlaufzeit berechnen, ausgeben
//    dann auf rest-server pushen

// alternative mit mehreren baellen: 
// soap-server nach baellen fragen
// wenn  etwas da ist
//    für jeden ball
//       hop erhoehen
//       rundenlaufzeit berechnen, ausgeben
//       dann auf rest-server pushen


// @todo was ist wenn mehrere baelle existieren? sehen wir das ueberhaupt?
// 
?>