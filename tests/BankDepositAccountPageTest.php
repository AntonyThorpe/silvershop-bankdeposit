<?php

namespace AntonyThorpe\SilverShopBankDeposit\Tests;

use SilverStripe\Dev\FunctionalTest;
use SilverStripe\SiteConfig\SiteConfig;
use SilverStripe\ORM\DataObject;
use SilverStripe\Control\Controller;
use SilverShop\Tests\ShopTestControllerExtension;
use SilverShop\Tests\ShopTest;
use SilverShop\Page\AccountPage;
use SilverShop\Page\AccountPageController;
use SilverStripe\Security\Member;
use SilverShop\Model\Order;

/**
 * Ensure that the template includes work on the AccountPage
 */
class BankDepositAccountPageTest extends FunctionalTest
{
    protected static $fixture_file = array(
        'vendor/silvershop/core/tests/php/Fixtures/Pages.yml',
        'vendor/silvershop/core/tests/php/Fixtures/shop.yml',
        'vendor/silvershop/core/tests/php/Fixtures/Orders.yml',
        'orders.yml'
    );

    /**
     * @var AccountPage
     */
    protected $accountpage;

    /**
     * @var AccountPageController
     */
    protected $controller;

    public function setUp()
    {
        parent::setUp();
        ShopTest::setConfiguration();

        $siteconfig = DataObject::get_one(SiteConfig::class);
        $siteconfig->BankAccountPaymentMethodMessage = "You will be notified of the bank account details";
        $siteconfig->BankAccountNumber = "XX-3456-7891011-XX";
        $siteconfig->BankAccountDetails = "TestBank, Business Branch";
        $siteconfig->BankAccountInvoiceMessage = "Hey bo, just pop the dosh in the account";
        $siteconfig->write();

        Controller::add_extension(ShopTestControllerExtension::class);
        $this->accountpage = $this->objFromFixture(AccountPage::class, "accountpage");
        $this->accountpage->publishSingle();
        $this->controller = new AccountPageController($this->accountpage);
    }

    public function testCanViewAccountPagePastOrdersAndIndividualOrders()
    {
        $self = $this;
        $this->useTestTheme(
            dirname(__FILE__),
            'testtheme',
            function () use ($self) {
                $member = $self->objFromFixture(Member::class, "joebloggs");
                $self->logInAs($member);
                $self->controller->init(); //re-init to connect up member

                // Open Address Book page
                $page = $self->get("account/"); // Past Orders page
                $self->assertEquals(AccountPageController::class, $page->getHeader('X-TestPageClass'), "Account page should open");

                $self->assertContains(
                    "Past Orders",
                    $page->getBody(),
                    "Account Page is open"
                );
                $self->assertContains(
                    "Joe Bloggs",
                    $page->getBody(),
                    "Joe Bloggs is logged in"
                );
                $self->assertContains(
                    "<td>$408.00</td>",
                    $page->getBody(),
                    "Past Order is listed"
                );

                // Open unpaid order
                $page = $self->get("account/order/10");
                $self->assertEquals(
                    200,
                    $page->getStatusCode(),
                    "a page should load"
                );
                $self->assertContains(
                    "Please deposit",
                    $page->getBody(),
                    "Opening statement is shown"
                );
                $self->assertContains(
                    "XX-3456-7891011-XX",
                    $page->getBody(),
                    "Bank Account number is shown"
                );
                $self->assertContains(
                    "Reference:",
                    $page->getBody(),
                    "Reference is shown"
                );
                $self->assertContains(
                    "Your name",
                    $page->getBody(),
                    "Code is shown"
                );
                $self->assertContains(
                    "$3950.00",
                    $page->getBody(),
                    "Total Outstanding is shown"
                );

                // Open paid order
                $page = $self->get("account/order/11");
                $self->assertEquals(
                    200,
                    $page->getStatusCode(),
                    "a page should load"
                );

                $self->assertNotContains(
                    "Please deposit",
                    $page->getBody(),
                    "Opening statement is not shown"
                );
                $self->assertContains(
                    "$7900.00",
                    $page->getBody(),
                    "Total Outstanding is shown"
                );

                $self->assertContains(
                    "Paid",
                    $page->getBody(),
                    "'Paid' is displayed in place of Total Outstanding"
                );

                // test other order statuses
                $order = Order::get()->byId("11");
                $statuses = ["Sent", "Complete", "AdminCancelled", "MemberCancelled"];
                foreach ($statuses as $status) {
                    $order->Status = $status;

                    $page = $self->get("account/order/11");
                    $self->assertEquals(
                        200,
                        $page->getStatusCode(),
                        "a page should load"
                    );
                    $self->assertNotContains(
                        "Please deposit",
                        $page->getBody(),
                        "Opening statement is not shown"
                    );
                    $self->assertContains(
                        "$7900.00",
                        $page->getBody(),
                        "Total Outstanding is shown"
                    );
                    $self->assertContains(
                        "Paid",
                        $page->getBody(),
                        "'Paid' is displayed in place of Total Outstanding"
                    );
                }
            }
        );
    }
}
