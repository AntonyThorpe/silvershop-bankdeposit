<?php

namespace AntonyThorpe\SilvershopBankDeposit;

use SilverStripe\Forms\TextareaField;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;
use SilverStripe\Forms\FieldList;
use SilverStripe\ORM\DataExtension;

class ShopConfigExtension extends DataExtension
{
    /**
     * @config
     */
    private static array $db = [
        'BankAccountPaymentMethodMessage' => 'Text',
        'BankAccountNumber' => 'Text',
        'BankAccountDetails' => 'Text',
        'BankAccountInvoiceMessage' => 'HTMLText'
    ];

    public function updateCMSFields(FieldList $fields): void
    {
        $fields->addFieldsToTab(
            'Root.Shop.ShopTabs.' . _t('SilverShop\Extension\ShopConfigExtension.BankAccountTitle', 'Bank Account'),
            [
                Textfield::create(
                    "BankAccountPaymentMethodMessage",
                    _t(
                        "SilverShop\\Extension\\ShopConfigExtension.BankAccountPaymentMethodMessage",
                        "Payment Method message on Checkout page"
                    )
                )->setDescription(
                    _t(
                        "SilverShop\\Extension\\ShopConfigExtension.BankAccountPaymentMethodMessageDescription",
                        "Message to appear in the Payment Method section of Checkout Page"
                    )
                ),
                Textfield::create(
                    "BankAccountNumber",
                    _t(
                        "SilverShop\\Extension\\ShopConfigExtension.BankAccountNumber",
                        "Bank Account Number"
                    )
                )->setDescription(
                    _t(
                        "SilverShop\\Extension\\ShopConfigExtension.BankAccountNumberDescription",
                        "e.g XX-XXXX-XXXXXXX-XX"
                    )
                ),
                TextareaField::create(
                    "BankAccountDetails",
                    _t(
                        "SilverShop\\Extension\\ShopConfigExtension.BankAccountDetails",
                        "Bank Account Details"
                    )
                )->setDescription(
                    _t(
                        "SilverShop\\Extension\\ShopConfigExtension.BankAccountDetailsDescription",
                        "Account Name, Bank Name, Branch, Branch Address"
                    )
                ),
                HtmlEditorField::create(
                    "BankAccountInvoiceMessage",
                    _t(
                        "SilverShop\\Extension\\ShopConfigExtension.BankAccountInvoiceMessage",
                        "Bank Account Order/Invoice Message"
                    )
                )->setDescription(
                    _t(
                        "SilverShop\\Extension\\ShopConfigExtension.BankAccountInvoiceMessageDescription",
                        "Message to appear on the order/invoice"
                    )
                )
            ]
        );
    }
}
