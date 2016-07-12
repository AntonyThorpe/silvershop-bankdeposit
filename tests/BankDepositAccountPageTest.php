<?php
/**
 * Ensure that the template includes work on the AccountPage
 */
class BankDepositAccountPageTest extends FunctionalTest
{
    protected static $fixture_file = array(
        'silvershop/tests/fixtures/Pages.yml',
        'silvershop/tests/fixtures/shop.yml',
        'silvershop/tests/fixtures/Orders.yml',
        'silvershop-bankdeposit/tests/orders.yml'
    );

    public function setUp()
    {
        $this->useTestTheme(dirname(__FILE__), 'testtheme', function(){});
        parent::setUp();
        $siteconfig = DataObject::get_one('SiteConfig');
        $siteconfig->BankAccountPaymentMethodMessage = "You will be notified of the bank account details";
        $siteconfig->BankAccountNumber = "XX-3456-7891011-XX";
        $siteconfig->BankAccountDetails = "TestBank, Business Branch";
        $siteconfig->BankAccountInvoiceMessage = "Hey bo, just pop the dosh in the account";
        $siteconfig->write();
        Payment::config()->allowed_gateways = array(
            'Manual',
            'Dummy'
        );
        $this->accountpage = $this->objFromFixture("AccountPage", "accountpage")->publish('Stage', 'Live');
        $this->controller = new AccountPage_Controller($this->accountpage);
        $this->controller->init();

    }

    public function testCanViewAccountPagePastOrdersAndIndividualOrders()
    {
        $member = $this->objFromFixture("Member", "joebloggs");
        $this->logInAs($member);
        $this->controller->init(); //re-init to connect up member

        // Open Address Book page
        $page = $this->get("account/"); // Past Orders page
        $this->assertEquals(
            200,
            $page->getStatusCode(),
            "a page should load"
        );
        $this->assertContains(
            "Past Orders",
            $page->getBody(),
            "Account Page is open"
        );
        $this->assertContains(
            "Joe Bloggs",
            $page->getBody(),
            "Joe Bloggs is logged in"
        );
        $this->assertContains(
            "$7,900.00",
            $page->getBody(),
            "Past Order is listed"
        );
        $this->assertContains(
            "$3,950.00",
            $page->getBody(),
            "Past Order is listed"
        );

        // Open unpaid order
        $page = $this->get("account/order/10");
        $this->assertEquals(
            200,
            $page->getStatusCode(),
            "a page should load"
        );
        $this->assertContains(
            "Please deposit",
            $page->getBody(),
            "Opening statement is shown"
        );
        $this->assertContains(
            "XX-3456-7891011-XX",
            $page->getBody(),
            "Bank Account number is shown"
        );
        $this->assertContains(
            "Reference:",
            $page->getBody(),
            "Reference is shown"
        );
        $this->assertContains(
            "Your name",
            $page->getBody(),
            "Code is shown"
        );
        $this->assertContains(
            "<strong>$3,950.00</strong>",
            $page->getBody(),
            "Total Outstanding is shown"
        );


        // Open paid order
        $page = $this->get("account/order/11");
        $this->assertEquals(
            200,
            $page->getStatusCode(),
            "a page should load"
        );
        $this->assertNotContains(
            "Please deposit",
            $page->getBody(),
            "Opening statement is not shown"
        );
        $this->assertContains(
            "$7,900.00",
            $page->getBody(),
            "Total Outstanding is shown"
        );
        
        $this->assertContains(
            "Paid",
            $page->getBody(),
            "'Paid' is displayed in place of Total Outstanding"
        );

        // test other order statuses
        $order = Order::get()->byId("11");
        $statuses = ["Sent", "Complete", "AdminCancelled", "MemberCancelled"];
        foreach ($statuses as $status) {
            $order->Status = $status;

            $page = $this->get("account/order/11");
            $this->assertEquals(
                200,
                $page->getStatusCode(),
                "a page should load"
            );
            $this->assertNotContains(
                "Please deposit",
                $page->getBody(),
                "Opening statement is not shown"
            );
            $this->assertContains(
                "$7,900.00",
                $page->getBody(),
                "Total Outstanding is shown"
            );
            $this->assertContains(
                "Paid",
                $page->getBody(),
                "'Paid' is displayed in place of Total Outstanding"
            );
        }
    }
}
