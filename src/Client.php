<?php

namespace GraphQL;

use GraphQL\Exception\QueryError;
use GraphQL\QueryBuilder\QueryBuilderInterface;
use GuzzleHttp\Exception\ClientException;
use TypeError;

/**
 * Class Client
 *
 * @package GraphQL
 */
class Client
{
    /**
     * @var string
     */
    protected $endpointUrl;

    /**
     * @var array
     */
    protected $authorizationHeaders;

    /**
     * @var \GuzzleHttp\Client
     */
    protected $httpClient;

    /**
     * Client constructor.
     *
     * @param string $endpointUrl
     * @param array  $authorizationHeaders
     */
    public function __construct(string $endpointUrl, array $authorizationHeaders = [])
    {
        $this->endpointUrl          = $endpointUrl;
        $this->authorizationHeaders = $authorizationHeaders;
        $this->httpClient           = new \GuzzleHttp\Client();
    }

    /**
     * @param Query|QueryBuilderInterface $query
     * @param bool                        $resultsAsArray
     *
     * @return Results|null
     * @throws QueryError
     */
    public function runQuery($query, bool $resultsAsArray = false): ?Results
    {
        if ($query instanceof QueryBuilderInterface) {
            $query = $query->getQuery();
        }

        if (!$query instanceof Query) {
            throw new TypeError('Client::runQuery accepts the first argument of type Query or QueryBuilderInterface');
        }

        return $this->runRawQuery((string) $query, $resultsAsArray);
    }

    /**
     * @param string $queryString
     * @param bool   $resultsAsArray
     *
     * @return Results|null
     * @throws QueryError
     */
    public function runRawQuery(string $queryString, $resultsAsArray = false): ?Results
    {
        // Set request headers for authorization and content type
        if (!empty($this->authorizationHeaders)) {
            $options['headers'] = $this->authorizationHeaders;
        }
        $options['headers']['Content-Type'] = 'application/json';

        // Set query in the request body
        $options['body'] = json_encode(['query' => (string) $queryString]);

        // Send api request and get response
        try {
            $response = $this->httpClient->post($this->endpointUrl, $options);
        }
        catch (ClientException $exception) {
            $response = $exception->getResponse();

            // If exception thrown by client is "400 Bad Request ", then it can be treated as a successful API request
            // with a syntax error in the query, otherwise the exceptions will be propagated
            if ($response->getStatusCode() !== 400) {
                throw $exception;
            }
        }

        // Parse response to extract results
        $results = new Results($response, $resultsAsArray);

        return $results;
    }
}