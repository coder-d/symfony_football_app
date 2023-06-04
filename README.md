# Symfony Football App

## Installation

1. Run the following command to install the required dependencies:

composer install

2. Set up the database

#uncomment line # DATABASE_URL= in .env file and the url

2. Load the fixtures by running the following command:

php bin/console doctrine:fixtures:load

## Database Migrations

To run the database migrations, execute the following commands:


1. For the default environment run the following command:

php bin/console doctrine:migrations:migrate

2. For the test environment run the following command:

php bin/console doctrine:migrations:migrate --env=test

## Testing 

1. Update the DATABASE_URL and BASE_URL in phpunit.xml.dist file

2. Run the following command:
php bin/phpunit --configuration phpunit.xml.dist --filter "/Entity/"
php bin/phpunit --configuration phpunit.xml.dist --filter "/Controller/"

