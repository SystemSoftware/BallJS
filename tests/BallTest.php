<?php

class ClientTest extends PHPUnit_Framework_TestCase
{

    public function testInstantiation() {
        /* Response-fixture, as would be returned by
           calling the soap service's getBall() operation. */
        $response = new stdClass();
        $response->return = new stdClass();
        $response->return->id = 'Ball 1';
        $response->return->holdtime = 1;
        $response->return->hopcount = 5;

        $payload = new stdClass();
        $payload->entry = new stdClass();
        $payload->entry->key = 'foo';
        $payload->entry->value = 'bar';
        
        $response->return->payload = $payload;


        $ball = new Ball($response);
        $this->assertEquals('Ball 1', $ball->getId());
        $this->assertEquals(1, $ball->getHoldTime());
        $this->assertEquals(5, $ball->getHopCount());
        $this->assertCount(1, $ball->getPayload());
        $this->assertEquals('bar', $ball->getPayload()['foo']);

        return $ball;
    }

    /**
     * @depends testInstantiation
     */
    public function testUpdate(Ball $ball)
    {
        $previousHops = $ball->getHopCount();
        /** @todo mock php's built-in time functions somehow,
         *        so we do not need to adapt the SUT for making it
         *        testable anymore. 
         *        cf. http://stackoverflow.com/q/2371854/1242922
         */
        $timestamp = 12345678;
        $ball->update($timestamp);
        $this->assertEquals($previousHops + 1, $ball->getHopCount());
        $this->assertCount(2, $ball->getPayload());
        $this->assertEquals($timestamp, $ball->getPayload()['cmr-php-soap-client']);
        /** @todo test that nothing else has changed. */

        return $ball;
    }

    /**
     * @depends testUpdate
     */
    public function testJson($ball) {
        $fixture = json_decode(file_get_contents("fixtures/ball.json"));

        $this->assertEquals($fixture, json_decode($ball->json()));
    }

    public function testGreet() {
        // $this->expectOutputString()
        // cf. https://phpunit.de/manual/current/en/writing-tests-for-phpunit.html#writing-tests-for-phpunit.test-dependencies
    }

}



?>