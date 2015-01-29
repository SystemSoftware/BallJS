<?php

class ClientTest extends PHPUnit_Framework_TestCase
{

    public function testConvertBall() {
        $soapball = $this->getSoapReponse("fixtures/ball.xml");
        $jsonfixture = json_decode(file_get_contents("fixtures/ball.json"));
        /** @todo Refactor and mock SoapClient class. */
        $this->assertEquals($jsonfixture, convertBall($soapball->ball));
    }
 
    public function testUpdateBall()
    {
        // $response = $this->getSoapReponse("fixtures/ball.xml");
        // var_dump($response->ball->payload);
        // $updatedBall = updateBall($response);
        // $this->assertEquals($updatedBall->hop-count, $reponse->hop-count + 1);
        
    }

    

    private function getSoapReponse($fixture) {
        $array = xmlstringToArray(file_get_contents($fixture));
        return json_decode(json_encode($array));
    }
}



?>