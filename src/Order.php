<?php

namespace AntonyThorpe\SilvershopBankDeposit;

use SilverStripe\Omnipay\GatewayInfo;
use SilverShop\Checkout\Checkout;
use SilverShop\Checkout\OrderProcessor;
use SilverStripe\ORM\DataExtension;

class Order extends DataExtension
{
    /**
     * Send an email with a copy of the order for payment
     *
     * Accessing the extension point is needed to dynamically set send_confirmation to true.
     * Normally, an unpaid order will not generate an email.
     * @see onPlaceOrder is an extension within the placeOrder function within the OrderProcessor class
     */
    public function onPlaceOrder(): void
    {
        $gateway = Checkout::get($this->getOwner())->getSelectedPaymentMethod();
        if (OrderProcessor::config()->bank_deposit_send_confirmation &&
            GatewayInfo::isManual($gateway) &&
            $this->owner->Status == "Unpaid"
        ) {
            OrderProcessor::config()->send_confirmation = true;
        } else {
            OrderProcessor::config()->send_confirmation = false;
        }
    }
}
