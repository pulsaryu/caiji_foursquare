<?php
require 'Main.php';
require dirname(__FILE__) . '/vendor/autoload.php';
$config = require(dirname(__FILE__) . '/config.php');

$main = new Main(dirname(__FILE__) . '/storage');
$main->foursquareId = $config['foursquare_id'];
$main->foursquareSecret = $config['foursquare_secret'];
$main->maxLatitude = $config['max_latitude'];
$main->minLatitude = $config['min_latitude'];
$main->maxLongitude = $config['max_longitude'];
$main->minLongitude = $config['min_longitude'];
$main->fire(function($venue) {
    echo "{$venue['id']},\"{$venue['name']}\"";

    echo ",";
    if (isset($venue['contact']['phone'])) {
        echo '"', $venue['contact']['phone'], '"';
    }

    echo ',';
    if (isset($venue['location']['formattedAddress'])) {
        echo '"';
        foreach ($venue['location']['formattedAddress'] as $address) {
            echo $address, " ";
        }
        echo '"';
    }

    echo ',"';
    $first = true;
    foreach ($venue['categories'] as $category) {
        if ($first) {
            $first = false;
        } else {
            echo ' | ';
        }
        echo $category['name'];
    }
    echo '"';

    //hours
    echo ',"';
    if (isset($venue['hours']['status'])) {
        echo $venue['hours']['status'];
    }
    echo '"';

    //stats
    echo ',';
    $stats = $venue['stats'];
    echo $stats['checkinsCount'];

    echo PHP_EOL;
});