<?php


class Ball {
    public static $verbose = false;
    const PAYLOAD_ID = 'cmr-php-soap-client';


    /** ball represented as stdClass-object hierarchy.
     *  Keeps the data structure very similar to the soap-response,
     *  @see __construct, and easily jason-serializable, @see json.
     */
    private $ball;

    function __construct($soapresponse) {
        $soapball = $soapresponse->return;
        $this->ball = new stdClass();
        $this->ball->id = $soapball->id;
        $this->ball->{'hold-time'} = $soapball->holdtime;
        $this->ball->{'hop-count'} = $soapball->hopcount;
        $this->ball->payload = [];
        // the soap-ball's payload (to be more specific: payload->entry)
        // might either be an array of items, or a single item if
        // there is only one.
        // It would be nicer if a single item was still wrapped in an
        // array, but that's not how it was done, unfortunately.
        if (is_array($soapball->payload->entry)) {
            foreach ($soapball->payload->entry as $entry) {
                $this->ball->payload[$entry->key] = $entry->value;
            }
        }
        else {
            $key = $soapball->payload->entry->key;
            $value = $soapball->payload->entry->value;
            $this->ball->payload[$key] = $value;
        }
        /** @todo what if there is no item in the payload? Don't know
                  how such a soap-response would look like. */
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
        print "Hi, my name is ". $this->getId() . " and\n";
        if (isset($this->getPayload()->{self::PAYLOAD_ID} )) {
            $lastHere = $this->payload->{'cmr-php-soap-client'};
            $delta = microtime(true) - $lastHere;
            print "  I'm back after ". $delta ." seconds of absence.\n";
        }
        else {
            print "  I'm here for the first time.\n";
        }
        if (self::$verbose) {
            print "  ". $this->json() ."\n";
        }
    }

}


?>