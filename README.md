## Description

A simple web scraping app for me to play around.
Using Laravel 8, Laravel Dusk (WebDriver and Chromedriver)

Don't forget to install composer and Laravel

Install Laravel dusk
`composer require --dev laravel/dusk`

Execute the dusk:install Artisan command.
`php artisan dusk:install`

Create Test file via command
`php artisan dusk:make <NAME>`

Generated test file will are located in tests -> Browser. Modified based on what you need.

Run Web scraping as test from via command 
`php artisan dusk` This will run all the test file in the system.

Can be modifed to use from controller. Can change the input and output based on what you need.

## References
[Laravel Dusk](https://laravel.com/docs/8.x/dusk) - https://laravel.com/docs/8.x/dusk
