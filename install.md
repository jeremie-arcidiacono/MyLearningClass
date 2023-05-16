# Install procedure for MyLearningClass

The following procedure describes how to install the project on a Linux server.

These instructions are intended to be as simple as possible, but if you know what you are doing,
you can try to install without following exactly the steps below.

## Pre-requisites

The projet installation has been tested on <b>Ubuntu 22.04 LTS</b>.
It should work on other Linux distributions, but it has not been tested.

The following packages are required:

- PHP 8.2 with the following extensions:
    - pdo_mysql
    - mbstring
    - xml
- Apache 2.4 (with mod_rewrite)
- MariaDB 10
- PHP [Composer](https://getcomposer.org/download/) (for the installation of the project dependencies)
- FFmpeg tools (for the video duration calculation)

If all the packages are already installed, you can go directly to the [Installation](#installation) section.<br>
Otherwise, you can follow the instructions below :

#### PHP

```bash
sudo apt install software-properties-common ca-certificates lsb-release apt-transport-https 
sudo add-apt-repository ppa:ondrej/php
sudo apt update 
sudo apt install php8.2 php8.2-mysql php8.2-mbstring php8.2-xml
```

Then install Composer:

```bash
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('sha384', 'composer-setup.php') === '55ce33d7678c5a611085589f1f3ddf8b3c52d662cd01d4ba75c0ee0459970c2200a51f492d557530c71c15d8dba01eae') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
php composer-setup.php
php -r "unlink('composer-setup.php');"

sudo mv composer.phar /usr/local/bin/composer
```

#### Apache

```bash
sudo apt install apache2
```

Enable the rewrite module and restart Apache:

```bash
sudo a2enmod rewrite
sudo systemctl restart apache2
```

#### MariaDB

```bash
sudo apt install mariadb-server mariadb-client
sudo mysql_secure_installation
```

Follow the instructions to secure your MariaDB installation.

#### FFmpeg

```bash
sudo apt install ffmpeg
```

## Installation

For the installation, we will assume that the project will be installed in the `/var/www` directory.
Our working directory will be `/var/www/MyLearningClass/src`.

#### Web server configuration

Config the Apache virtual host:

```bash
sudo vim /etc/apache2/sites-available/000-default.conf
```

Write/add the following lines:

```
<VirtualHost *:80>
    ServerName {YOUR_DOMAIN_NAME}
    DocumentRoot /var/www/MyLearningClass/src/public
    <Directory /var/www/MyLearningClass/src/public>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

Install the project dependencies:

```bash
composer install
```

Allow the web server to write in the `storage` directory:
(There are several ways to do this, this is just one of them)

```bash
sudo chown -R www-data:www-data storage
```

#### Configuration of PHP

You can edit your php.ini file as you wish, except for the parameters about the session management: this is managed by the project config files.

Here is an example of the values you might want to change:

```ini
upload_max_filesize = 100M
post_max_size = 100M
memory_limit = 512M
```

And for debugging:

```ini
display_errors = On
display_startup_errors = On
error_reporting = E_ALL
```

#### Database configuration

Connect to the MariaDB server:

```bash
mysql -u root -p
```

Create the database:<br>
(you can use the file my_learning_class-withExampleData.sql to create the database for demonstration purposes,
the password for pre-created users is
"SuperMDP1!")

```
source ../database/my_learning_class.sql;
```

Create a user for the project:<br>
Don't forget to <b>change the password</b>!

```sql
CREATE USER 'mlc_user'@'localhost' IDENTIFIED BY 'password';
GRANT SELECT, INSERT, UPDATE, DELETE ON my_learning_class.* TO 'mlc_user'@'localhost';
FLUSH PRIVILEGES;
```

#### Configuration of the project

Copy the file `.env.example` to `.env`:

```bash
cp .env.example .env
```

Edit the `.env` file and change the lines as needed. Especially the database connection parameters.

```
DB_NAME=my_learning_class
DB_USER=mlc_user
DB_PASSWORD=password
```

You can also enable the recaptcha by setting the following parameters:

```
RECAPTCHA_ENABLED=true
RECAPTCHA_SITE_KEY=your_site_key
RECAPTCHA_SECRET_KEY=your_secret_key
```

The other parameters can be left as they are for a production environment.
Note: If you want to activate the debug mode, you must have a working Xdebug installation.

#### Test the installation

You can now access the project at the address you have configured in the Apache virtual host.

Be careful about the permissions of the `storage` directory, the web server must be able to write in it.
It is recommended to test the creation of a course to check that everything is working properly.

## Contact

If you have any questions, you can contact me at the following address: [jeremie@arcidiacono.dev](mailto:jeremie@arcidiacono.dev)
