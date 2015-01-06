<?php
/**
 * Created by PhpStorm.
 * User: yuxing
 * Date: 2015-01-05
 * Time: 16:57
 */

class Main {

    public $foursquareId;
    public $foursquareSecret;
    public $maxLatitude;
    public $minLatitude;
    public $maxLongitude;
    public $minLongitude;
    private $httpClient;
    /**
     * @var Cache
     */
    private $fileCache;

    public function __construct($fileCache)
    {
        $this->httpClient = new GuzzleHttp\Client();
        $this->fileCache = $fileCache;
    }

    public function fire()
    {
        $n = 0;
        $m = 0;
        for ($latitude = $this->maxLatitude; $latitude > $this->minLatitude; $latitude -= 0.001) {
            for ($longitude = $this->minLongitude; $longitude < $this->maxLongitude; $longitude += 0.001) {
                $sw = $latitude .','. $longitude;
                $ne = ($latitude-0.001) .','. ($longitude+0.001);

                $url = $this->createSearchUrl($sw, $ne);

                $body = $this->httpRequestGet($url);
                if ($body) {
                    $data = json_decode($body, true);

                    $venues = $data['response']['venues'];

                    foreach ($venues as $venue) {
                        $id = $venue['id'];
                        $detailBody = $this->httpRequestGet($this->createDetailUrl($id), $id);
                        $detail = json_decode($detailBody, true);
                        echo $detail['response']['venue']['name'], PHP_EOL;
                        $m ++;
                    }

                }

                echo "$n | $m", "\r";

                $n ++;
            }
        }

        echo $n;
    }

    private function httpRequestGet($url, $key = null)
    {
        if ($key == null) {
            $key = $url;
        }
        if (!$this->fileCache->has($key)) {
            try {
                $response = $this->httpClient->get($url);
                $body = $response->getBody();
                $this->fileCache->set($key, $body);
            } catch (Exception $e) {
                echo $e->getMessage(), PHP_EOL;
                return false;
            }
        }

        return $this->fileCache->get($key);
    }

    private function createSearchUrl($sw, $ne)
    {
        $url = Purl\Url::parse('https://api.foursquare.com/v2/venues/search');
        $url->query->set('client_id', $this->foursquareId);
        $url->query->set('client_secret', $this->foursquareSecret);
        $url->query->set('v', '20150101');
        $url->query->set('intent', 'browse');
        $url->query->set('limit', '50');
        $url->query->set('sw', $sw);
        $url->query->set('ne', $ne);
        return $url;
    }

    private function createDetailUrl($id)
    {
        $url = Purl\Url::parse('https://api.foursquare.com/v2/venues');
        $url->path->add($id);
        $url->query->set('client_id', $this->foursquareId);
        $url->query->set('client_secret', $this->foursquareSecret);
        $url->query->set('v', '20150101');
        return $url;
    }
}