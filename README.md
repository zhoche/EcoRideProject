# Ecorideproject

This project was generated using [Angular CLI](https://github.com/angular/angular-cli) version 19.2.10.
**EcoRide** is an eco-friendly ridesharing web application designed to promote responsible mobility between passengers and drivers. The platform facilitates carpooling while encouraging sustainable behaviors and reducing carbon emissions.

## Features
### User Profiles
- 4 types of users: **passenger**, **driver**, **employee**, and **admin**
- Role-based access and customized dashboards
- Authentication via login/register
- Session persistence using `localStorage`

### Authentication & Security
- Custom login and registration forms (Angular frontend)
- Backend validation using Symfony
- API route protection with **AuthGuard**
- Secure role-based redirection

### Front-End
- Built with **Angular**
- Responsive UI with SCSS
- Thunder Client integration for API tests

### Back-End
- Built with **Symfony 6**
- Doctrine ORM for database operations
- RESTful routes: `GET` and `POST` for `/api/rides`
- Data validation using Symfony's Validator component

### Tech Stack

| Frontend      | Backend      | Tools & Other         |
|---------------|--------------|------------------------|
| Angular 18    | Symfony 6    | Git + GitHub           |
| TypeScript    | PHP 8.4      | Composer + npm         |
| SCSS          | Doctrine ORM | Postman / Thunder Client |



## Development server

To start a local development server, run:

```bash
ng serve
```

Once the server is running, open your browser and navigate to `http://localhost:4200/`. The application will automatically reload whenever you modify any of the source files.

## Code scaffolding

Angular CLI includes powerful code scaffolding tools. To generate a new component, run:

```bash
ng generate component component-name
```

For a complete list of available schematics (such as `components`, `directives`, or `pipes`), run:

```bash
ng generate --help
```

## Building

To build the project run:

```bash
ng build
```

This will compile your project and store the build artifacts in the `dist/` directory. By default, the production build optimizes your application for performance and speed.

## Running unit tests

To execute unit tests with the [Karma](https://karma-runner.github.io) test runner, use the following command:

```bash
ng test
```

## Running end-to-end tests

For end-to-end (e2e) testing, run:

```bash
ng e2e
```

Angular CLI does not come with an end-to-end testing framework by default. You can choose one that suits your needs.



## Backend Installation (Symfony)

1. Navigate to the backend folder:
   ```bash
   cd backend


2. Install PHP dependencies via Composer:
bash
Copier
Modifier
composer install


3. Configure your database connection in the .env file. For example, using XAMPP and phpMyAdmin:

env
Copier
Modifier
DATABASE_URL="mysql://root:@127.0.0.1:3306/ecoride"

4. Start XAMPP, and ensure Apache and MySQL services are running.

5. Open http://localhost/phpmyadmin

