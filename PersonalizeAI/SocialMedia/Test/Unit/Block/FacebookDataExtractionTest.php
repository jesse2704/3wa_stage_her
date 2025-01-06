<?php
namespace PersonalizeAI\SocialMedia\Test\Unit\Controller\Oauth;

use PersonalizeAI\SocialMedia\Controller\Oauth\SaveUserData;
use Magento\Framework\App\Request\Http as Request;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Customer\Model\Session as CustomerSession;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class SaveUserDataTest extends TestCase
{
    /**
     * @var SaveUserData
     */
    private $saveUserDataController;

    /**
     * @var CustomerSession|MockObject
     */
    private $customerSessionMock;

    /**
     * @var JsonFactory|MockObject
     */
    private $resultJsonFactoryMock;

    /**
     * @var Request|MockObject
     */
    private $requestMock;

    protected function setUp(): void
    {
        $this->customerSessionMock = $this->createMock(CustomerSession::class);
        $this->resultJsonFactoryMock = $this->createMock(JsonFactory::class);
        $this->requestMock = $this->createMock(Request::class);

        // Create the controller instance with mocked dependencies
        $this->saveUserDataController = new SaveUserData(
            new \Magento\Framework\App\Action\Context(
                $this->createMock(\Magento\Framework\App\RequestInterface::class),
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                null
            ),
            $this->customerSessionMock,
            $this->resultJsonFactoryMock
        );
    }

    public function testExecuteWithValidData()
    {
        // Mocking the request parameters
        $userData = [
            'id' => '123456789',
            'firstname' => 'test',
            'lastname' => 'user',
            'name' => 'test user',
            'email' => 'test.user@example.com',
            'profile_picture_url' => 'https://example.com/profile.jpg',
            'gender' => 'male',
            'birthday' => '2000-01-01',
            'hometown' => 'The Hague',
            'location' => 'The Hague',
            'country' => 'NL',
            'friends' => ['Friend A', 'Friend B'],
            'posts' => [['message' => 'Hello world!', 'created_time' => '2023-01-01T12:00:00+0000']],
            'likes' => ['Page A', 'Page B'],
            'languages' => ['English'],
            'favorite_teams' => ['Team A']
        ];

        // Set up the request mock to return user data
        $this->requestMock->method('getParam')->will($this->returnCallback(function ($param) use ($userData) {
            return isset($userData[$param]) ? $userData[$param] : null;
        }));

        // Set up the JSON result factory mock
        $resultJsonMock = $this->createMock(ResponseInterface::class);
        $this->resultJsonFactoryMock->method('create')->willReturn($resultJsonMock);

        // Expect session methods to be called with proper data
        $this->customerSessionMock->expects($this->once())->method('setFacebookId')->with($userData['id']);
        $this->customerSessionMock->expects($this->once())->method('setFacebookFirstName')->with($userData['firstname']);
        $this->customerSessionMock->expects($this->once())->method('setFacebookLastName')->with($userData['lastname']);
        // ... (repeat for other expected session methods)

        // Expect JSON response for success case
        $resultJsonMock->expects($this->once())->method('setData')->with(['success' => true, 'message' => 'User data saved successfully']);

        // Execute the controller action
        $response = $this->saveUserDataController->execute();

        // Assert that the response is as expected (success)
        $this->assertEquals(['success' => true, 'message' => 'User data saved successfully'], json_decode($response->getContent(), true));
    }

    public function testExecuteWithInvalidData()
    {
        // Mocking invalid user data (e.g., missing required fields)
        $invalidUserData = [
            // Missing required fields like id, name, or email
            'firstname' => '',
            // other fields...
        ];

        // Set up the request mock to return invalid user data
        $this->requestMock->method('getParam')->will($this->returnCallback(function ($param) use ($invalidUserData) {
            return isset($invalidUserData[$param]) ? $invalidUserData[$param] : null;
        }));

        // Set up the JSON result factory mock
        $resultJsonMock = $this->createMock(ResponseInterface::class);
        $this->resultJsonFactoryMock->method('create')->willReturn($resultJsonMock);

        // Expect JSON response for failure case due to invalid data
        $resultJsonMock->expects($this->once())->method('setData')->with(['success' => false, 'message' => 'Invalid or incomplete user data provided']);

        // Execute the controller action with invalid data
        $response = $this->saveUserDataController->execute();

        // Assert that the response indicates failure due to invalid data
        $this->assertEquals(['success' => false, 'message' => 'Invalid or incomplete user data provided'], json_decode($response->getContent(), true));
    }
}
