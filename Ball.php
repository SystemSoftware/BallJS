<?php


class Ball {
    const PAYLOAD_ID = 'cmr-php-soap-client';


    /** ball represented as stdClass-object hiearchy.
     *  Keeps the data structure very similar to the soap-response,
     *  @see __construct, and easyily jason-serializable, @see json.
     */
    private $ball;

    function __construct($soapresponse) {
        $soapball = $soapresponse->ball;
        $this->ball = new stdClass();
        $this->ball->id = $soapball->id;
        $this->ball->{'hold-time'} = $soapball->{'hold-time'};
        $this->ball->{'hop-count'} = $soapball->{'hop-count'};
        $this->ball->payload = new stdClass();
        foreach ($soapball->payload->service as $service) {
            $this->ball->payload->{$service->key} = $service->value;
        }
    }

    public function getId()       { return $this->ball->id; }
    public function getHoldTime() { return $this->ball->{'hold-time'}; }
    public function getHopCount() { return $this->ball->{'hop-count'}; }
    public function getPayload()  { return $this->ball->payload; }

    public function update($timestamp = NULL) {
        $timestamp == NULL? microtime() : $timestamp;
        $this->ball->{'hop-count'} += 1;
        $this->ball->payload->{self::PAYLOAD_ID} = $timestamp;
    }

    public function json() {
        return json_encode($this->ball);
    }

    public function greet() {
        print "Received ball ". $this->getId();
        if (isset($ball->getPayload()->{self::PAYLOAD_ID} )) {
            $lastHere = $ball->payload->{'cmr-php-soap-client'};
            $delta = microtime() - $lastHere;
            print "  after ". $delta ." microseconds of absence.";
        }
        else {
            print "  and wee see it for the first time.";
        }
    }

}


?>