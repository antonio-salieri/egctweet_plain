<?php
namespace Egc\Service\Twitter;

/**
 *
 * @category Zend
 * @package Zend_Service
 * @subpackage Twitter
 */
class Twitter
{
    const CONFIG_KEY = 'egc_tweet';

    /**
     * Base URI for all API calls
     */
    const API_BASE_URI = 'https://api.twitter.com/1.1/';
//     const API_BASE_URI = 'http://api.twitter.com:88/1.1/';  // local test

    const UAGENT = 'EgcTweet/curl';

    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';

    /**
     * Date format for 'since' strings
     *
     * @var string
     */
    protected $dateFormat = 'D, d M Y H:i:s T';

    /**
     * Auth (i.e. OAuth) for Twitter
     * @var Auth
     */
    protected $authAdapter;

    /**
     * Additional HTTP client (curl) options
     * @var array
     */
    protected $httpClientOptions = array(
        CURLOPT_CONNECTTIMEOUT => 10,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 60,
        CURLOPT_USERAGENT      => self::UAGENT,
        CURLOPT_HEADER         => true
//         CURLOPT_VERBOSE => 1,
    );

    /**
     * Constructor
     *
     * @param null|array $options
     */
    public function __construct(array $options = array())
    {
        $this->authAdapter = new Auth($options);
        $this->authAdapter->setRequestUrl(self::API_BASE_URI);

        $httpClientOptions = array();
        if (isset($options['httpClientOptions'])) {
            $httpClientOptions = $options['httpClientOptions'];
        } elseif (isset($options['http_client_options'])) {
            $httpClientOptions = $options['http_client_options'];
        }
        $this->setHttpClientOptions($httpClientOptions);
    }

    public function setHttpClientOptions($http_client_options)
    {
        foreach ($http_client_options as $option => $val)
        {
            $this->httpClientOptions[$option] = $val;

        }
        return $this;
    }

    public function getHttpClientOptions()
    {
        return $this->httpClientOptions;
    }

    /**
     * User Timeline status
     *
     * $options may include one or more of the following keys
     * - user_id: Id of a user for whom to fetch favorites
     * - screen_name: Screen name of a user for whom to fetch favorites
     * - count: number of tweets to attempt to retrieve, up to 200
     * - since_id: return results only after the specified tweet id
     * - max_id: return results with an ID less than (older than) or equal to the specified ID
     * - trim_user: when set to true, "t", or 1, user object in tweets will include only author's ID.
     * - exclude_replies: when set to true, will strip replies appearing in the timeline
     * - contributor_details: when set to true, includes screen_name of each contributor
     * - include_rts: when set to false, will strip native retweets
     *
     * @return Response
     */
    public function statusesUserTimeline(array $options = array())
    {
        $path = 'statuses/user_timeline.json';
        $params = array();
        foreach ($options as $key => $value) {
            switch (strtolower($key)) {
                case 'user_id':
                    $params['user_id'] = $this->validInteger($value);
                    break;
                case 'screen_name':
                    $params['screen_name'] = $this->validateScreenName($value);
                    break;
                case 'count':
                    $params['count'] = (int) $value;
                    break;
                case 'since_id':
                    $params['since_id'] = $this->validInteger($value);
                    break;
                case 'max_id':
                    $params['max_id'] = $this->validInteger($value);
                    break;
                case 'trim_user':
                    if (in_array($value, array(
                        true,
                        'true',
                        't',
                        1,
                        '1'
                    ))) {
                        $value = true;
                    } else {
                        $value = false;
                    }
                    $params['trim_user'] = $value;
                    break;
                case 'contributor_details:':
                    $params['contributor_details:'] = (bool) $value;
                    break;
                case 'exclude_replies':
                    $params['exclude_replies'] = (bool) $value;
                    break;
                case 'include_rts':
                    $params['include_rts'] = (bool) $value;
                    break;
                default:
                    break;
            }
        }

        $response = $this->get($path, $params);
        return new Response($response);
    }

    /**
     * Search users
     *
     * $options may include any of the following:
     * - page: the page of results to retrieve
     * - count: the number of users to retrieve per page; max is 20
     * - include_entities: if set to boolean true, include embedded entities
     *
     * @param string $query
     * @param array $options
     * @return Response
     */
    public function usersSearch($query, array $options = array())
    {
        $path = 'users/search.json';

        $len = iconv_strlen($query, 'UTF-8');
        if (0 == $len) {
            throw new Exception\InvalidArgumentException('Query must contain at least one character');
        }

        $params = array(
            'q' => $query
        );
        foreach ($options as $key => $value) {
            switch (strtolower($key)) {
                case 'count':
                    $value = (int) $value;
                    if (1 > $value || 20 < $value) {
                        throw new Exception\InvalidArgumentException('count must be between 1 and 20');
                    }
                    $params['count'] = $value;
                    break;
                case 'page':
                    $params['page'] = (int) $value;
                    break;
                case 'include_entities':
                    $params['include_entities'] = (bool) $value;
                    break;
                default:
                    break;
            }
        }
        $response = $this->get($path, $params);
        return new Response($response);
    }

    /**
     * Protected function to validate that the integer is valid or return a 0
     *
     * @param
     *            $int
     * @throws Http\Client\Exception\ExceptionInterface if HTTP request fails or times out
     * @return integer
     */
    protected function validInteger($int)
    {
        if (preg_match("/(\d+)/", $int)) {
            return $int;
        }
        return 0;
    }

    /**
     * Validate a screen name using Twitter rules
     *
     * @param string $name
     * @return string
     * @throws Exception\InvalidArgumentException
     */
    protected function validateScreenName($name)
    {
        if (! preg_match('/^[a-zA-Z0-9_]{0,20}$/', $name)) {
            throw new Exception\InvalidArgumentException('Screen name, "' . $name . '" should only contain alphanumeric characters and' . ' underscores, and not exceed 15 characters.');
        }
        return $name;
    }

    /**
     * Performs an HTTP GET request to the $path.
     *
     * @param string $path
     * @param array $query
     *            Array of GET parameters
     * @return Response
     */
    protected function get($path, array $query = array())
    {

        $auth_params = $this->authAdapter
            ->setRequestPath($path)
            ->setRequestQuery($query)
            ->setRequestMethod(self::METHOD_GET)
            ->getAuthParams();

        $auth_header = $this->authAdapter->getAuthHeaderForParams($auth_params);

        $query_str = $this->_buildQueryString($query);
        $curl_res = curl_init(self::API_BASE_URI . "{$path}?{$query_str}");

        $status = curl_setopt_array($curl_res, $this->getHttpClientOptions() + array(
            CURLOPT_HTTPGET => true,
            CURLOPT_HTTPHEADER => $auth_header
        ));
        if (!$status)
            throw new \Exception("Error setting client options.");

        $response = $this->_execRequest($curl_res);
        return $response;
    }

    protected function _execRequest($curl_res)
    {
        $result = null;
        if (!is_resource($curl_res))
            throw new \Exception("Error: Curl resource is expected.");

        session_write_close();
        $result = curl_exec($curl_res);
        curl_close($curl_res);
        return $result;

    }

    protected function _buildQueryString(array $query = array())
    {
        $string = '';
        foreach ($query as $name => $value) {
            $string .= "{$name}={$value}&";
        }

        $string = substr($string, 0, -1);

        return $string;
    }
}
