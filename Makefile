composer\:install:
	docker-compose run composer install

composer\:require:
	docker-compose run composer require $(p)

composer\:update:
	docker-compose run composer update

tests\:run:
	@if [ $(f) ]; then\
	 docker-compose run phpunit ./vendor/bin/phpunit --filter $(f); else\
	 docker-compose run phpunit ./vendor/bin/phpunit; fi
