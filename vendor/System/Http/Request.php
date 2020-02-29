<?php
declare(strict_types=1);

namespace System\Http;

class Request
{
    /**
     * @var string $url
     */
    private $url;
    /**
     * @var string $baseUrl
     */
    private $baseUrl;

    /**
     * prepare url and base url
     */
    public function prepareUrl(): void
    {
        $script=dirname($this->server('SCRIPT_NAME'));
        $requestUri = $this->server('REQUEST_URI');
        if (strpos($requestUri, '?') !== false) {
            list($requestUri, $queryParam) = explode('?', $this->server('REQUEST_URI'));
        }
        $this->url = rtrim(preg_replace('#^'.$script.'#', '' , $requestUri), '/');

        if (! $this->url) {
            $this->url = '/';
        }
        $this->baseUrl = $this->server('REQUEST_SCHEME') . '://' . $this->server('HTTP_HOST') . $script . '';

    }

    /**
     * @param $key
     * @return string
     */
    public function server(string $key): string
    {
        return $_SERVER[$key];
    }

    /**
     * @return string
     */
    public function url(): string
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function baseUrl(): string
    {
        return $this->baseUrl;
    }
}
