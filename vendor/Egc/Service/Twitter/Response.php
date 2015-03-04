<?php


namespace Egc\Service\Twitter;

use Egc\Mvc\Model;
class Response
{
    /**#@+
     * @const int Status codes
     */
    const STATUS_CODE_CUSTOM = 0;
    const STATUS_CODE_100 = 100;
    const STATUS_CODE_101 = 101;
    const STATUS_CODE_102 = 102;
    const STATUS_CODE_200 = 200;
    const STATUS_CODE_201 = 201;
    const STATUS_CODE_202 = 202;
    const STATUS_CODE_203 = 203;
    const STATUS_CODE_204 = 204;
    const STATUS_CODE_205 = 205;
    const STATUS_CODE_206 = 206;
    const STATUS_CODE_207 = 207;
    const STATUS_CODE_208 = 208;
    const STATUS_CODE_300 = 300;
    const STATUS_CODE_301 = 301;
    const STATUS_CODE_302 = 302;
    const STATUS_CODE_303 = 303;
    const STATUS_CODE_304 = 304;
    const STATUS_CODE_305 = 305;
    const STATUS_CODE_306 = 306;
    const STATUS_CODE_307 = 307;
    const STATUS_CODE_400 = 400;
    const STATUS_CODE_401 = 401;
    const STATUS_CODE_402 = 402;
    const STATUS_CODE_403 = 403;
    const STATUS_CODE_404 = 404;
    const STATUS_CODE_405 = 405;
    const STATUS_CODE_406 = 406;
    const STATUS_CODE_407 = 407;
    const STATUS_CODE_408 = 408;
    const STATUS_CODE_409 = 409;
    const STATUS_CODE_410 = 410;
    const STATUS_CODE_411 = 411;
    const STATUS_CODE_412 = 412;
    const STATUS_CODE_413 = 413;
    const STATUS_CODE_414 = 414;
    const STATUS_CODE_415 = 415;
    const STATUS_CODE_416 = 416;
    const STATUS_CODE_417 = 417;
    const STATUS_CODE_418 = 418;
    const STATUS_CODE_422 = 422;
    const STATUS_CODE_423 = 423;
    const STATUS_CODE_424 = 424;
    const STATUS_CODE_425 = 425;
    const STATUS_CODE_426 = 426;
    const STATUS_CODE_428 = 428;
    const STATUS_CODE_429 = 429;
    const STATUS_CODE_431 = 431;
    const STATUS_CODE_500 = 500;
    const STATUS_CODE_501 = 501;
    const STATUS_CODE_502 = 502;
    const STATUS_CODE_503 = 503;
    const STATUS_CODE_504 = 504;
    const STATUS_CODE_505 = 505;
    const STATUS_CODE_506 = 506;
    const STATUS_CODE_507 = 507;
    const STATUS_CODE_508 = 508;
    const STATUS_CODE_511 = 511;
    /**#@-*/

    /**
     * @var Model
     */
    protected $httpResponse;

    protected $rawResponse;

    /**
     * @var array|\stdClass
     */
    protected $jsonBody;

    public function __construct($httpResponse)
    {
        $this->rawResponse = $httpResponse;
        $this->parseRawResponse($this->rawResponse);

        try {
            $jsonBody = json_decode($this->httpResponse->getContent());
            $this->jsonBody = $jsonBody;
        } catch (Exception $e) {
            throw new Exception(sprintf(
                'Unable to decode response from twitter: %s',
                $e->getMessage()
            ));
        }
    }

    protected function parseRawResponse($raw_response)
    {
        $lines = explode("\r\n", $raw_response);
        if (!is_array($lines) || count($lines) == 1) {
            $lines = explode("\n", $raw_response);
        }

        $firstLine = array_shift($lines);

        $response = new Model();

        $regex   = '/^HTTP\/(?P<version>1\.[01]) (?P<status>\d{3})(?:[ ]+(?P<reason>.*))?$/';
        $matches = array();
        if (!preg_match($regex, $firstLine, $matches)) {
            throw new Exception\InvalidArgumentException(
                'A valid response status line was not found in the provided string'
            );
        }

        $response->setVersion($matches['version']);
        $response->setStatusCode($matches['status']);
        $response->setReasonPhrase((isset($matches['reason']) ? $matches['reason'] : ''));

        if (count($lines) == 0) {
            return $response;
        }

        $isHeader = true;
        $headers = $content = array();

        foreach ($lines as $line) {
            if ($isHeader && $line == '') {
                $isHeader = false;
                continue;
            }
            if ($isHeader) {
                $headers[] = $line;
            } else {
                $content[] = $line;
            }
        }

        if ($headers) {
            $response->setHeaders(implode("\r\n", $headers));
        }

        if ($content) {
            $response->setContent(implode("\r\n", $content));
        }

        $this->httpResponse = $response;
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
        return ($this->httpResponse->getStatusCode() == self::STATUS_CODE_200);
    }

    /**
     * Did an error occur in the request?
     *
     * @return bool
     */
    public function isError()
    {
        return ($this->httpResponse->getStatusCode() != self::STATUS_CODE_200);
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
