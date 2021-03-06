<?php

namespace Targito\Api;

use Targito\Api\Credentials\CredentialsInterface;
use Targito\Api\Endpoint\TargitoContactEndpoint;
use Targito\Api\Endpoint\TargitoTransactEndpoint;
use Targito\Api\Http\HttpRequestInterface;
use Targito\Api\Http\Request\CurlHttpRequest;
use Targito\Api\Http\Request\StreamHttpRequest;

final class TargitoApi
{
    public const API_URL = 'https://api.targito.com/v1.0';

    /**
     * @var CredentialsInterface
     */
    private $credentials;

    /**
     * @var HttpRequestInterface
     */
    private $httpRequest;

    /**
     * @var string
     */
    private $apiUrl;

    /**
     * Constructs the api object, if no httpRequest implementation is provided, default one is used
     *
     * @param CredentialsInterface      $credentials
     * @param HttpRequestInterface|null $httpRequest
     * @param string                    $apiUrl
     */
    public function __construct(
        CredentialsInterface $credentials,
        ?HttpRequestInterface $httpRequest = null,
        string $apiUrl = self::API_URL
    ) {
        if ($httpRequest === null) {
            if (extension_loaded('curl')) {
                $httpRequest = new CurlHttpRequest();
            } else {
                $httpRequest = new StreamHttpRequest();
            }
        }
        $this->credentials = $credentials;
        $this->httpRequest = $httpRequest;
        $this->apiUrl = $apiUrl;
    }

    public function contacts(): TargitoContactEndpoint
    {
        return new TargitoContactEndpoint($this->credentials, $this->httpRequest, $this->apiUrl);
    }

    public function transact(): TargitoTransactEndpoint
    {
        return new TargitoTransactEndpoint($this->credentials, $this->httpRequest, $this->apiUrl);
    }
}
