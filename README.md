# Teste Ditech

## Pré Requisitos

* PHP >= 7.0
* composer
* mysql

## Instalação

* git clone https://github.com/costamauricio/teste-ditech.git
* cd teste-ditech && composer install

## Executando via Docker

* docker-compose up --build -d
* docker-compose exec mysql /bin/sh -c 'mysql -u root -proot < /dump/init.sql'
* Acessar no navegador http://localhost:8080

## Executando Local

* Configurar os dados de conexão no arquivo config.ini
* Rodar o arquivo da estrutura dump/init.sql
* cd public && php -S 0.0.0.0:8080
* Acessar no navegador http://localhost:8080
