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

    /**
     * Base URI for all API calls
     */
    const API_BASE_URI = 'https://api.twitter.com/1.1/';
//     const API_BASE_URI = 'http://api.twitter.com:88/1.1/';  // local test

    const UAGENT = 'EgcTweet/curl';

    const OAUTH_SIGNATURE_METHOD = 'HMAC-SHA1';
    const SIGNATURE_METHOD = 'SHA1';
    const OAUTH_VERSION = '1.0';

    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';

    /**
     * Date format for 'since' strings
     *
     * @var string
     */
    protected $dateFormat = 'D, d M Y H:i:s T';

    /**
     * Twitter access token
     * @var string
     */
    protected $accessToken;

    /**
     * Twitter access token secret
     * @var string
     */
    protected $accessTokenSecret;

    /**
     * Twitter's app OAuth consumer key
     * @var string
     */
    protected $oauthConsumerKey;

    /**
     * Twitter's app OAuth consumer secret
     * @var string
     */
    protected $oauthConsumerSecret;

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
     * Options passed to constructor
     *
     * @var array
     */
    protected $options = array();

    /**
     * Constructor
     *
     * @param null|array $options
     */
    public function __construct(array $options = array())
    {
        $this->options = $options;


        $accessToken = false;
        if (isset($options['accessToken'])) {
            $accessToken = $options['accessToken'];
        } elseif (isset($options['access_token'])) {
            $accessToken = $options['access_token'];
        }
        if (isset($accessToken['token']))
            $this->setAccessToken($accessToken['token']);
        if (isset($accessToken['secret']))
            $this->setAccessTokenSecret($accessToken['secret']);

        $oauthOptions = array();
        if (isset($options['oauthOptions'])) {
            $oauthOptions = $options['oauthOptions'];
        } elseif (isset($options['oauth_options'])) {
            $oauthOptions = $options['oauth_options'];
        }
        if (isset($oauthOptions['consumerKey']))
            $this->setOauthConsumerKey($oauthOptions['consumerKey']);
        if (isset($oauthOptions['consumerSecret']))
            $this->setOauthConsumerSecret($oauthOptions['consumerSecret']);

        $httpClientOptions = array();
        if (isset($options['httpClientOptions'])) {
            $httpClientOptions = $options['httpClientOptions'];
        } elseif (isset($options['http_client_options'])) {
            $httpClientOptions = $options['http_client_options'];
        }
        $this->setHttpClientOptions($httpClientOptions);
    }

    public function setAccessToken($token)
    {
        $this->accessToken = $token;
        return $this;
    }

    public function getAccessToken()
    {
        return $this->accessToken;
    }

    public function setAccessTokenSecret($secret)
    {
        $this->accessTokenSecret = $secret;
        return $this;
    }

    public function getAccessTokenSecret()
    {
        return $this->accessTokenSecret;
    }

    public function setOauthConsumerKey($consumer_key)
    {
        $this->oauthConsumerKey = $consumer_key;
        return $this;
    }

    public function getOauthConsumerKey()
    {
        return $this->oauthConsumerKey;
    }

    public function setOauthConsumerSecret($consumer_secret)
    {
        $this->oauthConsumerSecret = $consumer_secret;
        return $this;
    }

    public function getOauthConsumerSecret()
    {
        return $this->oauthConsumerSecret;
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
        $this->init();
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
     * @throws Http\Client\Exception\ExceptionInterface if HTTP request fails or times out
     * @throws Exception\DomainException if unable to decode JSON payload
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
     * @throws Http\Client\Exception\ExceptionInterface
     * @return Response
     */
    protected function get($path, array $query = array())
    {
        $query_str = $this->_buildQueryString($query);

        $params = $this->_assembleParams($query);

        $sig_base_string = $this->_getBaseSignatureString($params, self::METHOD_GET, self::API_BASE_URI.$path);
        $key = "{$this->getOauthConsumerSecret()}&{$this->getAccessTokenSecret()}";
        $binary_sig = hash_hmac(self::SIGNATURE_METHOD, $sig_base_string, $key, true);

        $params['oauth_signature'] = $this->_urlEncode(base64_encode($binary_sig));

        $client = curl_init(self::API_BASE_URI . "{$path}?{$query_str}");
        $header_authz = "Authorization: OAuth oauth_consumer_key=\"{$this->getOauthConsumerKey()}\", oauth_nonce=\"{$params['oauth_nonce']}\", oauth_signature=\"{$params['oauth_signature']}\", oauth_signature_method=\"HMAC-SHA1\", oauth_timestamp=\"{$params['oauth_timestamp']}\", oauth_token=\"{$this->getAccessToken()}\", oauth_version=\"".self::OAUTH_VERSION."\"";
        $header_content_type = "Content-Type: application/x-www-form-urlencoded";
        $status = curl_setopt_array($client, $this->getHttpClientOptions() + array(
            CURLOPT_HTTPGET => true,
            CURLOPT_HTTPHEADER => array(
                $header_content_type,
                $header_authz
            )
        ));
        if (!$status)
            throw new \Exception("Error setting client options.");

        $response = $this->_execRequest($client);
        return $response;
    }

    protected function _execRequest($client)
    {
        session_write_close();
        $result = curl_exec($client);
        curl_close($client);
        return $result;

    }

    protected function _assembleParams($query_params = array())
    {
        $params = array(
            'oauth_consumer_key' => $this->getOauthConsumerKey(),
            'oauth_nonce' => $this->_generateNonce(),
            'oauth_signature_method' => self::OAUTH_SIGNATURE_METHOD,
            'oauth_timestamp' => time(),
            'oauth_token' => $this->getAccessToken(),
            'oauth_version' => self::OAUTH_VERSION
        );

        if (! empty($query_params)) {
            $params = array_merge($params, $query_params);
        }

        return $params;
    }

    protected function _generateNonce()
    {
        return md5(uniqid(rand(), true));
    }

    protected function _getBaseSignatureString(array $params, $method = null, $url = null)
    {
        $encodedParams = array();
        foreach ($params as $key => $value) {
            $encodedParams[$this->_urlEncode($key)] = $this->_urlEncode($value);
        }
        $baseStrings = array();
        if (isset($method)) {
            $baseStrings[] = strtoupper($method);
        }
        if (isset($url)) {
            $baseStrings[] = $this->_urlEncode($url);
        }
        if (isset($encodedParams['oauth_signature'])) {
            unset($encodedParams['oauth_signature']);
        }
        $baseStrings[] = $this->_urlEncode($this->_toByteValueOrderedQueryString($encodedParams));
        return implode('&', $baseStrings);
    }

    protected function _urlEncode($value)
    {
        $encoded = rawurlencode($value);
        $encoded = str_replace('%7E', '~', $encoded);
        return $encoded;
    }

    protected function _toByteValueOrderedQueryString(array $params)
    {
        $return = array();
        uksort($params, 'strnatcmp');
        foreach ($params as $key => $value) {
            if (is_array($value)) {
                natsort($value);
                foreach ($value as $keyduplicate) {
                    $return[] = $key . '=' . $keyduplicate;
                }
            } else {
                $return[] = $key . '=' . $value;
            }
        }
        return implode('&', $return);
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
