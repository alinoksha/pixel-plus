bash-php:
	docker exec -it pixel-plus-php bash

composer-install:
	docker exec -it pixel-plus-php composer install

up:
	docker-compose up -d

setup:
	docker-compose up -d --build
	make composer-install
