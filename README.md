# IPv4 and IPv6 calculator

Web application which provide you a details about IP v4 and v6 network subnet. This project is divided to two parts:
- Frontend (FE) - React application which is calling methods from Backend part
- Backend (BE) - PHP - Symfony application which is providing methods for calculate information regarding IP subnet via API. 

## How to use it

Download and unzip project file in to your folder or use git.

### `git clone https://github.com/patykmar/ip-calc.git`

## Download FE dependencies

For download dependencies please use `npm` tool [https://www.npmjs.com/get-npm](https://www.npmjs.com/get-npm).

### `cd <project-folder>/frontend`
### `npm install`

## Download BE dependencies

For download dependencies please use `composer` tool [https://getcomposer.org/download/](https://getcomposer.org/download/). 
You should have PHP CLI interpret installed on your computer. 

### `cd <project-folder>/backend`
### `composer install`

## Start BE part of application

Change directory to backend `cd <project-folder>/backend`. For start application you have two options:

- run via symfony tool `symfony server:start` - [https://symfony.com/download](https://symfony.com/download)
- or via PHP `php -S 127.0.0.1:8000 -t public`

You can check, if everything is working fine, when open your web browser and go to [http://localhost:8000](http://localhost:8000) address.

## Start FE part of application

Change directory to frontend `cd <project-folder>/frontend`.

- run via npm tool `npm start`

You can check, if everything is working fine, when open your web browser and go to [http://localhost:3000](http://localhost:3000) address.