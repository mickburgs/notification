## Setup

Requirements:
* php 8.3.13
* composer 2.8.2
* docker

Start application:
```bash
composer install
./vendor/bin/sail up
./vendor/bin/sail artisan migrate
```

Run tests:
```bash
./vendor/bin/sail test
./vendor/bin/phpcs app/*
```

Send notification:
```bash
update email in Order.routeNotificationForMail

 ./vendor/bin/sail artisan tinker
		\App\Models\Order::factory(5)->create();
		
curl --location 'http://localhost/order/1/mark-for-payment-request-notification'

./vendor/bin/sail artisan queue:work
```

## Process
The assesement specified using Laravel 10. This version is currently not getting bug fixes anymore and security fixes only until February 4th, 2025. Normally i would have asked if it would be okay to use version 11 but since it was saturday morning and i did not want to bother anyone i decided to continue and use version 10 like specified. 

Since it was my first time installing laravel i read the documentation at https://laravel.com/docs/10.x/installation and thought Sail would be a good option since it uses Docker, which makes it platform independent.
```bash
curl -s "https://laravel.build/notification" | bash
```
This installed the latest version (11) so i tried downgrading using
```bash
composer require laravel/framework:^10
```

Sadly this caused a dependency hell so i decided to start all over again using:
```bash
composer create-project --prefer-dist laravel/laravel notification "^10"
composer require laravel/sail --dev
```
After that i ran the following commands to setup the project
```bash
 ./vendor/bin/sail artisan make:model Order --migration
 ./vendor/bin/sail artisan make:notification PaymentRequest
 ./vendor/bin/sail artisan make:factory OrderFactory --model=Order
 ./vendor/bin/sail artisan tinker
		\App\Models\Order::factory(5)->create();

./vendor/bin/sail artisan make:test OrderPaymentRequestTest
./vendor/bin/sail artisan make:migration add_payment_request_sent_at_to_orders_table --table=orders

 ./vendor/bin/sail artisan make:event OrderSaved
 ./vendor/bin/sail artisan make:listener SendPaymentRequestNotification --event=OrderSaved
```
