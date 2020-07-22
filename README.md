# Installation

1. Run:
```
git clone <repository_url>
```

2. Make sure that
```
APP_ENV=prod
```
(in .env file)
if not, change it

3. Run:
```
composer install --no-dev --optimize-autoloader
```

4. Run (MySQL CLI or via phpMyAdmin):
```
CREATE USER 'currencies_app'@'localhost' IDENTIFIED BY 'Escaw8Bdz8MAER';
GRANT ALL PRIVILEGES ON * . * TO 'currencies_app'@'localhost';
```

5. Run:
```
php bin/console doctrine:database:create
php bin/console doctrine:schema:update --force
php bin/console app:update-historical-exchange-rates
php bin/console cache:clear
```

6. Optional - add Cron jobs:
php bin/console app:update-historical-exchange-rates (run command once per day, e.g. at 00:30)
php bin/console app:update-current-exchange-rates (run command every 10 minutes)

# Usage

Current exchange rates for all currencies:
GET /api/exchangerates/current

Historical exchange rates for selected currency (filtering by currency code):
GET /api/exchangerates/currency_code/order_by_date

For example - historical rates for EUR:
GET /api/exchangerates/EUR/asc (in ascending order)
GET /api/exchangerates/EUR/desc (in descending order)

Without explicit order - default order is descending:
GET /api/exchangerates/EUR

