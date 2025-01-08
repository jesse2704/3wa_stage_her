<?php
namespace PersonalizeAI\FotoAnalyzeTool\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Customer\Model\Session;

class BetaFaceTool extends Template
{
    protected $customerSession;

    public function __construct(
        Context $context,
        Session $customerSession,
        array $data = []
    ){
        $this->customerSession = $customerSession;
        parent::__construct($context, $data);
    }

    // Retrieve the status of the module from configuration settings
    public function getModuleStatus()
    {
        return $this->_scopeConfig->getValue('photo_analyze/general/enable'); // Get status from configuration
    }

    // Check if the user is logged in
    public function isLoggedIn()
    {
        return $this->customerSession->isLoggedIn();
    }

    // Check if Facebook is linked, return boolean
    public function getFacebookIsLinked()
    {
        return $this->isLoggedIn() && !empty($this->customerSession->getFacebookName());
    }

    public function getFacebookProfilePicUrl()
    {
        if ($this->customerSession->isLoggedIn() && $this->customerSession->getFacebookProfilePicUrl()) {
            return $this->customerSession->getFacebookProfilePicUrl(); 
        }
        return false; // Return false if not logged in or no picture URL available
    }
}