# Talking Walls Website [Wordpress]

## Language Requirements
- PHP: 7.2
- MySql: latest

## Install Package Managers

### Install Composer
- Download [Composer](https://getcomposer.org/download) and follow set up guide
- Install [Wordpress CLI](https://wp-cli.org/)
	- Download Binary `curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar`
	- Make binary executable `chmod +x wp-cli.phar`
	- Move binary to PATH `mv wp-cli.phar /usr/local/bin/wp`
	- Test to ensure working `wp --info`

## Install Wordpress Core
- `wp core download`

## Update Env
Copy the config file and update the required credentials
- `cp wp-config-example.php wp-config.php`

## Update .htaccess file for remote uploads
```
RewriteCond %{REQUEST_URI} ^\/wp-content\/uploads(.*) [NC]
RewriteCond %{HTTP_HOST} ^dev\.talkingwallscharlotte\.com$
RewriteRule ^(.*)$ http://www.talkingwallscharlotte.com/$1 [R=302,NC,L]
```

## Update Database
- String replace urls
    - `./replace staging`
    - `./replace production`
