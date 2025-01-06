<?php
namespace PersonalizeAI\SocialMedia\Test\Unit\Controller\Oauth;

use PersonalizeAI\SocialMedia\Controller\Oauth\SaveUserData;
use Magento\Framework\App\Action\Context;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\Controller\Result\JsonFactory;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class SaveUserDataTest extends TestCase
{
    /**
     * @var SaveUserData
     */
    private $controller;

    /**
     * @var CustomerSession|MockObject
     */
    private $customerSessionMock;

    /**
     * @var JsonFactory|MockObject
     */
    private $resultJsonFactoryMock;

    /**
     * @var \Magento\Framework\App\RequestInterface|MockObject
     */
    private $requestMock;

    protected function setUp(): void
    {
        $this->customerSessionMock = $this->createMock(CustomerSession::class);
        $this->resultJsonFactoryMock = $this->createMock(JsonFactory::class);
        $this->requestMock = $this->createMock(\Magento\Framework\App\RequestInterface::class);

        // Mocking the Result object
        $resultJsonMock = $this->createMock(\Magento\Framework\Controller\Result\Json::class);
        $this->resultJsonFactoryMock->method('create')->willReturn($resultJsonMock);

        // Setting up the controller with mocked dependencies
        $context = new Context(
            $this->requestMock,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null
        );

        $this->controller = new SaveUserData($context, $this->customerSessionMock, $this->resultJsonFactoryMock);
    }

    public function testExecuteWithValidData()
    {
        // Mocking request parameters
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
            'location' => ['country' => 'NL'],
            'friends' => ['Friend A', 'Friend B'],
            'posts' => [['message' => 'Hello world!', 'created_time' => '2023-01-01T12:00:00+0000']],
            'likes' => ['Page A', 'Page B'],
            'languages' => ['English'],
            'favorite_teams' => ['Team A']
        ];

        // Setting up request parameters
        foreach ($userData as $key => $value) {
            $this->requestMock->method('getParam')->with($key)->willReturn($value);
        }

        // Setting the request in the controller context
        $this->controller->setRequest($this->requestMock);

        // Expecting session methods to be called with correct parameters
        foreach ($userData as $key => $value) {
            if (strpos($key, '_') === false) { // Skip keys that are not meant to be set directly in session
                $methodName = "setFacebook" . ucfirst($key);
                if (method_exists($this->customerSessionMock, $methodName)) {
                    $this->customerSessionMock->expects($this->once())
                        ->method($methodName)
                        ->with($value);
                }
            }
        }

        // Execute the controller action
        /** @var \Magento\Framework\Controller\Result\Json|\PHPUnit\Framework\MockObject\Stub|\PHPUnit\Framework\ExpectationFailedException */
        $resultJsonMock = $this->createMock(\Magento\Framework\Controller\Result\Json::class);
        
        // Set up the result factory to return our mock result object.
        $this->resultJsonFactoryMock->method('create')->willReturn($resultJsonMock);

        // Expecting a successful response
        $resultJsonMock->expects($this->once())
                       ->method('setData')
                       ->with(['success' => true, 'message' => 'User data saved successfully']);

        // Execute the action and assert results
        $result = $this->controller->execute();
        
        // Assert that the result is a JSON response with success message
        $this->assertEquals(['success' => true, 'message' => 'User data saved successfully'], $result->getData());
    }

    public function testExecuteWithInvalidData()
    {
        // Mocking request parameters with missing required fields (e.g., id)
        $userData = [
            // Missing required fields like id, name, email...
            'firstname' => '',
        ];

        // Setting up request parameters
        foreach ($userData as $key => $value) {
            $this->requestMock->method('getParam')->with($key)->willReturn($value);
        }

        // Setting the request in the controller context
        $this->controller->setRequest($this->requestMock);

        // Execute the controller action
        /** @var \Magento\Framework\Controller\Result\Json|\PHPUnit\Framework\MockObject\Stub|\PHPUnit\Framework\ExpectationFailedException */
        $resultJsonMock = $this->createMock(\Magento\Framework\Controller\Result\Json::class);
        
         // Set up the result factory to return our mock result object.
         $this->resultJsonFactoryMock->method('create')->willReturn($resultJsonMock);

         // Expecting an error response due to invalid data
         $resultJsonMock->expects($this->once())
                        ->method('setData')
                        ->with(['success' => false, 
                                'message' => 'Invalid or incomplete user data provided']);

         // Execute the action and assert results
         $result = $this->controller->execute();
         
         // Assert that the result indicates failure due to invalid data
         $this->assertEquals(['success' => false, 
                              'message' => 'Invalid or incomplete user data provided'], 
                              $result->getData());
    }
}
