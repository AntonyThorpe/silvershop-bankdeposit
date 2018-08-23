# Documentation of Silvershop Bank Deposit

## Getting Started
* In Admin, click Settings/Shop/Bank Account and add your organisation's details
* Tip: some examples of the below are available under `tests/testtheme/SilverShop` folder.
* Stepped Checkout only: provide a message to the customer when they select Bank Deposit as a payment method in the checkout process.  Copy Silvershop's `SteppedCheckoutPage.ss` from `templates/SilverShop/Page/Layout` into `themes/{yourtheme}/templates/SilverShop/Page/Layout` folder, rename as `CheckoutPage.ss` and add in the payment method section:
```html
    <% with SiteConfig %>
        <div>
            $BankAccountPaymentMethodMessage
        </div>
    <% end_with %>
```
* Following completion of the checkout steps, on the order review page, provide an opening statement to encourage the customer to promptly make a transfer into the organisation's bank account.  Copy both `CheckoutPage_order.ss` (for guests) and `AccountPage_order.ss` (for members) from Silvershop's `templates/SilverShop/Page/Layout` into `themes/{yourtheme}/templates/SilverShop/Page/Layout` and add:
```html
    <%-- before the order --%>
    <% if $Status == "Unpaid" %>
        <% include SilverShop\Model\Order_BankDepositNeededStatement %>
    <% end_if %>
```
* Provide some customisation to the Total Outstanding for cancelled orders.  Copy `Order.ss` from Silvershop's `templates/SilverShop/Model` into `themes/{yourtheme}/templates/SilverShop/Model/Order.ss` and replace `$TotalOutstanding.Nice` with:
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
* In addition, provide full instructions beneath the Notes table of `Order.ss`:
```html
    <% if $Total %>
        <% if $TotalOutstanding %>
            <% if $Payments %>
                <% loop $Payments.last %>
                    <% if $GatewayTitle == "Bank Deposit" %>
                        <% include SilverShop\Model\Order_BankDeposit %>
                    <% end_if %>
                <% end_loop %>
            <% end_if %>
        <% end_if %>
    <% end_if %>
```

## Email Customisations
To customise copy SilverShop's `templates/SilverShop/Model/Order_ConfirmationEmail.ss` to `themes/{yourname}/templates/SilverShop/Model/Order_ConfirmationEmail.ss` and arrange/style as needed.
Here is an example:
```html
<% if $Order %>
    <% with $Order %>
        <% if $Member %>
            <% with $Member %>
                Hi $FirstName
            <% end_with %>
        <% else %>
            Hi $FirstName
        <% end_if %>
        <% if $Status == "Unpaid" %>
            <% include SilverShop\Model\Order_BankDepositNeededStatement %>
        <% end_if %>
        Thanks
        Kind regards
        Your Name
    <% end_with %>
<% end_if %>
```
Override the defaults in your `app/lang/en.yml` file (see SilverShop's `lang/en.yml` for an example).

For more information see [Enable / disable sending of emails](https://github.com/silvershop/silvershop-core/blob/master/docs/en/02_Customisation/Emails.md)
