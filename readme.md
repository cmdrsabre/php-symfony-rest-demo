# Exchange rates app

This is a simple currency converter written in PHP 7.1 with symfony using the fixer.io webservice for conversion rates

### Prerequisites

You need PHHP 7.1 and composer installed

### Installing

- clone or download repository
- use composer to install dependencies
```
composer install
```
- configure API Key in config/services.yaml to have access to fixer.io
- run with a webserver php internal for example
```
cd public
php -S localhost:8000
```
- open browser with page http://localhost:8000
