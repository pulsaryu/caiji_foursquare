<?php
require 'Main.php';
require dirname(__FILE__) . '/vendor/autoload.php';
require 'FileCache.php';
$config = require(dirname(__FILE__) . '/config.php');

$main = new Main(new FileCache(dirname(__FILE__) . '/storage'));
$main->foursquareId = $config['foursquare_id'];
$main->foursquareSecret = $config['foursquare_secret'];
$main->maxLatitude = $config['max_latitude'];
$main->minLatitude = $config['min_latitude'];
$main->maxLongitude = $config['max_longitude'];
$main->minLongitude = $config['min_longitude'];
$main->fire();