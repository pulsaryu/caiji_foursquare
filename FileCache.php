<?php
/**
 * Created by PhpStorm.
 * User: yuxing
 * Date: 2015-01-06
 * Time: 11:00
 */

require_once 'Cache.php';

class FileCache implements Cache
{

    private $path;

    public function __construct($path)
    {
        $this->path = $path;
    }

    public function set($key, $value)
    {
        $key = md5($key);
        $dir = $this->getCacheDir($key);
        if (!is_dir($dir)) {
            self::createDirs($dir);
        }
        file_put_contents("$dir/$key", $value);
    }

    public function get($key)
    {
        $key = md5($key);
        $dir = $this->getCacheDir($key);
        return file_get_contents("$dir/$key");
    }

    public function has($key)
    {
        $key = md5($key);
        $dir = $this->getCacheDir($key);
        return is_file("$dir/$key");
    }

    private function getCacheDir($key)
    {
        $path = rtrim($this->path, '/') . '/' . substr($key, 0, 2) . '/' . substr($key, 2, 2);
        return $path;
    }

    private static function createDirs($dir, $mode = 0777)
    {
        $arr = explode('/', $dir);
        $path = '';
        foreach ($arr as $item) {
            $path .=  '/' . $item;
            if (!is_dir($path)) {
                mkdir($path, $mode);
            }
        }

    }
}