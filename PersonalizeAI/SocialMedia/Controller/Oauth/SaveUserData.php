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
        $resultJson = $this->resultJsonFactory->create();
        $userData = $this->collectUserData();

        if ($this->validateUserData($userData)) {
            try {
                $this->customerSession->setFacebookId($userData['id']);
                $this->customerSession->setFacebookFirstName($userData['firstname']);
                $this->customerSession->setFacebookLastName($userData['lastname']);
                $this->customerSession->setFacebookName($userData['name']);
                $this->customerSession->setFacebookEmail($userData['email']);
                $this->customerSession->setFacebookProfilePicUrl($userData['profile_pic_url']);
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

                return $resultJson->setData([
                    'success' => true,
                    'message' => 'User data saved successfully'
                ]);
            } catch (\Exception $e) {
                return $resultJson->setData([
                    'success' => false,
                    'message' => 'Error saving user data: ' . $e->getMessage()
                ]);
            }
        }

        return $resultJson->setData([
            'success' => false,
            'message' => 'Invalid or incomplete user data provided'
        ]);
    }

    private function collectUserData()
    {
        return [
            'id' => $this->getRequest()->getParam('id'),
            'firstname' => $this->getRequest()->getParam('firstname'),
            'lastname' => $this->getRequest()->getParam('lastname'),
            'name' => $this->getRequest()->getParam('name'),
            'email' => $this->getRequest()->getParam('email'),
            'profile_pic_url' => $this->getRequest()->getParam('profile_pic_url'),
            'gender' => $this->getRequest()->getParam('gender'),
            'birthday' => $this->getRequest()->getParam('birthday'),
            'hometown' => $this->getRequest()->getParam('hometown'),
            'location' => $this->getRequest()->getParam('location'),
            'country' => $this->getRequest()->getParam('country'),
            'friends' => $this->getRequest()->getParam('friends'),
            'posts' => $this->getRequest()->getParam('posts'),
            'likes' => $this->getRequest()->getParam('likes'),
            'languages' => $this->getRequest()->getParam('languages'),
            'favorite_teams' => $this->getRequest()->getParam('favorite_teams')
        ];
    }

    private function validateUserData($userData)
    {
        return !empty($userData['id']) && !empty($userData['name']) && !empty($userData['email']);
    }
}
