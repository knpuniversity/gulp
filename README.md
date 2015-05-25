# Gulp! Refreshment for Your Frontend Assets

Well hi there! This repository holds the code and script
for the KnpUniversity lesson called:

[Gulp! Refreshment for Your Frontend Assets](http://knpuniversity.com/screencast/gulp)

## Installation

This is a Symfony project (though must of the tutorial doesn't depend
on that). To get it up and running, first install the vendor assets
via Composer and get the database up and running. If you get any
database errors, update the credentials in `app/config/parameters.yml`:s

```
composer install
php app/console doctrine:database:create
php app/console doctrine:schema:create
php app/console doctrine:fixtures:load

```

Next, download the frontend assets via [Bower](http://bower.io/):

```
bower install
```

Finally, run the built-in PHP web server:

```
php app/console server:run
```

Open up the site at http://localhost:8000. 

Have fun!
