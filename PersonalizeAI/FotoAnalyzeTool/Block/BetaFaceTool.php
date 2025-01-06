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

    public function getFacebookProfilePicUrl()
    {
        if ($this->customerSession->isLoggedIn() && $this->customerSession->getFacebookProfilePicUrl()) {
            return $this->customerSession->getFacebookProfilePicUrl(); 
        }
        return false; // Return false if not logged in or no picture URL available
    }
}