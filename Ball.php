<?php


class Ball {
    public static $verbose = false;
    const PAYLOAD_ID = 'cmr-php-soap-client';


    /** ball represented as stdClass-object hiearchy.
     *  Keeps the data structure very similar to the soap-response,
     *  @see __construct, and easyily jason-serializable, @see json.
     */
    private $ball;

    function __construct($soapresponse) {
        $soapball = $soapresponse->return;
        $this->ball = new stdClass();
        $this->ball->id = $soapball->id;
        $this->ball->{'hold-time'} = $soapball->holdtime;
        $this->ball->{'hop-count'} = $soapball->hopcount;
        $this->ball->payload = [];
        $key = $soapball->payload->entry->key;
        $value = $soapball->payload->entry->value;
        $this->ball->payload[$key] = $value;
    }

    public function getId()       { return $this->ball->id; }
    public function getHoldTime() { return $this->ball->{'hold-time'}; }
    public function getHopCount() { return $this->ball->{'hop-count'}; }
    public function getPayload()  { return $this->ball->payload; }

    public function update($timestamp = NULL) {
        if ($timestamp == NULL) {
            // return microtime as float.
            $timestamp = microtime(true);
        }
        $this->ball->{'hop-count'} += 1;
        $this->ball->payload[self::PAYLOAD_ID] = $timestamp;
    }

    public function json() {
        return json_encode($this->ball);
    }

    public function greet() {
        print "\n";
        print "Received ball ". $this->getId() . "\n";
        if (isset($this->getPayload()->{self::PAYLOAD_ID} )) {
            $lastHere = $this->payload->{'cmr-php-soap-client'};
            $delta = microtime(true) - $lastHere;
            print "  after ". $delta ." seconds of absence.\n";
        }
        else {
            print "  and wee see it for the first time.\n";
        }
        if (self::$verbose) {
            print "  ". $this->json() ."\n";
        }
    }

}


?>