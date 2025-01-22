<?php

use App\UserCollection;
use App\UserService;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;


class UserServiceTest extends TestCase
{
    private MockHandler $mock;
    private UserService $userService;

    /**
     * Sets up the mock handler, handler stack, and client for the test cases.
     */
    protected function setUp(): void
    {
        $this->mock = new MockHandler();
        $handlerStack = HandlerStack::create($this->mock);
        $client = new Client(['handler' => $handlerStack]);
        $this->userService = new UserService($client);
    }

    /**
     * Tests the successful retrieval of a user by ID.
     *
     * @throws Exception
     * @return void
     */
    public function testGetUserByIdIsSuccess(): void
    {
        $userId = 12;
        $this->mock->append(new Response(200, [], json_encode(['data' => [
            'id' => 121,
            'email' => 'elon.musk@tesla.com',
            'first_name' => 'Elon',
            'last_name' => 'Musk',
            'avatar' => 'https://reqres.in/img/faces/elon-musk-image.jpg'
        ]])));

        $result = $this->userService->getUserById($userId);

        $this->assertEquals('success', $result['status']);
        $this->assertEquals(200, $result['statusCode']);
        $this->assertInstanceOf(UserCollection::class, $result['data']);
    }

    /**
     * Tests the case where a user by the given ID is not found.
     *
     * @throws Exception
     * @return void
     */
    public function testGetUserByIdReturnsUserNotFound(): void
    {
        $userId = 1288989;
        $this->mock->append(new Response(404, [], json_encode(['error' => 'User Not Found'])));

        $result = $this->userService->getUserById($userId);

        $this->assertEquals('error', $result['status']);
        $this->assertEquals(404, $result['statusCode']);
        $this->assertEquals('User not found.', $result['message']);
    }

    /**
     * Tests the successful retrieval of a paginated list of users.
     *
     * @throws Exception
     * @return void
     */
    public function testGetUsersIsSuccess(): void
    {
        $this->mock->append(new Response(200, [], json_encode([
            'page' => 1,
            'per_page' => 6,
            'total' => 12,
            'total_pages' => 2,
            'data' => [
                [
                    'id' => 1,
                    'email' => 'george.best@machester.com.uk',
                    'first_name' => 'George',
                    'last_name' => 'Best',
                    'avatar' => 'https://reqres.in/img/faces/george-best-image.jpg'
                ],
                [
                    'id' => 2,
                    'email' => 'wright.joe@machester.com.uk',
                    'first_name' => 'Wright',
                    'last_name' => 'Joe',
                    'avatar' => 'https://reqres.in/img/faces/wright-joe-image.jpg'
                ]
            ]
        ])));

        $result = $this->userService->getUsers();

        $this->assertEquals('success', $result['status']);
        $this->assertEquals(200, $result['statusCode']);
        $this->assertIsArray($result['data']);
        $this->assertInstanceOf(UserCollection::class, $result['data'][0]);
    }

    /**
     * Tests the successful creation of a user.
     *
     * @throws Exception
     * @return void
     */
    public function testUserCreatedSuccessfully(): void
    {
        $this->mock->append(new Response(201, [], json_encode([
            'id' => 1,
            'name' => 'Paige Jacob',
            'job' => 'Vice President',
            'createdAt' => '2023-04-01T00:00:00.000Z'
        ])));

        $result = $this->userService->createUser('Paige Jacob', 'Vice President');

        $this->assertEquals('success', $result['status']);
        $this->assertEquals(201, $result['statusCode']);
        $this->assertInstanceOf(UserCollection::class, $result['data']);
    }

    /**
     * Tests validation error when the name is empty during user creation.
     *
     * @throws Exception
     * @return void
     */
    public function testCreateUserValidationErrorForName(): void
    {
        $result = $this->userService->createUser('', 'Police Chief');

        $this->assertEquals('error', $result['status']);
        $this->assertEquals(422, $result['statusCode']);
        $this->assertEquals("The 'name' field is required and must not be empty.", $result['message']);
    }

    /**
     * Tests validation error when the job is empty during user creation.
     *
     * @throws Exception
     * @return void
     */
    public function testCreateUserValidationErrorForJob(): void
    {
        $result = $this->userService->createUser('Luke Lammy ', '');

        $this->assertEquals('error', $result['status']);
        $this->assertEquals(422, $result['statusCode']);
        $this->assertEquals("The 'job' field is required and must not be empty.", $result['message']);
    }

    /**
     * Tests the failure case when creating a user with a 500 error.
     *
     * @throws Exception
     * @return void
     */
    public function testCreateUserFail(): void
    {
        $name = 'Ashely Marks';
        $job = 'Trader Worker';

        $this->mock->append(new Response(500, [], json_encode(['error' => 'Internal Server Error'])));

        $result = $this->userService->createUser($name, $job);
        $this->assertEquals('error', $result['status']);
        $this->assertEquals(500, $result['statusCode']);
        $this->assertNotEmpty($result['message'], 'The error message should not be empty');
    }
}
