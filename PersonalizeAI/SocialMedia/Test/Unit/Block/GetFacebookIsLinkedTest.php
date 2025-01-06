<?php
namespace PersonalizeAI\SocialMedia\Test\Unit\Block;

use PersonalizeAI\SocialMedia\Block\FacebookOAuth;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\View\Element\Template\Context;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class GetFacebookIsLinkedTest extends TestCase
{
    /**
     * @var FacebookOAuth
     */
    private $facebookOAuth;

    /**
     * @var CustomerSession|MockObject
     */
    private $customerSessionMock;

    /**
     * @var Context|MockObject
     */
    private $contextMock;
    
    protected function setUp(): void
    {
        $this->contextMock = $this->createMock(Context::class);
        $this->customerSessionMock = $this->getMockBuilder(CustomerSession::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['isLoggedIn'])
            ->addMethods(['getFacebookName'])
            ->getMock();
    
        $this->facebookOAuth = new FacebookOAuth(
            $this->contextMock,
            $this->customerSessionMock
        );
    }
    
    public function testGetFacebookIsLinkedWhenLoggedInAndLinked()
    {
        $this->customerSessionMock->method('isLoggedIn')->willReturn(true);
        $this->customerSessionMock->method('getFacebookName')->willReturn('John Do');

        $this->assertTrue($this->facebookOAuth->getFacebookIsLinked());
    }

    public function testGetFacebookIsLinkedWhenLoggedInButNotLinked()
    {
        $this->customerSessionMock->method('isLoggedIn')->willReturn(true);
        $this->customerSessionMock->method('getFacebookName')->willReturn(null);

        $this->assertFalse($this->facebookOAuth->getFacebookIsLinked());
    }

    public function testGetFacebookIsLinkedWhenNotLoggedIn()
    {
        $this->customerSessionMock->method('isLoggedIn')->willReturn(false);

        $this->assertFalse($this->facebookOAuth->getFacebookIsLinked());
    }
}
