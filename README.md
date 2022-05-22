# Frostnova
Fully customizable and [PSR](https://www.php-fig.org) compatible PHP framework

1) Run `composer create-project frostnova/starter <folder>` to create a new project.
    - If you plan to use Docker, make sure that you have (locally) same PHP version as [the version `^8.0.0`](https://github.com/ironexdev/frostnova-docker/blob/master/images/app/Dockerfile) that is used in frostnova/docker
2) Prepare development environment
    - With Docker
      - Run `composer run add-docker` from the newly created project to add Docker development environment.
      - Edit domain in [http server configuration](https://github.com/ironexdev/frostnova-docker/blob/master/images/http-proxy/default.conf)
      - Run `docker-compose up -d`      
    - Without Docker
      - Start your local http server
3) Add `127.0.0.1 <domain>` to etc/hosts
4) Go to `http://<domain>` in your browser
5) The page should show JSON data
