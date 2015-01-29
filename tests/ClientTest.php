<?php

class ClientTest extends PHPUnit_Framework_TestCase
{

    public function testConvertBall() {
        $soapball = $this->getSoapReponse("fixtures/ball.xml");
        $fixture = json_decode(file_get_contents("fixtures/ball.json"));
        /** @todo Refactor and mock SoapClient class. */
        $this->assertEquals($fixture, convertBall($soapball->ball));
    }
 
    public function testUpdateBall()
    {
        $soapball = $this->getSoapReponse("fixtures/ball.xml");
        $fixture = json_decode(file_get_contents("fixtures/ball.json"));
        $fixture->{'hop-count'} += 1;
        $fixture->payload->{'cmr-php-soap-client'} = "12345678";
        $ball = convertBall($soapball->ball);
        $this->assertEquals($fixture, updateBall($ball, 12345678));        
    }

    

    private function getSoapReponse($fixture) {
        $array = xmlstringToArray(file_get_contents($fixture));
        return json_decode(json_encode($array));
    }
}



?>