# Installation and Configuration of Silvershop Bank Deposit

# Installation
`composer require antonythorpe/silvershop-bankdeposit`

## Configuration
In your `app/_config/bankdeposit.yml`, or similar, add:
```yml
SilverStripe\Omnipay\Model\Payment:
  allowed_gateways:
    - 'Manual'
```
To change 'Manual' to something else then create `app/lang/en.yml`:
```yml
en:
  Gateway:
    Manual: Bank Deposit
  SilverShop\ShopEmail:
    ConfirmationTitle: "Your business name - Tax Invoice #%d"
    ConfirmationSubject: "Your business name - Tax Invoice #%d - Please deposit funds"
```

## Disable Unpaid Order Email
Emailing a copy of an unpaid Order to the customer with your bank deposit details is enabled by default.  To deactivate:
```yml
SilverShop\Checkout\OrderProcessor:
  bank_deposit_send_confirmation: false
```
