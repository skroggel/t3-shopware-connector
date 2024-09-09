# Shopware Connector

## Description
The Shopware Connector is a TYPO3 extension designed to connect and import data from a Shopware 6 store into TYPO3.

## Installation
1. Install the extension via Composer:
   ```bash
   composer require madj2k/shopware_connector
   ```

2. Activate the extension in the TYPO3 Extension Manager.

## Configuration
1. Configure the Shopware API credentials via the backend module.
2. Set up a scheduled task to regularly import data from Shopware.

## Usage
1. Navigate to the "Shopware Connector" backend module to manage API settings.
2. Use the provided scheduler tasks to automate data imports.

## Tests
Run functional tests using the TYPO3 testing framework:
```bash
./vendor/bin/phpunit --testsuite functional
```

## Todo
Models anpassen
TCA anpassen
Translations anpassen
