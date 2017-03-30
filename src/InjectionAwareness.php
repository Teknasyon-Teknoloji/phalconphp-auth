<?php

namespace Teknasyon\Phalcon;

use Phalcon\DiInterface;

/**
 * Class InjectionAwareness
 */
trait InjectionAwareness {

    /**
     * @var DiInterface
     */
    protected $_di;


    public function setDi(DiInterface $di)
    {
        $this->_di = $di;
    }

    /**
     * @return DiInterface
     */
    public function getDi()
    {
        return $this->_di;
    }
}