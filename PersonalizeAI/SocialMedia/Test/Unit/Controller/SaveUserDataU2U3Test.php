<?php
namespace PersonalizeAI\SocialMedia\Test\Unit\Controller\Oauth;

use PHPUnit\Framework\TestCase;
use PersonalizeAI\SocialMedia\Controller\Oauth\SaveUserData;
use Magento\Framework\App\Action\Context;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\App\RequestInterface;

// Document is for UnitTest 2 -- and 3 
class SaveUserDataU2U3Test extends TestCase
{
    protected $controller; // The controller being tested
    protected $contextMock; // Mock for the action context
    protected $customerSessionMock; // Mock for the customer session
    protected $resultJsonFactoryMock; // Mock for the JSON result factory
    protected $requestMock; // Mock for the request
    protected $resultJsonMock; // Mock for the JSON result

    protected function setUp(): void
    {
        // Create a mock for the context
        $this->contextMock = $this->createMock(Context::class);
        
        // Create a mock for the customer session with expected setter methods
        $this->customerSessionMock = $this->getMockBuilder(CustomerSession::class)
            ->disableOriginalConstructor()
            ->addMethods([
                'setFacebookId', 'setFacebookFirstName', 'setFacebookLastName', 'setFacebookName',
                'setFacebookEmail', 'setFacebookProfilePicUrl', 'setFacebookGender', 'setFacebookBirthday',
                'setFacebookHometown', 'setFacebookLocation', 'setFacebookCountry', 'setFacebookFriends',
                'setFacebookPosts', 'setFacebookLikes', 'setFacebookLanguages', 'setFacebookFavoriteTeams'
            ])
            ->getMock();

        // Create a mock for the JSON result factory
        $this->resultJsonFactoryMock = $this->createMock(JsonFactory::class);
        
        // Create a mock for the request
        $this->requestMock = $this->createMock(RequestInterface::class);
        
        // Create a mock for the JSON result
        $this->resultJsonMock = $this->createMock(Json::class);

        // Configure the context mock to return the request mock
        $this->contextMock->expects($this->any())
            ->method('getRequest')
            ->willReturn($this->requestMock);

        // Configure the JSON result factory mock to return the JSON result mock
        $this->resultJsonFactoryMock->expects($this->any())
            ->method('create')
            ->willReturn($this->resultJsonMock);

        // Instantiate the controller with mocked dependencies
        $this->controller = new SaveUserData(
            $this->contextMock,
            $this->customerSessionMock,
            $this->resultJsonFactoryMock
        );
    }

    public function testExecuteWithValidData()
    {
        // Arrange: Set up valid user data
        $userData = [
            'id' => '123456',
            'firstname' => 'John',
            'lastname' => 'Doe',
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'profile_pic_url' => 'http://example.com/pic.jpg',
            'gender' => 'male',
            'birthday' => '1990-01-01',
            'hometown' => 'New York',
            'location' => 'Los Angeles',
            'country' => 'US',
            'friends' => ['friend1', 'friend2'],
            'posts' => ['post1', 'post2'],
            'likes' => ['like1', 'like2'],
            'languages' => ['English', 'Spanish'],
            'favorite_teams' => ['team1', 'team2']
        ];

        // Configure the request to return parameters from userData
        $this->requestMock->expects($this->any())
            ->method('getParam')
            ->willReturnCallback(function ($key) use ($userData) {
                return $userData[$key];
            });

        // Check that all expected setter methods are called with correct values
        foreach ($userData as $key => $value) {
            $method = 'setFacebook' . str_replace('_', '', ucwords($key, '_'));
            $this->customerSessionMock->expects($this->once())
                ->method($method)
                ->with($value) // Expect that the setter is called with the correct value
                ->willReturnSelf();
        }

    // ## Unit test 3 ## Check that setData is called with the expected success message 
        $this->resultJsonMock->expects($this->once())
            ->method('setData')
            ->with(['success' => true, 'message' => 'User data saved successfully'])
            ->willReturnSelf();

        // Act: Execute the controller's method and get the result
        $result = $this->controller->execute();

        // Assert: Verify that the result is equal to the mocked JSON result
        $this->assertSame($this->resultJsonMock, $result);
    }

    public function testExecuteWithInvalidData()
    {
        // Arrange: Simulate invalid data by returning null for all parameters
        $this->requestMock->expects($this->any())
            ->method('getParam')
            ->willReturn(null);

        // Check that setData is called with the expected error message
        $this->resultJsonMock->expects($this->once())
            ->method('setData')
            ->with(['success' => false, 'message' => 'Invalid or incomplete user data provided'])
            ->willReturnSelf();

        // Act: Execute the controller's method and get the result
        $result = $this->controller->execute();

        // Assert: Verify that the result is equal to the mocked JSON result
        $this->assertSame($this->resultJsonMock, $result);
    }
}
