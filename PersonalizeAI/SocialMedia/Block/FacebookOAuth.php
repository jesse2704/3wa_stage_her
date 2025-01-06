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
        array $data = [])
    {
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
        if ($this->customerSession->isLoggedIn()) {
            $customerName = $this->customerSession->getCustomer()->getName(); // Get logged-in customer's name
            return __('Welcome, %1!', $customerName); // Return personalized welcome message
        }
        return __('Welcome, Guest!'); // Return generic welcome message for guests
    }

    // Check if facebook is linked, return boolean
    public function getFacebookIsLinked()
    {
        if ($this->customerSession->isLoggedIn() && $this->customerSession->getFacebookName()) {
            return true;
        }
        return false;
    }

    // Get the URL of the Facebook profile picture if logged in
    public function getFacebookProfilePicUrl()
    {
        if ($this->customerSession->isLoggedIn() && $this->customerSession->getFacebookProfilePicUrl()) {
            return $this->customerSession->getFacebookProfilePicUrl(); 
        }
        return false; // Return false if not logged in or no picture URL available
    }

    // Save Facebook profile picture URL to session 
    public function saveFacebookProfileToSession()
    {
       $customerSession->setFacebookProfile(getFacebookProfilePicUrl());
    }

    // Retrieve the Facebook App ID from configuration settings
    public function getAppId()
    {
        return $this->_scopeConfig->getValue('facebook_oauth/general/app_id'); // Get App ID from configuration 
    }

    public function getFacebookLanguages()
    {
        if ($this->customerSession->isLoggedIn() && $this->customerSession->getFacebookLanguages()) {
            return $this->customerSession->getFacebookLanguages();
        }
        return false;
    }

    public function getFacebookName()
    {
        if ($this->customerSession->isLoggedIn() && $this->customerSession->getFacebookName()) {
            return $this->customerSession->getFacebookName();
        }
        return false;
    }

    // Retrieve the status of the module from configuration settings
    public function getModuleStatus()
    {
        return $this->_scopeConfig->getValue('facebook_oauth/general/enable'); // Get status from configuration 
    }
}
