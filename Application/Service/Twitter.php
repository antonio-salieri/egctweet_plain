<?php
namespace EgcTweet\Service;

use ZendService\Twitter\Twitter as ZendTwitter;
use Zend\Http\Response;
use ZendService\Twitter\Response as ZendTwitterResponse;

class Twitter extends ZendTwitter
{

    const DEFAULT_LIST_SIZE = 20;

    const DEFAULT_TIMELINE_STATUS_COUNT = 5;

    public function __construct($options = null, OAuth\Consumer $consumer = null, Http\Client $httpClient = null)
    {
        parent::__construct($options, $consumer, $httpClient);

        // Fix broken ZF2 HTTP Client
        if (isset($options['http_client_options']) && isset($options['http_client_options']['adapter'])) {
            $adapter_config = array();
            if (isset($options['http_client_options']['adapter_options'])) {
                $adapter_config = $options['http_client_options']['adapter_options'];
            }
            $client_addapter = new $options['http_client_options']['adapter']($adapter_config);

            $this->getHttpClient()->setAdapter($client_addapter);
        }
    }

    public function usersSearch($query, array $options = array())
    {
        if (! isset($options['count'])) {
            $options['count'] = self::DEFAULT_LIST_SIZE;
        }

        return parent::usersSearch($query, $options);
    }

    public function getLastTweets($user_id, $screen_name = '', array $options = array())
    {
        $options['user_id'] = $user_id;
        if (! empty($screen_name)) {
            $options['screen_name'] = $screen_name;
        }
        if (! isset($options['count'])) {
            $options['count'] = self::DEFAULT_TIMELINE_STATUS_COUNT;
        }
        return $this->statusesUserTimeline($options);
    }

    protected function get($path, array $query = array())
    {
        $response = null;
        try {
            $response = parent::get($path, $query);
        } catch (\Exception $e) {
            $http_response = new Response();
            $http_response->setStatusCode(Response::STATUS_CODE_500);
            $response = new ZendTwitterResponse($http_response);
        }

        return $response;
    }
}
