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
To change 'Bank Deposit' to something else then create `mysite/lang/en.yml`:
```yml
en:
  Gateway:
    Manual: Bank Deposit Changed
```
Then `{example.net}/dev/build/?flush=1`
