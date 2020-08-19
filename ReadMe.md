# TestMe

TestMe is an easy-to-use open source online examination system for high schools built with Laravel and VueJS.

## Installation

Please check the official laravel installation guide for server requirements before you start. [Official Documentation](https://laravel.com/docs/7.x/installation)


Clone the repository

    git clone https://github.com/prismathic/TestMe.git

Switch to the repo folder

    cd TestMe

Install all the dependencies using composer

    composer install

Install frontend dependencies using npm

    npm install

Copy the example env file and make the required configuration changes in the .env file

    cp .env.example .env

Generate a new application key

    php artisan key:generate

Run the database migrations (**Set the database connection in .env before migrating**)

    php artisan migrate

Start the local development server

    php artisan serve

Run `php artisan db:seed` to seed a few subjects, classes, random student data and a default administrator into the database

The default administrator seeded into the database has a username and password of `admin`. (You could always change this from the seeder files in `database/seeds/AdminTableSeeder.php`)

You can now access the server at http://localhost:8000 and the admin interface at http://localhost:8000/admin

## Contributing

Wanna contribute? Fork the repo, make your changes and open a [pull request](https://github.com/prismathic/TestMe/pulls). I would be happy to go over it and merge if applicable.

## Acknowledgements

 - [Vick Greenfields](https://github.com/akintoluvic), for helping with the project's frontend in the beginning stages
 - [Tolu Makinde](https://github.com/Tmakinde), for helping with the admin role authorization logic.


Have fun building :)
