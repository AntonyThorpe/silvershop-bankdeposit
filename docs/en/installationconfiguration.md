# Installation and Configuration of Silvershop Bank Deposit

# Installation
In a terminal
`composer require antonythorpe/silvershop-bankdeposit`

## Configuration
In your `app/_config/bankdeposit.yml`, or similar, add:
```yml
SilverStripe\Omnipay\Model\Payment:
  allowed_gateways:
    - 'Manual'
```
To change 'Bank Deposit' to something else then create `app/lang/en.yml`:
```yml
en:
  Gateway:
    Manual: Bank Deposit Changed
  SilverShop\ShopEmail:
    ConfirmationTitle: "Your business name - Tax Invoice #%d"
    ConfirmationSubject: "Your business name - Tax Invoice #%d - Please deposit funds"
```
Then `{example.net}/dev/build`

## Disable Unpaid Order Email
Emailing a copy of an unpaid Order to the customer with your bank deposit details is enabled by default.  To deactivate:
```yml
SilverShop\Checkout\OrderProcessor:
  bank_deposit_send_confirmation: false
```
