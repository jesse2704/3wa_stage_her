<?php
namespace PersonalizeAI\SocialMedia\Controller\Oauth;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context; 
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\Controller\Result\JsonFactory;

class SaveUserData extends Action
{
    protected $customerSession;
    protected $resultJsonFactory;

    public function __construct(
        Context $context,
        CustomerSession $customerSession,
        JsonFactory $resultJsonFactory
    ) {
        parent::__construct($context);
        $this->customerSession = $customerSession; 
        $this->resultJsonFactory = $resultJsonFactory;
    }

    public function execute()
    {
        $resultJson = $this->resultJsonFactory->create(); // Create a new JSON result object
        $request = $this->getRequest(); // Get the current request object
    
        // Collect user data from request parameters
        $userData = [
            'id' => $request->getParam('id'),
            'firstname' => $request->getParam('firstname'),
            'lastname' => $request->getParam('lastname'),
            'name' => $request->getParam('name'),
            'email' => $request->getParam('email'),
            'profile_picture_url' => $request->getParam('profile_picture_url'),
            'gender' => $request->getParam('gender'),
            'birthday' => $request->getParam('birthday'),
            'hometown' => $request->getParam('hometown'),
            'location' => $request->getParam('location'),
            'country' => $request->getParam('country'),
            'friends' => $request->getParam('friends'),
            'posts' => $request->getParam('posts'),
            'likes' => $request->getparam('likes'),
            'languages' => $request->getParam('languages'),
            'favorite_teams' => $request->getParam('favorite_teams')
        ];
    
        // Validate user data before saving it
        if ($this->validateUserData($userData)) {
            try {
                $this->saveUserData($userData); // Save user data to the session
                return $resultJson->setData(['success' => true, 'message' => 'User data saved successfully']);
            } catch (\Exception $e) {
                return $resultJson->setData(['success' => false, 'message' => 'Error saving user data: ' . $e->getMessage()]);
            }
        }
    
        return $resultJson->setData(['success' => false, 'message' => 'Invalid or incomplete user data provided']);
    }
    
    // Validate required user data fields
    private function validateUserData($userData)
    {
        return !empty($userData['id']) && !empty($userData['name']) && !empty($userData['email']);
    }
    
    // Save user data into the customer session
    private function saveUserData($userData)
    {
        $this->customerSession->setFacebookId($userData['id']);
        $this->customerSession->setFacebookFirstName($userData['firstname']);
        $this->customerSession->setFacebookLastName($userData['lastname']);
        $this->customerSession->setFacebookName($userData['name']);
        $this->customerSession->setFacebookEmail($userData['email']);
        $this->customerSession->setFacebookProfilePicUrl($userData['profile_picture_url']);
        $this->customerSession->setFacebookGender($userData['gender']);
        $this->customerSession->setFacebookBirthday($userData['birthday']);
        $this->customerSession->setFacebookHometown($userData['hometown']);
        $this->customerSession->setFacebookLocation($userData['location']);
        $this->customerSession->setFacebookCountry($userData['country']);
        $this->customerSession->setFacebookFriends($userData['friends']);
        $this->customerSession->setFacebookPosts($userData['posts']);
        $this->customerSession->setFacebookLikes($userData['likes']);
        $this->customerSession->setFacebookLanguages($userData['languages']);
        $this->customerSession->setFacebookFavoriteTeams($userData['favorite_teams']);
    }
}
