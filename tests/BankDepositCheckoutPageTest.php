<?php

/**
 * Test that the Manual payment method can be used on a stepped checkout and
 * that an email is sent upon completing the form
 */
class BankDepositCheckoutPageTest extends FunctionalTest
{
    protected static $fixture_file = array(
        'silvershop/tests/fixtures/Pages.yml',
        'silvershop/tests/fixtures/shop.yml',
        'silvershop/tests/fixtures/Orders.yml',
        'silvershop-bankdeposit/tests/orders.yml'
    );

    public function setUp()
    {
        parent::setUp();
        ShopTest::setConfiguration();
        SteppedCheckout::setupSteps(); //use default steps
        //set supported gateways
        Payment::config()->allowed_gateways = array(
            'Manual',
            'Dummy'
        );
        $siteconfig = DataObject::get_one('SiteConfig');
        $siteconfig->BankAccountPaymentMethodMessage = "You will be notified of the bank account details";
        $siteconfig->BankAccountNumber = "XX-3456-7891011-XX";
        $siteconfig->BankAccountDetails = "TestBank, Business Branch";
        $siteconfig->BankAccountInvoiceMessage = "Hey bo, just pop the dosh in the account";
        $siteconfig->write();

        // establish products
        $this->laptop = $this->objFromFixture("Product", "laptop");
        $this->laptop->publish('Stage', 'Live');
        $this->bag = $this->objFromFixture("Product", "bag");
        $this->bag->publish('Stage', 'Live');
        $this->battery = $this->objFromFixture("Product", "battery");
        $this->battery->publish('Stage', 'Live');

        // publish pages
        $checkoutpage = $this->objFromFixture("CheckoutPage", "checkout");
        $checkoutpage->publish('Stage', 'Live');
        $accountpage = $this->objFromFixture("AccountPage", "accountpage");
        $accountpage->publish('Stage', 'Live');

        // Login member
        $member = $this->objFromFixture("Member", "joebloggs");
        $this->logInAs($member);

        // create a cart
        $this->cart = $this->objFromFixture("Order", "cartBD");
        ShoppingCart::singleton()->setCurrent($this->cart);
    }

    public function testEmailIsSent()
    {
        $page = $this->get("checkout/");
        $this->assertEquals(200, $page->getStatusCode(), "contact details page should load");

        // contact form
        $page = $this->submitForm("CheckoutForm_ContactDetailsForm", "action_checkoutSubmit", array(
            'CustomerDetailsCheckoutComponent_FirstName' => 'Joe',
            'CustomerDetailsCheckoutComponent_Surname' => 'Bloggs',
            'CustomerDetailsCheckoutComponent_Email' => 'test@example.com'
        ));
        $this->assertEquals(
            200,
            $page->getStatusCode(),
            "enter contact details page should load"
        );
        
        // Shipping Address form
        $page = $this->submitForm("CheckoutForm_ShippingAddressForm", "action_setshippingaddress", array(
            'ShippingAddressCheckoutComponent_Country' => 'AU',
            'ShippingAddressCheckoutComponent_Address' => '201-203 BROADWAY AVE',
            'ShippingAddressCheckoutComponent_AddressLine2' => 'U 235',
            'ShippingAddressCheckoutComponent_City' => 'WEST BEACH',
            'ShippingAddressCheckoutComponent_State' => 'South Australia',
            'ShippingAddressCheckoutComponent_PostalCode' => '5024',
            'ShippingAddressCheckoutComponent_Phone' => '',
            'SeperateBilling' => '0'

        ));
        $this->assertEquals(
            200,
            $page->getStatusCode(),
            "payment methods page should load"
        );

        // Payment Method
        $page = $this->submitForm("CheckoutForm_PaymentMethodForm", "action_setpaymentmethod", array(
            'PaymentMethod' => 'Manual',
        ));
        $this->assertEquals(
            200,
            $page->getStatusCode(),
            "enter summary page should load"
        );

        // Summary
        $page = $this->submitForm("PaymentForm_ConfirmationForm", "action_checkoutSubmit", array(
            'PaymentForm_ConfirmationForm_Notes' => 'Test',
        ));
        $this->assertEquals(
            200,
            $page->getStatusCode(),
            "enter summary page should load"
        );
        $this->assertContains(
            '<h2>My Account</h2>',
            $page->getBody(),
            "Account Page should load"
        );

        $this->assertEmailSent(
            'test@example.com'
        );
    }
}
