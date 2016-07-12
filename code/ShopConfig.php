<?php namespace SilvershopBankDeposit\Cms;

use DataExtension;
use FieldList;
use Textfield;
use HtmlEditorField;

class ShopConfig extends DataExtension
{
    private static $db = array(
        'BankAccountPaymentMethodMessage' => 'Text',
        'BankAccountNumber' => 'Text',
        'BankAccountDetails' => 'Text',
        'BankAccountInvoiceMessage' => 'HTMLText'
    );

    public function updateCMSFields(FieldList $fields)
    {
        $fields->addFieldsToTab(
            'Root.Shop.ShopTabs.' . _t('ShopConfig.BANKACCOUNTTITLE', 'Bank Account'),
            array(
                Textfield::create("BankAccountPaymentMethodMessage", _t("ShopConfig.BANKACCOUNTPAYMENTMETHODMESSAGE", "Payment Method message on Checkout page"))
                    ->setDescription(_t("ShopConfig.BANKACCOUNTPAYMENTMETHODMESSAGEDESCRIPTION", "Message to appear in the Payment Method section of Checkout Page")),
                Textfield::create("BankAccountNumber", _t("ShopConfig.BANKACCOUNTNUMBER", "Bank Account Number"))
                    ->setDescription(_t("ShopConfig.BANKACCOUNTNUMBERDESCRIPTION", "e.g XX-XXXX-XXXXXXX-XX")),
                Textfield::create("BankAccountDetails", _t("ShopConfig.BANKACCOUNTDETAILS", "Bank Account Details"))
                    ->setDescription(_t("ShopConfig.BANKACCOUNTDETAILSDESCRIPTION", "Account Name, Bank Name, Branch, Branch Address")),
                HtmlEditorField::create("BankAccountInvoiceMessage", _t("ShopConfig.BANKACCOUNTINVOICEMESSAGE", "Bank Account Order/Invoice Message"))
                    ->setDescription(_t("ShopConfig.BANKACCOUNTINVOICEMESSAGEDESCRIPTION", "Message to appear on the order/invoice"))
            )
        );
    }
}
