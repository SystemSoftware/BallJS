<?php

/**
 * @return ball represented as object hiearchy.
 */
function convertBall($soapball) {
    $ball = new stdClass();
    $ball->id = $soapball->id;
    $ball->{'hold-time'} = $soapball->{'hold-time'};
    $ball->{'hop-count'} = $soapball->{'hop-count'};
    $ball->payload = new stdClass();
    foreach ($soapball->payload->service as $service) {
        $ball->payload->{$service->key} = $service->value;
    }
    return $ball;
}
/**
 *
 * @return The updated ball as json string.
 */
function updateBall($ball, $timestamp = NULL) {
    $timestamp == NULL? microtime() : $timestamp;
    $ball->{'hop-count'} += 1;
    $ball->payload->{'cmr-php-soap-client'} = $timestamp;
    return $ball;
}

?>