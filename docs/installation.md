Installation
============

### 1. Check the requirements

- php 5.4 or above
- mysql 5
- git, composer
- extensions: iconv, pdo_mysql, intl

### 2. Clone the repository

Ensure you have git installed on your system.

```
git clone git@github.com:filipgorny/oro-academy-project.git issues
```

### 3. Create and configure MySQL database

- remember the database name, installation script will ask for it

### 4. Running installation scripts

This project is using composer to manage dependencies, ensure it is installed on your system
and provide a valid path to it when using below example commands.

WARNING: Composer installation may take some time, depending on the system.

#### WARNING: composer installation script has known bug of adding semicolon at the end of database
name in parameters.yml, you have to edit it manually to fix this issue.

```
composer install
vim app/config/parameters.yml
app/console oro:install
```

If something went wront with the installer, before relaunching it, clear the cache
with following command:

```
app/console cache:clear --no-debug
```

### 5. Run the app

Use following command:

```
app/console server:run
```

Then you should be able to access the website under [http://localhost:8000](http://localhost:8000) url.
