# Frostnova
Fully customizable and [PSR](https://www.php-fig.org) compatible PHP framework

1) Run `composer create-project frostnova/starter <folder>` to create a new project.
2) Add `127.0.0.1 frostnova.local` to etc/hosts
3) Prepare development environment
    - With Docker
      - Run `composer run add-docker` from the newly created project to add Docker development environment.
      - Run `docker-compose up -d`
    - Without Docker
      - Start your local http server
4) Go to http://frostnova.local in your browser
5) The page should show JSON data
