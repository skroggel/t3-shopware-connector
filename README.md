# Shopware Connector

**WARNING: This Extension is still in development. It may have bugs and breaking changes - use on your own risk!!!**

## Description
The Shopware Connector is a TYPO3 extension designed to connect to Shopware 6 store with TYPO3.

## Installation
1. Install the extension via Composer:
   ```bash
   composer require madj2k/shopware_connector
   ```
2. Activate the extension in the TYPO3 Extension Manager.

## Configuration
Configure the Shopware API credentials via the backend module "Shopware Connector".

## Tests
Run functional tests using the TYPO3 testing framework:
```bash
./vendor/bin/phpunit -c vendor/madj2k/t3-shopware-connector/phpunit.xml.dist

```
