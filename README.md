What we have in this project
============================
* Integration of Bootstrap
* API
  * We can read, create, updated, delete tweets from the API
  * We can render the tweets from the API
* Integration of login system (can login with username and also with email)
* My page, where it only shows your own tweets
* Home page, where it shows all the tweets
* Pagination
* Ability to add/edit/remove your own tweets only when you are logged in the system
* Security checks to make sure another user can't create, update, or delete your tweets in your name, unless that user is logged in the system

Requirements
============

* node.js
* npm
* less
* php5 
* apache2
* mysql

To install them on a debian machine just run the following commands with root privileges

```bash
apt-get install nodejs nodejs-legacy npm php5 apache2 git mysql-server mysql-client
npm -g install less
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
```

We need to add the site to apache2 sites 

```
<VirtualHost 127.0.0.1:80>
  ServerName sy2try.local
  DocumentRoot "/var/www/symfony2-tryout/web"
  DirectoryIndex app.php
	ErrorLog ${APACHE_LOG_DIR}/sy2try.local.error.log
	CustomLog ${APACHE_LOG_DIR}/sy2try.local.access.log combined
  <Directory "/var/www/symfony2-tryout/web">
        AllowOverride all
        Require all granted
  </Directory>
</VirtualHost>
```

In the /etc/hosts file we add this
```
127.0.0.1   sy2try.local
```

Lets create a new user for this application

```mysql
CREATE USER 'symfony'@'localhost' IDENTIFIED BY 'symfony';
GRANT ALL PRIVILEGES ON sym2tryout.* TO 'symfony'@'localhost';
FLUSH PRIVILEGES;
```

Now with the user www-data 

```bash
su - www-data
cd /var/www
git clone https://github.com/ilijamt/symfony2-tryout.git
composer update
php app/console cache:clear
php app/console cache:clear --env=prod --no-debug
php app/console assets:install web --symlink
php app/console assetic:dump
php app/console braincrafted:bootstrap:install
php app/console braincrafted:bootstrap:generate
php app/console doctrine:database:drop --force
php app/console doctrine:database:create
php app/console doctrine:schema:drop --force
php app/console doctrine:schema:update --force
php app/console doctrine:fixtures:load
```

Now you should have a fully populated database so we can start playing with it right away

Users
=====
| Username | Password | Email               | Name               |
|----------|----------|---------------------|--------------------|
| admin    | admin    | admin@admin.com     | Admin Adminstrator |
| demo     | demo     | demo@demo.com       | Demo Demonstration |
| newuser  | newuser  | newuser@newuser.com | New User           |