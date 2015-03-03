<?php


namespace Egc\Service\Twitter;

class Response
{
    /**
     * @var HttpResponse
     */
    protected $httpResponse;

    /**
     * @var array|\stdClass
     */
    protected $jsonBody;

    public function __construct($httpResponse)
    {
        $this->httpResponse = $httpResponse;
        try {
            $jsonBody = json_decode($this->httpResponse);
            $this->jsonBody = $jsonBody;
        } catch (Exception $e) {
            throw new Exception(sprintf(
                'Unable to decode response from twitter: %s',
                $e->getMessage()
            ));
        }
    }

    /**
     * Property overloading to JSON elements
     *
     * If a named property exists within the JSON response returned,
     * proxies to it. Otherwise, returns null.
     *
     * @param  string $name
     * @return mixed
     */
    public function __get($name)
    {
        if (null === $this->jsonBody) {
            return null;
        }
        if (!isset($this->jsonBody->{$name})) {
            return null;
        }
        return $this->jsonBody->{$name};
    }

    /**
     * Was the request successful?
     *
     * @return bool
     */
    public function isSuccess()
    {
        return $this->httpResponse->isSuccess();
    }

    /**
     * Did an error occur in the request?
     *
     * @return bool
     */
    public function isError()
    {
        return !$this->httpResponse->isSuccess();
    }

    /**
     * Retun the decoded response body
     *
     * @return array|\stdClass
     */
    public function toValue()
    {
        return $this->jsonBody;
    }
}
