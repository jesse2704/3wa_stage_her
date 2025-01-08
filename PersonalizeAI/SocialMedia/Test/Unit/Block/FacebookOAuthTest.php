<?php
namespace PersonalizeAI\SocialMedia\Test\Unit\Block;

use PHPUnit\Framework\TestCase;
use PersonalizeAI\SocialMedia\Block\FacebookOAuth;
use Magento\Framework\View\Element\Template\Context;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Config\ScopeConfigInterface;

class FacebookOAuthTest extends TestCase
{
    protected $block;
    protected $contextMock;
    protected $customerSessionMock;
    protected $scopeConfigMock;

    protected function setUp(): void
    {
        $this->contextMock = $this->createMock(Context::class);
        $this->customerSessionMock = $this->createMock(Session::class);
        $this->scopeConfigMock = $this->createMock(ScopeConfigInterface::class);

        $this->contextMock->expects($this->any())
            ->method('getScopeConfig')
            ->willReturn($this->scopeConfigMock);

        $this->block = new FacebookOAuth(
            $this->contextMock,
            $this->customerSessionMock
        );
    }

    public function testIsLoggedIn()
    {
        $this->customerSessionMock->expects($this->once())
            ->method('isLoggedIn')
            ->willReturn(true);

        $this->assertTrue($this->block->isLoggedIn());
    }

    public function testGetWelcomeLoggedIn()
    {
        $this->customerSessionMock->expects($this->once())
            ->method('isLoggedIn')
            ->willReturn(true);

        $this->customerSessionMock->expects($this->once())
            ->method('getCustomer')
            ->willReturn(new \Magento\Framework\DataObject(['name' => 'John Doe']));

        $this->assertEquals('Welcome, John Doe!', $this->block->getWelcome());
    }

    public function testGetWelcomeGuest()
    {
        $this->customerSessionMock->expects($this->once())
            ->method('isLoggedIn')
            ->willReturn(false);

        $this->assertEquals('Welcome, Guest!', $this->block->getWelcome());
    }

    public function testGetAppId()
    {
        $this->scopeConfigMock->expects($this->once())
            ->method('getValue')
            ->with('facebook_oauth/general/app_id')
            ->willReturn('123456789');

        $this->assertEquals('123456789', $this->block->getAppId());
    }

}
