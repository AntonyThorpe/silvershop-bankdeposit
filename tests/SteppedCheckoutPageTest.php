<?php

namespace AntonyThorpe\SilverShopBankDeposit\Tests;

use SilverStripe\Dev\FunctionalTest;
use SilverStripe\SiteConfig\SiteConfig;
use SilverStripe\Core\Config\Config;
use SilverStripe\ORM\DataObject;
use SilverStripe\Security\Member;
use SilverStripe\View\SSViewer;
use SilverShop\Tests\ShopTest;
use SilverShop\Extension\SteppedCheckoutExtension;
use SilverShop\Page\Product;
use SilverShop\Page\CheckoutPage;
use SilverShop\Page\AccountPage;
use SilverShop\Model\Order;
use SilverShop\Cart\ShoppingCart;
use SilverShop\Cart\ShoppingCartController;

/**
 * Test that the Manual payment method can be used on a stepped checkout and
 * that an email is sent upon completing the form
 */
class SteppedCheckoutPageTest extends FunctionalTest
{
    protected static $fixture_file = array(
        'vendor/silvershop/core/tests/php/Fixtures/Pages.yml',
        'vendor/silvershop/core/tests/php/Fixtures/shop.yml',
        'vendor/silvershop/core/tests/php/Fixtures/Orders.yml',
        'orders.yml'
    );

    protected static $disable_theme  = true;

    /**
     * @var SilverStripe\ORM\DataObject
     */
    protected $laptop;

    public function setUp()
    {
        parent::setUp();
        ShopTest::setConfiguration();
        ShoppingCart::singleton()->clear();
        SteppedCheckoutExtension::setupSteps(); //use default steps

        $siteconfig = DataObject::get_one(SiteConfig::class);
        $siteconfig->BankAccountPaymentMethodMessage = "You will be notified of the bank account details";
        $siteconfig->BankAccountNumber = "XX-3456-7891011-XX";
        $siteconfig->BankAccountDetails = "TestBank, Business Branch";
        $siteconfig->BankAccountInvoiceMessage = "Hey bo, just pop the dosh in the account";
        $siteconfig->write();

        SSViewer::config()->source_file_comments = true;

        // establish products
        $this->laptop = $this->objFromFixture(Product::class, "laptop");
        $this->laptop->publishSingle();

        // publish pages
        $checkoutpage = $this->objFromFixture(CheckoutPage::class, "checkout");
        $checkoutpage->publishSingle();

        $accountpage = $this->objFromFixture(AccountPage::class, "accountpage");
        $accountpage->publishSingle();

        // Login member
        $member = $this->objFromFixture(Member::class, "joebloggs");
        $this->logInAs($member);

        //add item to cart via url
        $this->get(ShoppingCartController::add_item_link($this->laptop));
    }

    public function testEmailIsSentUponStepCheckoutCompletion()
    {
        $self = $this;
        $this->useTestTheme(
            dirname(__FILE__),
            'testtheme',
            function () use ($self) {
                $page = $self->get("checkout/");
                $self->assertEquals(
                    200,
                    $page->getStatusCode(),
                    "contact details page should load"
                );

                // contact form
                $page = $self->submitForm("CheckoutForm_ContactDetailsForm", "action_checkoutSubmit", array(
                    'CustomerDetailsCheckoutComponent_FirstName' => 'Joe',
                    'CustomerDetailsCheckoutComponent_Surname' => 'Bloggs',
                    'CustomerDetailsCheckoutComponent_Email' => 'test@example.com'
                ));
                $self->assertEquals(
                    200,
                    $page->getStatusCode(),
                    "enter contact details page should load"
                );

                // Shipping Address form
                $page = $self->submitForm("CheckoutForm_ShippingAddressForm", "action_setshippingaddress", array(
                    'ShippingAddressCheckoutComponent_Country' => 'AU',
                    'ShippingAddressCheckoutComponent_Address' => '201-203 BROADWAY AVE',
                    'ShippingAddressCheckoutComponent_AddressLine2' => 'U 235',
                    'ShippingAddressCheckoutComponent_City' => 'WEST BEACH',
                    'ShippingAddressCheckoutComponent_State' => 'South Australia',
                    'ShippingAddressCheckoutComponent_PostalCode' => '5024',
                    'ShippingAddressCheckoutComponent_Phone' => '',
                    'SeperateBilling' => '0'

                ));
                $self->assertEquals(
                    200,
                    $page->getStatusCode(),
                    "payment methods page should load"
                );

                $self->assertContains(
                    "CheckoutForm_PaymentMethodForm_PaymentMethod_Manual",
                    $page->getBody(),
                    "Manual payment method available"
                );

                $self->assertContains(
                    "You will be notified of the bank account details",
                    $page->getBody(),
                    "Bank Account Message presented during the payment method section"
                );

                // Payment Method can be manual
                $page = $self->submitForm("CheckoutForm_PaymentMethodForm", "action_setpaymentmethod", array(
                    'PaymentMethod' => 'Manual',
                ));
                $self->assertEquals(
                    200,
                    $page->getStatusCode(),
                    "Payment Method set.  The summary page should load."
                );

                // Summary
                $page = $self->submitForm("PaymentForm_ConfirmationForm", "action_checkoutSubmit", array(
                    'PaymentForm_ConfirmationForm_Notes' => 'Test',
                ));
                $self->assertEquals(
                    200,
                    $page->getStatusCode(),
                    "enter summary page should load"
                );
                $self->assertContains(
                    '<h2>My Account</h2>',
                    $page->getBody(),
                    "Account Page should load"
                );

                $self->assertContains(
                    'XX-3456-7891011-XX',
                    $page->getBody(),
                    "Account Page contains bank deposit instructions"
                );

                $self->assertEmailSent(
                    'test@example.com'
                );
            }
        );
    }
}
