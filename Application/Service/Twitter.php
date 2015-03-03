<?php
namespace Application\Service;

use Egc\Service\Twitter\Twitter as EgcTwitter;

class Twitter extends EgcTwitter
{
    const DEFAULT_LIST_SIZE = 20;
    const DEFAULT_TIMELINE_STATUS_COUNT = 5;

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
