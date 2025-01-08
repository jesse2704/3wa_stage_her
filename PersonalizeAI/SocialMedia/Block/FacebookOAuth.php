<?php
namespace PersonalizeAI\SocialMedia\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Customer\Model\Session;

class FacebookOAuth extends Template
{
    protected $customerSession;

    public function __construct(
        Context $context,
        Session $customerSession,
        array $data = []
    ) {
        $this->customerSession = $customerSession;
        parent::__construct($context, $data);
    }

    // Check if the user is logged in
    public function isLoggedIn()
    {
        return $this->customerSession->isLoggedIn();
    }

    // Get the part of the view to show (default is 'welcome')
    public function getShowPart()
    {
        return $this->getData('show_part') ?: 'welcome';
    }

    // Generate a welcome message based on login status
    public function getWelcome()
    {
        if ($this->isLoggedIn()) {
            $customerName = $this->customerSession->getCustomer()->getName(); // Get logged-in customer's name
            return __('Welcome, %1!', $customerName); // Return personalized welcome message
        }
        return __('Welcome, Guest!'); // Return generic welcome message for guests
    }

    // Check if Facebook is linked, return boolean
    public function getFacebookIsLinked()
    {
        return $this->isLoggedIn() && !empty($this->customerSession->getFacebookName());
    }

    // Get the URL of the Facebook profile picture if logged in
    public function getFacebookProfilePicUrl()
    {
        if ($this->isLoggedIn()) {
            return $this->customerSession->getFacebookProfilePicUrl();
        }
        return false; // Return false if not logged in or no picture URL available
    }

    // Save Facebook profile picture URL to session
    public function saveFacebookProfileToSession()
    {
        $this->customerSession->setFacebookProfile($this->getFacebookProfilePicUrl());
    }

    // Retrieve the Facebook App ID from configuration settings
    public function getAppId()
    {
        return $this->_scopeConfig->getValue('facebook_oauth/general/app_id'); // Get App ID from configuration
    }

    // Retrieve the status of the module from configuration settings
    public function getModuleStatus()
    {
        return $this->_scopeConfig->getValue('facebook_oauth/general/enable'); // Get status from configuration
    }

    /**
     * Retrieve Facebook ID from session
     *
     * @return string|null
     */
    public function getFacebookId()
    {
        $facebookId = $this->customerSession->getFacebookId();
        return !empty($facebookId) ? $facebookId : 'Facebook ID not set';
    }

    /**
     * Retrieve Facebook Languages from session
     *
     * @return string|null
     */
    public function getFacebookLanguages()
    {
        $facebookEmail = $this->customerSession->getFacebookLanguages();
        return !empty($facebookEmail) ? $facebookEmail : 'Facebook Email not set';
    }


    /**
    * Retrieve Facebook name from session
    *
    * @return string|null
    */
    public function getFacebookName()
    {
        return $this->customerSession->getFacebookName();
    }

    /**
     * Retrieve Facebook Email from session
     *
     * @return string|null
     */
    public function getFacebookEmail()
    {
        $facebookEmail = $this->customerSession->getFacebookEmail();
        return !empty($facebookEmail) ? $facebookEmail : 'Facebook Email not set';
    }

   /**
     * Retrieve Facebook Gender from session
     *
     * @return string|null
     */
   public function getFacebookGender()
   {
       $facebookGender = $this->customerSession->getFacebookGender();
       return !empty($facebookGender) ? $facebookGender : 'Gender not set';
   }

   /**
     * Retrieve Facebook Birthday from session
     *
     * @return string|null
     */
   public function getFacebookBirthday()
   {
       $facebookBirthday = $this->customerSession->getFacebookBirthday();
       return !empty($facebookBirthday) ? $facebookBirthday : 'Birthday not set';
   }

   /**
     * Retrieve Facebook Hometown from session
     *
     * @return string|null
     */
   public function getFacebookHometown()
   {
       $facebookHometown = $this->customerSession->getFacebookHometown();
       return !empty($facebookHometown) ? $facebookHometown : 'Hometown not set';
   }

   /**
     * Retrieve Facebook Location from session
     *
     * @return array|string|null
     */
   public function getFacebookLocation()
   {
       $facebookLocation = $this->customerSession->getFacebookLocation();
       return !empty($facebookLocation) ? $facebookLocation : 'Location not set';
   }

   /**
     * Retrieve Facebook Country from session
     *
     * @return string|null
     */
   public function getFacebookCountry()
   {
       $facebookCountry = $this->customerSession->getFacebookCountry();
       return !empty($facebookCountry) ? $facebookCountry : 'Country not set';
   }

   /**
     * Retrieve Facebook Posts from session
     *
     * @return array|string|null
     */
   public function getFacebookPosts()
   {
       $facebookPosts = $this->customerSession->getFacebookPosts();
       return !empty($facebookPosts) ? $facebookPosts : 'No posts found';
   }

   /**
     * Retrieve Facebook Likes from session
     *
     * @return array|string|null
     */
   public function getFacebookLikes()
   {
       $facebookLikes = $this->customerSession->getFacebookLikes();
       return !empty($facebookLikes) ? $facebookLikes : 'No likes found';
   }

   /**
     * Retrieve Facebook Favorite Teams from session
     *
     * @return array|string|null
     */
   public function getFacebookFavoriteTeams()
   {
       $facebookFavoriteTeams = $this->customerSession->getFacebookFavoriteTeams();
       return !empty($facebookFavoriteTeams) ? $facebookFavoriteTeams : 'No favorite teams found';
   }
}
