<?php

namespace AntonyThorpe\SilvershopBankDeposit;

use SilverStripe\Core\Extension;
use SilverStripe\Omnipay\GatewayInfo;
use SilverShop\Checkout\Checkout;
use SilverShop\Checkout\OrderProcessor;

/**
 * @extends Extension<\SilverShop\Model\Order&static>
 */
class Order extends Extension
{
    /**
     * Send an email with a copy of the order for payment
     *
     * Accessing the extension point is needed to dynamically set send_confirmation to true.
     * Normally, an unpaid order will not generate an email.
     *
     * @see onPlaceOrder is an extension within the placeOrder function within the OrderProcessor class
     */
    public function onPlaceOrder(): void
    {
        $checkout = Checkout::get($this->getOwner());
        if ($checkout instanceof Checkout) {
            $gateway = $checkout->getSelectedPaymentMethod();
            if ($gateway
                && OrderProcessor::config()->get('bank_deposit_send_confirmation')
                && GatewayInfo::isManual($gateway)
                && $this->getOwner()->Status == "Unpaid"
            ) {
                OrderProcessor::config()->set('send_confirmation', true);
            } else {
                OrderProcessor::config()->set('send_confirmation', false);
            }
        }
    }
}
