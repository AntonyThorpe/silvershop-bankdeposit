# silvershop-bankdeposit
Submodule for Silvershop that adds bank deposit as a Payment Method

[![Build Status](https://travis-ci.org/AntonyThorpe/silvershop-bankdeposit.svg)](https://travis-ci.org/AntonyThorpe/silvershop-bankdeposit)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/AntonyThorpe/silvershop-bankdeposit/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/AntonyThorpe/silvershop-bankdeposit/?branch=master)

## Features
* Provides a Bank Account tab under Settings/Shop to add your organisations bank account number and payment messages
* Include bank account number and messages in emails, the order review and during the checkout process
* Localisation options available

## How it works
* Utilises the 'Manual' Payment Method (names it "Bank Deposit")
* Add includes to your templates

## Warning
It is assumed that people accessing the Setting folder can be trusted.  This is where the bank account number is entered.  If you think that settings from a YAML file would be more secure then please feel free to submit a pull request.

## Requirements
[Silvershop module](https://github.com/silvershop/silvershop-core)

## Installation
In a terminal
`composer require antonythorpe/silvershop-bankdeposit`

In your mysite/_config/bankdeposit.yml, or similar, add:
```yml
Payment:
  allowed_gateways:
    - 'Manual'
```
Then `{example.net}/dev/build/?flush`

## Getting Started
* In Admin, click Settings/Shop/Bank Account and add your organisation's details
* Provide a message to the customer when they select Bank Deposit as a payment method in the checkout process.  Copy Silvershop's Checkout.ss to your theme's `templates/Layout` folder and add:
```html
    <% with SiteConfig %>
        <div>
            $BankAccountPaymentMethodMessage
        </div>
    <% end_with %>
```
* Following completion of the checkout steps, on the order review page, provide an opening statement to encourage the customer to make a transfer into your organisation's bank account.  Copy AccountPage_order.ss from Silvershop's `templates/Layout` into your theme and add:
```html
    <% if $Status == "Unpaid" %>
        <% include Order_BankDepositNeededStatement %>
    <% end_if %>
```
* Provide some customisation to the Total Outstanding for cancelled orders.  Copy Order.ss from Silvershop's templates/order into `{yourtheme}/templates/order` and replace `$TotalOutstanding.Nice` with:
```html
    <% if $Status == "Unpaid" %>
        $TotalOutstanding.Nice
    <% else %>
        <% if $Status == "MemberCancelled" || $Status == "AdminCancelled" %>
            Order Cancelled
        <% else %>
            Paid with thanks - you are awesome :)
        <% end_if %>
    <% end_if %>
```
In addition, provide full instructions beneath the Notes table:
```html
    <% if Total %>
        <% if TotalOutstanding %>
            <% if Payments %>
                <% loop Payments.last %>
                    <% if GatewayTitle == "Bank Deposit" %>
                        <% include Order_BankDeposit %>
                    <% end_if %>
                <% end_loop %>
            <% end_if %>
        <% end_if %>
    <% end_if %>
```

Examples are available under the test folder.

## Sending Email Confirmations
Emailing a copy of an unpaid Order to the customer with your bank deposit details is enabled by default.  To deactivate:
```yml
OrderProcessor:
  bank_deposit_send_confirmation: false
```
To customise copy `shop/templates/email/Order_ConfirmationEmail.ss` to `themes/yourname/templates/email/Order_ConfirmationEmail.ss` and arrange/style as needed.

The email will need your subject.  Override the default in your `mysite/lang/en.yml` file (see `lang/en.yml` for an example).

In addition, if you want a blind copy:
```yml
Email:
  admin_email: example@example.com

OrderEmailNotifier:
  bcc_confirmation_to_admin: true  #if not set to true by Silvershop
```

For more information see [Enable / disable sending of emails](https://github.com/burnbright/silverstripe-shop/blob/master/docs/en/02_Customisation/Emails.md)

## Contributions
Pull requests welcome!  PSR-2 please.

## License
[MIT](/LICENCE.md)


