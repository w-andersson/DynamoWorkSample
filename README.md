# DynamoWorkSample

## Install the Application

Run this command in order to install the package dependencies. You will require [PHP 7.4 or newer](https://www.php.net/downloads) and [Composer](https://getcomposer.org/).

```bash
composer install
```

To run the application on a server using a webserver such as Apache, make sure to:
* Point your virtual host document root to your new application's `public/` directory.
* Ensure `logs/` is web writable.

To run the application in development, you can run these commands 

```bash
composer start
```

Or you can use `docker-compose` to run the app with `docker`, so you can run these commands:
```bash
docker-compose up -d
```
After that, open `http://localhost:8080` in your browser.

Run this command in the application directory to run the test suite

```bash
composer test
```
