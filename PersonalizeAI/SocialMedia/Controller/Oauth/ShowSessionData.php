<?php 
namespace PersonalizeAI\SocialMedia\Controller\Oauth;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Customer\Model\Session;
use Magento\Framework\Controller\Result\JsonFactory;

class ShowSessionData extends Action
{
    protected $customerSession;
    protected $resultJsonFactory;

    public function __construct(
        Context $context,
        Session $customerSession,
        JsonFactory $resultJsonFactory
    ) {
        parent::__construct($context);
        $this->customerSession = $customerSession;
        $this->resultJsonFactory = $resultJsonFactory;
    }

    public function execute()
    {
        // Retrieve Facebook data from the customer session
        $facebookId = $this->customerSession->getFacebookId();
        $facebookName = $this->customerSession->getFacebookName();
        $facebookEmail = $this->customerSession->getFacebookEmail();
        $facebookProfilePicUrl = $this->customerSession->getFacebookProfilePicUrl();
        $facebookGender = $this->customerSession->getFacebookGender();
        $facebookBirthday = $this->customerSession->getFacebookBirthday();
        $facebookHometown = $this->customerSession->getFacebookHometown();
        $facebookLocation = $this->customerSession->getFacebookLocation();
        $facebookCountry = $this->customerSession->getFacebookCountry();
        $facebookPosts = $this->customerSession->getFacebookPosts();
        $facebookLikes = $this->customerSession->getFacebookLikes();
        $facebookLanguages = $this->customerSession->getFacebookLanguages();
        $facebookFavoriteTeams = $this->customerSession->getFacebookFavoriteTeams();

        // Decode friends data from JSON format and handle errors
        $friendsJson = $this->customerSession->getFacebookFriends();
        if ($friendsJson) {
            $facebookFriends = json_decode($friendsJson, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                $facebookFriends = []; 
            }
        } else {
            $facebookFriends = [];
        }
        // Prepare data array for JSON response
        $data = [
            'facebook_id' => $facebookId,
            'name' => $facebookName,
            'email' => $facebookEmail,
            'profile_picture_url' => $facebookProfilePicUrl,
            'gender' => $facebookGender,
            'birthday' => $facebookBirthday,
            'hometown' => $facebookHometown,
            'location' => $facebookLocation,
            'country' => $facebookCountry,
            'friends' => $facebookFriends,
            'posts' => $facebookPosts,
            'likes' => $facebookLikes,
            'languages' => $facebookLanguages,
            'favorite_teams' => $facebookFavoriteTeams
        ];

        /** @var \Magento\Framework\Controller\Result\Json */
        $resultJson = $this->resultJsonFactory->create(); // Create JSON result object
        
        return $resultJson->setData($data); // Return the data as a JSON response
    }
}
