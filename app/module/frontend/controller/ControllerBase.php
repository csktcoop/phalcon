<?php

class ControllerBase extends Phalcon\Mvc\Controller
{

    public function initialize() {
        date_default_timezone_set($this->getDI()->get('config')->timezone);
    }
    
}
