<?php

namespace HjsonToPropelXml;

class Parameter
{

    private $attributes = [];

    public function __construct($key, $value)
    {
        $this->setAttributes($key, $value);
    }

    private function setAttributes($key, $value)
    {
        if(is_array($value)){
            $this->attributes[$key] = json_encode($value);
        }else{
            $this->attributes[$key] = $value;
        }
        
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function getXml()
    {
        $Xml = new Xml();
        return $Xml->addElement('parameter', $this->getAttributes())->getXml();
    }
}
