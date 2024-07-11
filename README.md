# Welcome to Multi SQL Manager

##  Table of contents
* [Introduction](#introduction)
* [Features](#features)
* [Technologies](#technologies)
* [Setup](#setup)

##  Introduction
The "Multi SQL Manager" is a Laravel web application, which will run raw sql for both Mysql and Pgsql directly from the UI 

##  Features:
 
 ### QUERY EXECUTION:
 - Execute Raw SQL directly from UI

### QUERY CONVERSION:
 - Convert MySQL Into PGSql (Not fully working, need improvement)
      
## Technologies
* PHP Laravel
* mySQL
* pgSQL
* HTML
* Java Script
* Bootstrap

## Setup

####  Installation
**requirements**

 1. PHP:  ^8.1
 2. Laravel : ^10.10

First clone this repository, install the dependencies, and setup your .env file.

**run the commands**

clone project
```
git clone https://github.com/Sajid-al-islam/multisql-manager.git
```

swith directory to project
```
cd multisql-manager
```

install dependencies
```
composer install
```

copy .env.example and paste as .env
```
cp .env.example .env
or copy .env.example .env
```

generate app key
```
php artisan key:generate
```

open in vs code editor
```
code .
```

open .env file and change db name. 
**database setup**
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_db_name
DB_USERNAME=root
DB_PASSWORD=

PGSQL_DB_CONNECTION=pgsql
PGSQL_DB_HOST=127.0.0.1
PGSQL_DB_PORT=5432
PGSQL_DB_DATABASE=your_db_name
PGSQL_DB_USERNAME=postgres
PGSQL_DB_PASSWORD=
```

Finally time to launch project, run
```
php artisan serve
```
the project will open at http://127.0.0.1:8000

or
```
php artisan serve --port=8001 | any supported port number
```
