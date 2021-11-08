![Library logo](public/images/github/logo.png)

[![Codacy Badge](https://app.codacy.com/project/badge/Grade/f05cd994261045b99622abf7a8d7ccbf)](https://www.codacy.com/gh/phil-all/Portfolio-OCR-Projet5/dashboard?utm_source=github.com&utm_medium=referral&utm_content=phil-all/Portfolio-OCR-Projet5&utm_campaign=Badge_Grade) [![Code Grade](https://www.code-inspector.com/project/29630/score/svg)](https://frontend.code-inspector.com/project/29630/dashboard)

## <span style="color: #269d24; text-decoration:underline;">Table of Contents</span>

-   [General info](#general-info)
-   [Project status](#project-status)
-   [Technologies](#technologies)
-   [Getting started](#getting-started)
    -   Composer
    -   .env file needed
    -   Database
-   [Features](#features)
    -   Dynamic routing
    -   JWT autentification
-   [Author](#author)
-   [Contributing](#contributing)

## <span style="color: #269d24; text-decoration:underline;">General info</span>

Over_Code blog is the fifth study project of: web application developper PHP & Symfony, by Openclassrooms.

## <span style="color: #269d24; text-decoration:underline;">Project Status</span>

> Project is: `in progress`

## <span style="color: #269d24; text-decoration:underline;">Technologies</span>

This project is created with:

-   Apache2 HTTP server
-   Mariadb 8.0 database
-   PHP 8
-   Bootstrap 5
-   Twig 3
-   Swiftmailer 6
-   Mailtrap (for testing in catching sent emails)
-   Json Web Tokens

## <span style="color: #269d24; text-decoration:underline;">Getting started</span>

### Composer

> The `composer.json` file is needed by composer.

If not yet installed on your system, follow the wiki: [Composer install and initialization](https://github.com/phil-all/Portfolio-OCR-Projet5/wiki/Composer-install).

composer.json will be replace by the one of the git repo.

### .env file needed

Rename `.env.dist` file in `.env` and set values "changethis" by your own as example follow

```bash
DSN=changethis
DB_USERNAME=changethis
DB_PASSWORD=changethis
```

could became :

```bash
DSN=mysql:host=name_of_your_host;dbname=name_of_your_db
DB_USERNAME=JOHNDOE
DB_PASSWORD=a_cray_pass
```

### Database

##### 1. *Database install*

// procedure to install database

##### 2. *Database Entity Relationship Diagram*

// Modèle physique de données

##### 3. *Enumeration list tables*

User section:

-   role <span style="color: #6f80a7">*a list of roles (1-admlin, 2-member)*</span>
-   user status <span style="color: #6f80a7">*a list of different status (1-pending, 2-active, 3-suspended)*</span>

Blog section:

-   comment_status <span style="color: #6f80a7">*a list of different status (1-pending, 2-validate, 3-suspended)*</span>

## <span style="color: #269d24; text-decoration:underline;">Features</span>

-   `Dynamic routing`, which don't use a routes file, but deduce classes ,methods and arguments in analizing URL parameters.

-   Token authentification user, `JWT` type.

## <span style="color: #269d24; text-decoration:underline;">Author</span>

**Philippe Allard-Latour**

-   [Twitter](https://twitter.com/AllardLatour)
-   [Github](https://github.com/phil-all)

## <span style="color: #269d24; text-decoration:underline;">Contributing</span>

This is a study project, thanks to **`NOT`** contributing.
