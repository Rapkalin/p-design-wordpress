![wordpress](https://img.shields.io/badge/wordpress-v6.2-0678BE.svg?style=flat-square)
![php](https://img.shields.io/badge/PHP-v8.1-828cb7.svg?style=flat-square)
![Node](https://img.shields.io/badge/node-v18-644D31.svg?style=flat-square)
![composer](https://img.shields.io/badge/composer-v2-126E75.svg?style=flat-square)

## HOW TO INSTALL THE PROJECT

### 1/ BACKEND

#### 1.1_ Create the directory on your computer
```
mkdir your-directory-name
```

#### 1.2_ Clone the website project from your directory (don't forget the . at the end):
```git
git clone git@github.com:Rapkalin/p-design-wordpress.git .
```

#### 1.3_ Move to the project directory and install the backend dependencies:
- cd your-directory-name
- composer install

#### 1.4_ Copy the .env.sample file, rename it to .env and complete the needed variables:
```
DATABASE_NAME='your-database-name'
DATABASE_USER='your-database-username'
DATABASE_PASSWORD='your-database-password'
DATABASE_HOST='your-host'

WP_ENV=local
WP_CONTENT_URL=http://p-design.local/
WP_SITEURL=http://p-design.local/

# USED FOR DATABASE IMPORT SCRIPTS
PROD_HOST=
PROD_USER=
PROD_SITEURL=
DATABASE_PROD_HOST=
DATABASE_PROD_NAME=
DATABASE_PROD_USER=
DATABASE_PROD_PASSWORD=
```

#### 1.5_ Configure your vHost
- ServerName: p-design.local
- Directory: your-directory-name/website
```
  <VirtualHost *:80>
    ServerName p-design.local
    DocumentRoot "/Users/your-username/parent-directory-name/your-directory-name/website"
    ServerAlias p-design.local.*
    <Directory "/Users/your-username/parent-directory-name/your-directory-name/website">
      Options Includes FollowSymLinks
      AllowOverride All
    </Directory>
 </VirtualHost>
```

#### 1.6_ Import prod database
```
cd your-directory-name
php scripts/import-db.php
```

#### 1.7_ Import the uploads directory from prod to local
```
cd your-directory-name
php scripts/sync-uploads.php
```

### 2/ FRONTEND
- None

## MEANING OF SOME DIRECTORIES AND FILES

### 4/ WEBSITE/APP
This directory replace the wordpress-core/wp-content native Wordpress directory.
This is where you will find all the plugins, themes etc:
- Languages: directory that handle the translations of your website. It is created by Wordpress when you configure the default language of your Wordpress website.
- Uploads: contains all the website's media files
- Plugins and themes: where are all the plugins & themes and custom plugins & themes/child-themes

### 5/ HOW TO DEPLOY ON PREPROD
To use the auto-deploy using Github Workflows please follow the below instructions:
- Commit and push your branch
```
  gco your-branch
  git add your-files
  git commit -m "your comment"
  git push
```
- Merge your branch to develop
- Push develop, this will automatically deploy your changes on preprod
```
  gco develop
  git merge your-branch"
  git push
```

### 6/ SCRAPPING SCRIPT
/!\ Make sure the Selenium server is installed on your computer
PHP package: php-webdriver
Webdriver: Selenium
Selenium version: Selenium server version: 3.141.59. Selenium serveur runs on port 4444
Browser: Firefox

- The scrapping script is located in the scripts directory
- To run the script, you need to be in the root directory of the project
- Run the script with the following command and adding the website to scrap as an argument in lowcase (see example below):
- The list of the authorized websites are listed in scripts/scrapping/scrapping.php.

- The below command is an example with the website Pedrali.
```
  php scripts/scrapping/scrapping.php pedrali
```

- Start the webdriver with the following command:
```
  webdriver-manager start 
```

- If there is an error, try to update the Selenium webdriver with the following command:
```
  webdriver-manager update --versions.firefox
  webdriver-manager start
```
