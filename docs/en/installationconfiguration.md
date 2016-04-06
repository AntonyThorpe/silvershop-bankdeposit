# Installation and Configuration of Silvershop Bank Deposit

# Installation
In a terminal
`composer require antonythorpe/silvershop-bankdeposit`

## Configuration
In your `mysite/_config/bankdeposit.yml`, or similar, add:
```yml
Payment:
  allowed_gateways:
    - 'Manual'
```
Then `{example.net}/dev/build/`
