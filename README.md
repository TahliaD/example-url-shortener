### Summary

This assignment was to implement a URL shortening service using PHP and Slim

### How To Use

Go to the project directory and run `composer install` to install all project dependencies.

For the purposes of reviewing the implementation you can run a local PHP server on your machine.
In your terminal run `php -S localhost:8080 -t public public/index.php`
You can run against any other port number if 8080 is in use on your machine.

You will now be able to send requests to localhost:8080.

### Endpoints

The projects has two endpoints that expect and return JSON in their response.
You can make these requests in any way that you choose. I prefer to use the app Postman. 

REQUEST:

POST : `localhost:8080/encode`

{
    url: "https://long-webadress.co.uk/big-long-long-url
}


will return:

{
    url: "https://short.co/shorty
}

REQUEST:

GET : `localhost:8080/decode`

{
    url: "https://short.co/shorty"
}

will return 

{
    url: "https://the-original-very-long-address/long-long-long
}

### How To Test
All tests can be run with the command `composer test` in your terminal

