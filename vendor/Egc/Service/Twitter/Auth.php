<?php
namespace Egc\Service\Twitter;

class Auth
{

    const OAUTH_SIGNATURE_METHOD = 'HMAC-SHA1';

    const SIGNATURE_METHOD = 'SHA1';

    const OAUTH_VERSION = '1.0';

    protected $requestUrl;

    protected $requestPath;

    protected $requestQuery;

    protected $requestMethod;

    /**
     * Twitter access token
     *
     * @var string
     */
    protected $accessToken;

    /**
     * Twitter access token secret
     *
     * @var string
     */
    protected $accessTokenSecret;

    /**
     * Twitter's app OAuth consumer key
     *
     * @var string
     */
    protected $oauthConsumerKey;

    /**
     * Twitter's app OAuth consumer secret
     *
     * @var string
     */
    protected $oauthConsumerSecret;

    public function __construct(array $options = array())
    {
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

    /**
     *
     * @param array $query_params
     * @return array
     */
    protected function _assembleParams(array $query_params = array())
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

    /**
     *
     * @return array
     */
    public function getAuthParams()
    {
        $params = array();
        $params = $this->_assembleParams($this->getRequestQuery());

        $sig_base_string = $this->_getBaseSignatureString(
            $params,
            $this->getRequestMethod(),
            $this->getRequestUrlWithPath());

        $key = $this->getKeyForSignature();
        $binary_sig = hash_hmac(self::SIGNATURE_METHOD, $sig_base_string, $key, true);

        $params['oauth_signature'] = $this->_urlEncode(base64_encode($binary_sig));
        return $params;
    }


    public function getAuthHeaderForParams(array $auth_params)
    {
        $header_content_type = "Content-Type: application/x-www-form-urlencoded";

        $header_authz = "Authorization: OAuth ".
            "oauth_consumer_key=\"{$this->getOauthConsumerKey()}\", ".
            "oauth_nonce=\"{$auth_params['oauth_nonce']}\", ".
            "oauth_signature=\"{$auth_params['oauth_signature']}\", ".
            "oauth_signature_method=\"HMAC-SHA1\", ".
            "oauth_timestamp=\"{$auth_params['oauth_timestamp']}\", ".
            "oauth_token=\"{$this->getAccessToken()}\", ".
            "oauth_version=\"".self::OAUTH_VERSION."\"";

        return array(
            $header_content_type,
            $header_authz
        );
    }

    public function setRequestUrl($url)
    {
        $this->requestUrl = $url;
        return $this;
    }

    public function getRequestUrl()
    {
        return $this->requestUrl;
    }

    public function setRequestPath($path)
    {
        $this->requestPath = $path;
        return $this;
    }

    public function getRequestPath()
    {
        return $this->requestPath;
    }

    public function setRequestQuery($query)
    {
        $this->requestQuery = $query;
        return $this;
    }

    public function getRequestQuery()
    {
        return $this->requestQuery;
    }

    public function setRequestMethod($method)
    {
        $this->requestMethod = $method;
        return $this;
    }

    public function getRequestMethod()
    {
        return $this->requestMethod;
    }

    protected function getRequestUrlWithPath()
    {
        return $this->getRequestUrl().$this->getRequestPath();
    }

    protected function getKeyForSignature()
    {
        return "{$this->getOauthConsumerSecret()}&{$this->getAccessTokenSecret()}";
    }
}