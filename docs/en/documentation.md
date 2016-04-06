# Documentation of Silvershop Bank Deposit

## Getting Started
* In Admin, click Settings/Shop/Bank Account and add your organisation's details
* Provide a message to the customer when they select Bank Deposit as a payment method in the checkout process.  Copy Silvershop's CheckoutPage.ss to your theme's `templates/Layout` folder and add:
```html
    <% with SiteConfig %>
        <div>
            $BankAccountPaymentMethodMessage
        </div>
    <% end_with %>
```
* Following completion of the checkout steps, on the order review page, provide an opening statement to encourage the customer to make a transfer into your organisation's bank account.  Copy both Checkout_order.ss (for guests) and AccountPage_order.ss (for members) from Silvershop's `templates/Layout` into `{yourtheme}/templates/Layout` and add:
```html
    <%-- before the order --%>
    <% if $Status == "Unpaid" %>
        <% include Order_BankDepositNeededStatement %>
    <% end_if %>
    ...
    <%-- after include Order --%>
    <% if $Order.Status == "Unpaid" %>
        $ActionsForm
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
  bcc_confirmation_to_admin: true  #if not already set to true by Silvershop
```

For more information see [Enable / disable sending of emails](https://github.com/silvershop/core/blob/master/docs/en/02_Customisation/Emails.md)
