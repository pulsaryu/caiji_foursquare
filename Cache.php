<?php
/**
 * Created by PhpStorm.
 * User: yuxing
 * Date: 2015-01-06
 * Time: 10:56
 */

interface Cache {
    public function set($key, $value);
    public function get($key);
    public function has($key);
} 