<?php

namespace App;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;

class UserService
{

    public function __construct(
        private readonly Client $client = new Client(),
        private readonly string $baseUrl = 'https://reqres.in/api'
    ) {}

        /**
         * Retrieve a single user by ID.
         *
         * @param int $userId
         * @return array
         * @throws Exception
         */
        public function getUserById(int $userId): array
        {
            try {
                $response = $this->client->get("{$this->baseUrl}/users/{$userId}");
                $data = json_decode($response->getBody()->getContents(), true);
                return [
                    'data' => new UserCollection($data['data']),
                    'message' => 'Single user retrieved successfully',
                    'status' => 'success',
                    'statusCode' => 200,
                ];
            } catch (RequestException|GuzzleException $exception) {
                $response = $exception->getResponse();
                if ($response && $response->getStatusCode() === 404) {
                    return [
                      'message' => "User not found.",
                      'status' => 'error',
                      'statusCode' => 404,
                    ];
                }

                return [
                    'status' => 'error',
                    'statusCode' => $exception->getCode(),
                    'message' => 'Unable to retrieve user: ' . $exception->getMessage()
                ];
            }
        }

    /**
     * Retrieve a paginated list of users.
     *
     * @param int $page
     * @return array
     * @throws Exception
     */
    public function getUsers(int $page = 1): array
    {
        try {
            $response = $this->client->get("{$this->baseUrl}/users", [
                'query' => ['page' => $page]
            ]);

            $responseData = json_decode($response->getBody()->getContents(), true);

            if (empty($responseData['data'])) {
                return [
                    'data' => [],
                    'message' => 'No users found.',
                    'status' => 'success',
                    'statusCode' => 200,
                ];
            }

            $users = array_map(fn($userData) => new UserCollection($userData), $responseData['data']);

            return [
                'data' => $users,
                'page' => $responseData['page'],
                'per_page' => $responseData['per_page'],
                'total' => $responseData['total'],
                'total_pages' => $responseData['total_pages'],
                'message' => 'User data retrieved successfully.',
                'status' => 'success',
                'statusCode' => 200,
            ];

        } catch (RequestException|GuzzleException $exception) {
            return [
                'message' => 'Unable to retrieve user: ' . $exception->getMessage(),
                'status' => 'error',
                'statusCode' => $exception->getCode(),
            ];
        }
    }

    /**
     * Create a new user with a name and job.
     *
     * @param string $name
     * @param string $job
     * @return array
     * @throws Exception
     */
    public function createUser(string $name, string $job): array
    {
        if (empty($name)) {
            return [
                'message' => "The 'name' field is required and must not be empty.",
                'status' => 'error',
                'statusCode' => 422,
            ];
        }

        if (empty($job)) {
            return [
                'message' => "The 'job' field is required and must not be empty.",
                'status' => 'error',
                'statusCode' => 422,
            ];
        }

        try {
            $response = $this->client->post("{$this->baseUrl}/users", [
                'json' => [
                    'name' => $name,
                    'job' => $job
                ]
            ]);

            $data = json_decode($response->getBody()->getContents(), true);

            if (!isset($data['id'])) {
                return [
                    'message' => 'Failed to create user.',
                    'status' => 'error',
                    'statusCode' => 500
                ];
            }

            return [
                'data' => new UserCollection($data),
                'extras' => $data,
                'message' => 'User created successfully.',
                'status' => 'success',
                'statusCode' => 201,
            ];
        } catch (RequestException|GuzzleException $exception) {
            return [
                'status' => 'error',
                'statusCode' => $exception->getCode(),
                'message' => 'Unable to create user: ' . $exception->getMessage()
            ];
        }
    }
}