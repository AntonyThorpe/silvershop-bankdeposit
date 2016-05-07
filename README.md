# silvershop-bankdeposit
A Silvershop submodule that adds bank deposit as a payment method

[![Build Status](https://travis-ci.org/AntonyThorpe/silvershop-bankdeposit.svg?branch=master)](https://travis-ci.org/AntonyThorpe/silvershop-bankdeposit)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/antonythorpe/silvershop-bankdeposit/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/antonythorpe/silvershop-bankdeposit/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/antonythorpe/silvershop-bankdeposit/v/stable)](https://packagist.org/packages/antonythorpe/silvershop-bankdeposit)
[![Total Downloads](https://poser.pugx.org/antonythorpe/silvershop-bankdeposit/downloads)](https://packagist.org/packages/antonythorpe/silvershop-bankdeposit)
[![Latest Unstable Version](https://poser.pugx.org/antonythorpe/silvershop-bankdeposit/v/unstable)](https://packagist.org/packages/antonythorpe/silvershop-bankdeposit)
[![License](https://poser.pugx.org/antonythorpe/silvershop-bankdeposit/license)](https://packagist.org/packages/antonythorpe/silvershop-bankdeposit)

## Features
* Provides a Bank Account tab under Settings/Shop to add your organisation's bank account number and payment messages
* Includes bank account number & messages in emails, the order review, and during the checkout process
* Localisation options available

## How it works
* Utilises the 'Manual' payment method (names it "Bank Deposit")
* You add various includes to your templates

## Requirements
* [Silvershop module (a Silverstripe module)](https://github.com/silvershop/silvershop-core)

## Warning
It is assumed that people accessing the Setting folder can be trusted.  This is where the bank account number is entered.  If you think that a YAML file would be more secure, then please feel free to submit a pull request.

## Documentation
[Index](/docs/en/index.md)

## Support
None sorry.

## Contributions
Pull requests welcome!  PSR-2 please.

## License
[MIT](LICENCE)


