<?php

namespace SP\AllocineBundle\API;

use Guzzle\Http\Message\Request;
use Guzzle\Http\Client;
use Guzzle\Http\QueryString;

class AllocineApi
{
    protected $baseUrl = 'http://api.allocine.fr/rest/v3/';

    protected $partner = '100043982026';

    protected $secret = '29d185d98c984a359e6e6f26a0474269';

    protected $userAgent = array(
        'Dalvik/1.6.0 (Linux; U; Android 4.2.2; Nexus 4 Build/JDQ39E)',
        'Mozilla/5.0 (Linux; Android 4.1.1; Nexus 7 Build/JRO03D) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.166  Safari/535.19',
        'Mozilla/5.0 (iPhone; U; CPU iPhone OS 4_3_2 like Mac OS X; en-us) AppleWebKit/533.17.9 (KHTML, like Gecko) Version/5.0.2 Mobile/8H7 Safari/6533.18.5',
        'Mozilla/5.0 (iPad; U; CPU OS 3_2 like Mac OS X; en-us) AppleWebKit/531.21.10 (KHTML, like Gecko) Version/4.0.4 Mobile/7B334b Safari/531.21.10',
    );

    protected $client;

    protected $sed;

    public function getFilters()
    {
        return array(
            'movie', 'theater', 'person', 'news', 'tvseries'
        );
    }

    public function __construct()
    {
        $this->client = new Client();

        shuffle($this->userAgent);

        $this->client->setUserAgent($this->userAgent[0]);

        $this->sed = date('Ymd');

        $this->params = array(
            'partner' => $this->partner,
            'format' => 'json'
        );
    }

    public function findNews($name, $profile = 'medium')
    {
        return $this->findByName($name, 'news', $profile);
    }

    public function findTheater($name, $profile = 'medium')
    {
        return $this->findByName($name, 'theater', $profile);
    }

    public function findPerson($name, $profile = 'medium')
    {
        return $this->findByName($name, 'person', $profile);
    }

    public function findMovie($name, $profile = 'medium')
    {
        return $this->findByName($name, 'movie', $profile);
    }

    public function findTvserie($name, $profile = 'medium')
    {
        return $this->findByName($name, 'tvseries', $profile);
    }

    public function findByName($name, $filter, $profile)
    {
        $results = $this->search($name, array($filter));

        if (isset($results['feed']) && $results['feed']['totalResults'] > 0)
        {
            $result = $results['feed'][$filter][0];

            $allocineData = $this->searchByCode($result['code'], $profile, $filter);

            return $allocineData;
        }

        return $results;
    }

    public function search($string, $filter = null, $count = 10, $page = 1)
    {
        $this->params['q'] = $string;

        $this->params['filter'] = implode(",", $filter ? $filter : $this->getFilters());

        $this->params['count'] = $count;

        $this->params['page'] = $page;

        $response = $this->getResponse($this->generateUrl('search'));

        if ($response == null) {
           return null;
        }

        $results = json_decode($response->getBody(), true);

        return $results;
    }

    public function searchByCode($code, $profile = 'small', $filter = 'movie')
    {
        $this->params['code'] = $code;
        $this->params['profile'] = $profile;
        $this->params['striptags'] = 'synopsis,synopsisShort';

        $response = $this->getResponse($this->generateUrl($filter));

        if ($response == null) {
            return null;
        }

        $results = json_decode($response->getBody(), true);

        return $results;
    }

    protected function generateUrl($type)
    {
        $sig = urlencode(base64_encode(sha1($this->secret.http_build_query($this->params).'&sed='.$this->sed, true)));

        $url = $this->baseUrl.$type .'?'.http_build_query($this->params).'&sed='.$this->sed.'&sig='.$sig;

        return $url;
    }

    protected function getResponse($link)
    {
        $request = $this->client->get($link);

        $query = $request->getQuery();

        $query->useUrlEncoding(QueryString::FORM_URLENCODED);

        $response = null;

        try {
            $response = $request->send();
        } catch (\Guzzle\Http\Exception\BadResponseException $e) {
            echo 'Uh oh! ' . $e->getMessage() . '<br/>';
        }

        return $response;
    }
}
