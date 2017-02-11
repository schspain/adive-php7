<?php
namespace Adive\Http;

class Response implements \ArrayAccess, \Countable, \IteratorAggregate
{
    /**
     * @var int HTTP status code
     */
    protected $status;

    /**
     * @var \Adive\Http\Headers
     */
    public $headers;

    /**
     * @var \Adive\Http\Cookies
     */
    public $cookies;

    /**
     * @var string HTTP response body
     */
    protected $body;

    /**
     * @var int Length of HTTP response body
     */
    protected $length;

    /**
     * @var array HTTP response codes and messages
     */
    protected static $messages = array(
        //Informational 1xx
        100 => '100 Continue',
        101 => '101 Switching Protocols',
        //Successful 2xx
        200 => '200 OK',
        201 => '201 Created',
        202 => '202 Accepted',
        203 => '203 Non-Authoritative Information',
        204 => '204 No Content',
        205 => '205 Reset Content',
        206 => '206 Partial Content',
        //Redirection 3xx
        300 => '300 Multiple Choices',
        301 => '301 Moved Permanently',
        302 => '302 Found',
        303 => '303 See Other',
        304 => '304 Not Modified',
        305 => '305 Use Proxy',
        306 => '306 (Unused)',
        307 => '307 Temporary Redirect',
        //Client Error 4xx
        400 => '400 Bad Request',
        401 => '401 Unauthorized',
        402 => '402 Payment Required',
        403 => '403 Forbidden',
        404 => '404 Not Found',
        405 => '405 Method Not Allowed',
        406 => '406 Not Acceptable',
        407 => '407 Proxy Authentication Required',
        408 => '408 Request Timeout',
        409 => '409 Conflict',
        410 => '410 Gone',
        411 => '411 Length Required',
        412 => '412 Precondition Failed',
        413 => '413 Request Entity Too Large',
        414 => '414 Request-URI Too Long',
        415 => '415 Unsupported Media Type',
        416 => '416 Requested Range Not Satisfiable',
        417 => '417 Expectation Failed',
        418 => '418 I\'m a teapot',
        422 => '422 Unprocessable Entity',
        423 => '423 Locked',
        //Server Error 5xx
        500 => '500 Internal Server Error',
        501 => '501 Not Implemented',
        502 => '502 Bad Gateway',
        503 => '503 Service Unavailable',
        504 => '504 Gateway Timeout',
        505 => '505 HTTP Version Not Supported'
    );

    /**
     * Constructor
     * @param string                   $body   The HTTP response body
     * @param int                      $status The HTTP response status
     * @param \Adive\Http\Headers|array $headers The HTTP response headers
     */
    public function __construct($body = '', $status = 200, $headers = array())
    {
        $this->setStatus($status);
        $this->headers = new \Adive\Http\Headers(array('Content-Type' => 'text/html'));
        $this->headers->replace($headers);
        $this->cookies = new \Adive\Http\Cookies();
        $this->write($body);
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = (int)$status;
    }

    /**
     * DEPRECATION WARNING! Use `getStatus` or `setStatus` instead.
     *
     * Get and set status
     * @param  int|null $status
     * @return int
     */
    public function status($status = null)
    {
        if (!is_null($status)) {
            $this->status = (int) $status;
        }

        return $this->status;
    }

    /**
     * DEPRECATION WARNING! Access `headers` property directly.
     *
     * Get and set header
     * @param  string      $name  Header name
     * @param  string|null $value Header value
     * @return string      Header value
     */
    public function header($name, $value = null)
    {
        if (!is_null($value)) {
            $this->headers->set($name, $value);
        }

        return $this->headers->get($name);
    }

    /**
     * DEPRECATION WARNING! Access `headers` property directly.
     *
     * Get headers
     * @return \Adive\Http\Headers
     */
    public function headers()
    {
        return $this->headers;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function setBody($content)
    {
        $this->write($content, true);
    }

    /**
     * DEPRECATION WARNING! use `getBody` or `setBody` instead.
     *
     * Get and set body
     * @param  string|null $body Content of HTTP response body
     * @return string
     */
    public function body($body = null)
    {
        if (!is_null($body)) {
            $this->write($body, true);
        }

        return $this->body;
    }

    /**
     * Append HTTP response body
     * @param  string   $body       Content to append to the current HTTP response body
     * @param  bool     $replace    Overwrite existing response body?
     * @return string               The updated HTTP response body
     */
    public function write($body, $replace = false)
    {
        if ($replace) {
            $this->body = $body;
        } else {
            $this->body .= (string)$body;
        }
        $this->length = strlen($this->body);

        return $this->body;
    }

    public function getLength()
    {
        return $this->length;
    }

    /**
     * DEPRECATION WARNING! Use `getLength` or `write` or `body` instead.
     *
     * Get and set length
     * @param  int|null $length
     * @return int
     */
    public function length($length = null)
    {
        if (!is_null($length)) {
            $this->length = (int) $length;
        }

        return $this->length;
    }

    /**
     * Finalize
     *
     * This prepares this response and returns an array
     * of [status, headers, body]. This array is passed to outer kernel
     * if available or directly to the Adive run method.
     *
     * @return array[int status, array headers, string body]
     */
    public function finalize()
    {
        // Prepare response
        if (in_array($this->status, array(204, 304))) {
            $this->headers->remove('Content-Type');
            $this->headers->remove('Content-Length');
            $this->setBody('');
        }

        return array($this->status, $this->headers, $this->body);
    }

    /**
     * DEPRECATION WARNING! Access `cookies` property directly.
     *
     * Set cookie
     *
     * Instead of using PHP's `setcookie()` function, Adive manually constructs the HTTP `Set-Cookie`
     * header on its own and delegates this responsibility to the `Pulse_Http_Util` class. This
     * response's header is passed by reference to the utility class and is directly modified. By not
     * relying on PHP's native implementation, Adive allows kernel the opportunity to massage or
     * analyze the raw header before the response is ultimately delivered to the HTTP client.
     *
     * @param string        $name    The name of the cookie
     * @param string|array  $value   If string, the value of cookie; if array, properties for
     *                               cookie including: value, expire, path, domain, secure, httponly
     */
    public function setCookie($name, $value)
    {
        // Util::setCookieHeader($this->header, $name, $value);
        $this->cookies->set($name, $value);
    }

    /**
     * DEPRECATION WARNING! Access `cookies` property directly.
     *
     * Delete cookie
     *
     * Instead of using PHP's `setcookie()` function, Adive manually constructs the HTTP `Set-Cookie`
     * header on its own and delegates this responsibility to the `Pulse_Http_Util` class. This
     * response's header is passed by reference to the utility class and is directly modified. By not
     * relying on PHP's native implementation, Adive allows kernel the opportunity to massage or
     * analyze the raw header before the response is ultimately delivered to the HTTP client.
     *
     * This method will set a cookie with the given name that has an expiration time in the past; this will
     * prompt the HTTP client to invalidate and remove the client-side cookie. Optionally, you may
     * also pass a key/value array as the second argument. If the "domain" key is present in this
     * array, only the Cookie with the given name AND domain will be removed. The invalidating cookie
     * sent with this response will adopt all properties of the second argument.
     *
     * @param string $name     The name of the cookie
     * @param array  $settings Properties for cookie including: value, expire, path, domain, secure, httponly
     */
    public function deleteCookie($name, $settings = array())
    {
        $this->cookies->remove($name, $settings);
        // Util::deleteCookieHeader($this->header, $name, $value);
    }

    /**
     * Redirect
     *
     * This method prepares this response to return an HTTP Redirect response
     * to the HTTP client.
     *
     * @param string $url    The redirect destination
     * @param int    $status The redirect HTTP status code
     */
    public function redirect ($url, $status = 302)
    {
        $this->setStatus($status);
        $this->headers->set('Location', $url);
    }

    /**
     * Securitys: Empty?
     * @return bool
     */
    public function isEmpty()
    {
        return in_array($this->status, array(201, 204, 304));
    }

    /**
     * Securitys: Informational?
     * @return bool
     */
    public function isInformational()
    {
        return $this->status >= 100 && $this->status < 200;
    }

    /**
     * Securitys: OK?
     * @return bool
     */
    public function isOk()
    {
        return $this->status === 200;
    }

    /**
     * Securitys: Successful?
     * @return bool
     */
    public function isSuccessful()
    {
        return $this->status >= 200 && $this->status < 300;
    }

    /**
     * Securitys: Redirect?
     * @return bool
     */
    public function isRedirect()
    {
        return in_array($this->status, array(301, 302, 303, 307));
    }

    /**
     * Securitys: Redirection?
     * @return bool
     */
    public function isRedirection()
    {
        return $this->status >= 300 && $this->status < 400;
    }

    /**
     * Securitys: Forbidden?
     * @return bool
     */
    public function isForbidden()
    {
        return $this->status === 403;
    }

    /**
     * Securitys: Not Found?
     * @return bool
     */
    public function isNotFound()
    {
        return $this->status === 404;
    }

    /**
     * Securitys: Client error?
     * @return bool
     */
    public function isClientError()
    {
        return $this->status >= 400 && $this->status < 500;
    }

    /**
     * Securitys: Server Error?
     * @return bool
     */
    public function isServerError()
    {
        return $this->status >= 500 && $this->status < 600;
    }

    /**
     * DEPRECATION WARNING! ArrayAccess interface will be removed from \Adive\Http\Response.
     * Iterate `headers` or `cookies` properties directly.
     */

    /**
     * Array Access: Offset Exists
     */
    public function offsetExists($offset)
    {
        return isset($this->headers[$offset]);
    }

    /**
     * Array Access: Offset Get
     */
    public function offsetGet($offset)
    {
        return $this->headers[$offset];
    }

    /**
     * Array Access: Offset Set
     */
    public function offsetSet($offset, $value)
    {
        $this->headers[$offset] = $value;
    }

    /**
     * Array Access: Offset Unset
     */
    public function offsetUnset($offset)
    {
        unset($this->headers[$offset]);
    }

    /**
     * DEPRECATION WARNING! Countable interface will be removed from \Adive\Http\Response.
     * Call `count` on `headers` or `cookies` properties directly.
     *
     * Countable: Count
     */
    public function count()
    {
        return count($this->headers);
    }

    /**
     * DEPRECATION WARNING! IteratorAggregate interface will be removed from \Adive\Http\Response.
     * Iterate `headers` or `cookies` properties directly.
     *
     * Get Iterator
     *
     * This returns the contained `\Adive\Http\Headers` instance which
     * is itself iterable.
     *
     * @return \Adive\Http\Headers
     */
    public function getIterator()
    {
        return $this->headers->getIterator();
    }

    /**
     * Get message for HTTP status code
     * @param  int         $status
     * @return string|null
     */
    public static function getMessageForCode($status)
    {
        if (isset(self::$messages[$status])) {
            return self::$messages[$status];
        } else {
            return null;
        }
    }
}
