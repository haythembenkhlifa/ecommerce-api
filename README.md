### Installation:

## Install required packages:

`composer install`

## Install sail container:

`php artisan sail:install`

Make sure to select mysql,redis,mailpit

## Run the containers:

`./vendor/bin/sail up`

## Run the migration and seed:

`./vendor/bin/sail artisan migrate:fresh --seed`

## Publish passport migrations:

`./vendor/bin/sail artisan passport:install`

## Generate a personal access client created for passport

`./vendor/bin/sail artisan passport:client --personal`

## Mailpit http://localhost:8025/

## telescope http://localhost:8025/
