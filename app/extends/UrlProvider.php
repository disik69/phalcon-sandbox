<?php

class UrlProvider extends \Phalcon\Mvc\Url
{
    public function get($uri = null, $args = null, $local = null)
    {
        try {
            $result = parent::get($uri, $args, $local);
        } catch (Phalcon\Mvc\Url\Exception $e) {
            $result = false;
        }
        
        return $result;
    }
}
