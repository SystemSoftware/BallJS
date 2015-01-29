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
        $stamp = 12345678;
        $soapball = $this->getSoapReponse("fixtures/ball.xml");
        $fixture = json_decode(file_get_contents("fixtures/ball.json"));
        $fixture->{'hop-count'} += 1;
        $fixture->payload->{'cmr-php-soap-client'} = $stamp;
        $ball = convertBall($soapball->ball);
        $this->assertEquals($fixture, updateBall($ball, $stamp));        
    }

    private function getSoapReponse($fixture) {
        $array = $this->xmlstringToArray(file_get_contents($fixture));
        return json_decode(json_encode($array));
    }


        /**
     * Thanks to http://outlandish.com/blog/xml-to-json/
     */
    private function xmlToArray($xml, $options = array()) {
        $defaults = array(
            'namespaceSeparator' => ':',//you may want this to be something other than a colon
            'attributePrefix' => '@',   //to distinguish between attributes and nodes with the same name
            'alwaysArray' => array(),   //array of xml tag names which should always become arrays
            'autoArray' => true,        //only create arrays for tags which appear more than once
            'textContent' => '$',       //key used for the text content of elements
            'autoText' => true,         //skip textContent key if node has no attributes or child nodes
            'keySearch' => false,       //optional search and replace on tag and attribute names
            'keyReplace' => false       //replace values for above search values (as passed to str_replace())
        );
        $options = array_merge($defaults, $options);
        $namespaces = $xml->getDocNamespaces();
        $namespaces[''] = null; //add base (empty) namespace
     
        //get attributes from all namespaces
        $attributesArray = array();
        foreach ($namespaces as $prefix => $namespace) {
            foreach ($xml->attributes($namespace) as $attributeName => $attribute) {
                //replace characters in attribute name
                if ($options['keySearch']) $attributeName =
                        str_replace($options['keySearch'], $options['keyReplace'], $attributeName);
                $attributeKey = $options['attributePrefix']
                        . ($prefix ? $prefix . $options['namespaceSeparator'] : '')
                        . $attributeName;
                $attributesArray[$attributeKey] = (string)$attribute;
            }
        }
     
        //get child nodes from all namespaces
        $tagsArray = array();
        foreach ($namespaces as $prefix => $namespace) {
            foreach ($xml->children($namespace) as $childXml) {
                //recurse into child nodes
                $childArray = $this->xmlToArray($childXml, $options);
                list($childTagName, $childProperties) = each($childArray);
     
                //replace characters in tag name
                if ($options['keySearch']) $childTagName =
                        str_replace($options['keySearch'], $options['keyReplace'], $childTagName);
                //add namespace prefix, if any
                if ($prefix) $childTagName = $prefix . $options['namespaceSeparator'] . $childTagName;
     
                if (!isset($tagsArray[$childTagName])) {
                    //only entry with this key
                    //test if tags of this type should always be arrays, no matter the element count
                    $tagsArray[$childTagName] =
                            in_array($childTagName, $options['alwaysArray']) || !$options['autoArray']
                            ? array($childProperties) : $childProperties;
                } elseif (
                    is_array($tagsArray[$childTagName]) && array_keys($tagsArray[$childTagName])
                    === range(0, count($tagsArray[$childTagName]) - 1)
                ) {
                    //key already exists and is integer indexed array
                    $tagsArray[$childTagName][] = $childProperties;
                } else {
                    //key exists so convert to integer indexed array with previous value in position 0
                    $tagsArray[$childTagName] = array($tagsArray[$childTagName], $childProperties);
                }
            }
        }
     
        //get text content of node
        $textContentArray = array();
        $plainText = trim((string)$xml);
        if ($plainText !== '') $textContentArray[$options['textContent']] = $plainText;
     
        //stick it all together
        $propertiesArray = !$options['autoText'] || $attributesArray || $tagsArray || ($plainText === '')
                ? array_merge($attributesArray, $tagsArray, $textContentArray) : $plainText;
     
        //return node as array
        return array(
            $xml->getName() => $propertiesArray
        );
    }

    private function xmlstringToArray($xmlstring) {
        $xmlNode = simplexml_load_string($xmlstring);
        $arrayData = $this->xmlToArray($xmlNode);
        return $arrayData;
    }


    private function xmlstringToJSON($xmlstring) {
        $array = $this->xmlstringToArray($xmlstring);
        return json_encode($array);
    }
}



?>