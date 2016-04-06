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

## Requirements
* [Silvershop module (a Silverstripe module)](https://github.com/silvershop/silvershop-core) v1.3
* [Silverstripe Omnipay](https://github.com/burnbright/silverstripe-omnipay) v1.1

## Warning
It is assumed that people accessing the Setting folder can be trusted.  This is where the bank account number is entered.  If you think that settings from a YAML file would be more secure, then please feel free to submit a pull request.

## Documentation
[Index](/docs/en/index.md)

## Support
None sorry.

## Contributions
Pull requests welcome!  PSR-2 please.

## License
[MIT](/LICENCE.md)


