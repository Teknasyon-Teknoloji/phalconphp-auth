<?php

namespace Teknasyon\Phalcon;

/**
 * Class InjectionAwareness
 */
trait InjectionAwareness {

    protected $_di;


    public function setDi(\Phalcon\DiInterface $di)
    {
        $this->_di = $di;
    }

    public function getDi()
    {
        return $this->_di;
    }
}