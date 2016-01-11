# silverstripe-shop-bankdeposit
Submodule for Silverstripe Shop that adds bank deposit as a Payment Method

[![Build Status](https://travis-ci.org/AntonyThorpe/silverstripe-shop-bankdeposit.svg)](https://travis-ci.org/AntonyThorpe/silverstripe-shop-bankdeposit)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/AntonyThorpe/silverstripe-shop-bankdeposit/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/AntonyThorpe/silverstripe-shop-bankdeposit/?branch=master)

## How it works
Utilises the 'Manual' Payment Method (names it "Bank Deposit").  
Localisation options available.

## Warning
It is assumed that people accessing the Setting folder can be trusted.  This is where the bank account number is entered.

## Requirements
[SilverStripe Shop module](https://github.com/burnbright/silverstripe-shop)

## Installation
In your composer.json add

```json
"repositories": [{
  "type": "vcs",
	"url": "https://github.com/AntonyThorpe/silverstripe-shop-bankdeposit.git"
}],
"require": {
	"AntonyThorpe/silverstripe-shop-bankdeposit": "latest version number"
}
```
Then in the terminal cd to your project root and `composer update`

In your mysite/_config/config.yml add
```yml
Payment:
  allowed_gateways:
    - 'Manual'
```
dev/build/?flush

## Getting Started
In Admin, click settings/shop/bankaccount and add details.

## Customisation
### Checkout Page
As an option, add a message for the user in the Payment Method section of the Checkout Page:
```html
<% with SiteConfig %>
    $BankAccountPaymentMethodMessage
<% end_with %>
```
###Account Page
Refer to this module's template folder for includes/changes to `AccountPage_order.ss` where the opening statement is shown if the order has an unpaid status.  In addition, the order includes modifications to Total Outstanding and an additional table displaying bank account information, just like what typically appears on a invoice.  

## Send Email Confirmations
Email a copy of an unpaid Order to the customer with your bank deposit instructions.
This feature is enabled by default.  To deactivate:
```yml
OrderProcessor:
  bank_deposit_send_confirmation: false
```
To customise copy `shop/templates/email/Order_ConfirmationEmail.ss` to `themes/yourname/templates/email/Order_ConfirmationEmail.ss` and style as needed.

The email will need your subject.  Override the default in your `mysite/lang/en.yml` file (see `lang/en.yml` for an example).

In addition, if you want a blind copy:
```yml
Email:
  admin_email: example@example.com

OrderEmailNotifier:
  bcc_confirmation_to_admin: true
```

For more information see [Enable / disable sending of emails](https://github.com/burnbright/silverstripe-shop/blob/master/docs/en/02_Customisation/Emails.md)

## Contributions
Pull requests welcome!  PSR-2 please.

## License
[MIT](LICENCE)


