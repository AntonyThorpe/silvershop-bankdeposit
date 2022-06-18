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
class SteppedCheckoutPageGuestTest extends FunctionalTest
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

        //add item to cart via url
        $this->get(ShoppingCartController::add_item_link($this->laptop));
        ShoppingCart::curr()->calculate();
    }

    public function testEmailIsSentUponStepCheckoutCompletionByGuest()
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
                    "page should load"
                );

                $page = $self->submitForm("Form_MembershipForm", "action_guestcontinue", []);
                $self->assertEquals(
                    200,
                    $page->getStatusCode(),
                    "page should load"
                );

                // contact form
                $page = $self->submitForm("CheckoutForm_ContactDetailsForm", "action_checkoutSubmit", array(
                    'SilverShop-Checkout-Component-CustomerDetails_FirstName' => 'James',
                    'SilverShop-Checkout-Component-CustomerDetails_Surname' => 'Stark',
                    'SilverShop-Checkout-Component-CustomerDetails_Email' => 'guest@example.net'
                ));
                $self->assertEquals(
                    200,
                    $page->getStatusCode(),
                    "a page should load"
                );

                // Shipping Address form
                $page = $self->submitForm("CheckoutForm_ShippingAddressForm", "action_setshippingaddress", array(
                    'SilverShop-Checkout-Component-ShippingAddress_Country' => 'AU',
                    'SilverShop-Checkout-Component-ShippingAddress_Address' => '201-203 BROADWAY AVE',
                    'SilverShop-Checkout-Component-ShippingAddress_AddressLine2' => 'U 235',
                    'SilverShop-Checkout-Component-ShippingAddress_City' => 'WEST BEACH',
                    'SilverShop-Checkout-Component-ShippingAddress_State' => 'South Australia',
                    'SilverShop-Checkout-Component-ShippingAddress_PostalCode' => '5024',
                    'SilverShop-Checkout-Component-ShippingAddress_Phone' => '',
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
                    "a page should load"
                );
                $self->assertContains(
                    'XX-3456-7891011-XX',
                    $page->getBody(),
                    "Account Page contains bank deposit instructions"
                );
                $self->assertContains(
                    'CheckoutPage_order.ss',
                    $page->getBody(),
                    "CheckoutPage_order.ss template is used"
                );
                $self->assertEmailSent(
                    'guest@example.net'
                );
            }
        );
    }
}
