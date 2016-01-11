<?php
/**
 * Test AccountPageBankDepositTest
 *
 * @package shop
 * @subpackage tests
 */
class AccountPageBankDepositTest extends FunctionalTest
{
    protected static $fixture_file = array(
        'shop/tests/fixtures/Pages.yml',
        'shop/tests/fixtures/shop.yml',
        'shop/tests/fixtures/Orders.yml',
        'shop_bankdeposit/tests/orders.yml'
    );
    //protected static $disable_theme = true;
    protected static $use_draft_site = true;

    public function setUp()
    {
        parent::setUp();
        $siteconfig = DataObject::get_one('SiteConfig');
        $siteconfig->BankAccountPaymentMethodMessage = "You will be notified of the bank account details";
        $siteconfig->BankAccountNumber = "XX-3456-7891011-XX";
        $siteconfig->BankAccountDetails = "TestBank, Business Branch";
        $siteconfig->BankAccountInvoiceMessage = "Hey bo, just pop the dosh in the account";
        $siteconfig->write();
        $this->accountpage = $this->objFromFixture("AccountPage", "accountpage");
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
        $this->assertEquals(200, $page->getStatusCode(), "a page should load");
        $this->assertContains("Past Orders", $page->getBody(), "Account Page is open");
        $this->assertContains("Joe Bloggs", $page->getBody(), "Joe Bloggs is logged in");
        $this->assertContains("$7,900.00", $page->getBody(), "Past Order is listed");
        $this->assertContains("$3,950.00", $page->getBody(), "Past Order is listed");

        // Open unpaid order
        $page = $this->get("account/order/10");
        $this->assertEquals(200, $page->getStatusCode(), "a page should load");
        $this->assertContains("Please deposit", $page->getBody(), "Opening statement is shown");
        $this->assertContains("XX-3456-7891011-XX", $page->getBody(), "Bank Account number is shown");
        $this->assertContains("Reference:", $page->getBody(), "Reference is shown");
        $this->assertContains("Your name", $page->getBody(), "Code is shown");
        $this->assertContains("<strong>$3,950.00</strong>", $page->getBody(), "Total Outstanding is shown");


        // Open paid order
        $page = $this->get("account/order/11");
        $this->assertEquals(200, $page->getStatusCode(), "a page should load");
        $this->assertNotContains("Please deposit", $page->getBody(), "Opening statement is not shown");
        $this->assertContains("$7,900.00", $page->getBody(), "Total Outstanding is shown");
        $this->assertContains("Paid with thanks", $page->getBody(), "'Paid with thanks' is displayed in place of Total Outstanding");


        // test other order statuses
        $order = Order::get()->byId("11");
        $statuses = ["Processing", "Sent", "Complete", "AdminCancelled", "MemberCancelled"];
        foreach ($statuses as $status) {
            $order->Status = $status;

            $page = $this->get("account/order/11");
            $this->assertEquals(200, $page->getStatusCode(), "a page should load");
            $this->assertNotContains("Please deposit", $page->getBody(), "Opening statement is not shown");
            $this->assertContains("$7,900.00", $page->getBody(), "Total Outstanding is shown");
            $this->assertContains("Paid with thanks", $page->getBody(), "'Paid with thanks' is displayed in place of Total Outstanding");
        }
    }
}
