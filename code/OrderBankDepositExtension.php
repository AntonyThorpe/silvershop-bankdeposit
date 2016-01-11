<?php namespace Shop\BankDeposit\Extensions;

use DataExtension;
use Checkout;
use OrderProcessor;
use GatewayInfo;

class Order extends DataExtension
{
    /**
     * onPlaceOrder
     *
     * send an email with a copy of the order for payment
     * @see onPlaceOrder is an extension within the placeOrder function within the OrderProcessor class
     * @return null
     */
    public function onPlaceOrder()
    {
        $gateway = Checkout::get($this->owner)->getSelectedPaymentMethod();
        if (OrderProcessor::config()->bank_deposit_send_confirmation && GatewayInfo::is_manual($gateway) && $this->owner->Status == "Unpaid") {
            OrderProcessor::config()->send_confirmation = true;
        } else {
            OrderProcessor::config()->send_confirmation = false;
        }
    }
}
