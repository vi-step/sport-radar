## 1. Install PhpStorm Plugin: https://plugins.jetbrains.com/plugin/9333-makefile-language
## 2. Specify path to 'make' utility in PhpStorm settings: Build->Build Tools->Make

start-docker:
	docker-compose up -d --build

db-console:
	docker-compose exec mysql bash -c "mysql -u $$MYSQL_USER -p$$MYSQL_PASSWORD $$MYSQL_DATABASE"

db-init:
	docker-compose exec mysql bash -c "mysql -u $$MYSQL_USER -p$$MYSQL_PASSWORD $$MYSQL_DATABASE < /docker-entrypoint-initdb.d/schema.sql"

sh:
	docker-compose exec php bash

start:
	docker-compose exec php php run.php start $(filter-out $@,$(MAKECMDGOALS))

finish:
	docker-compose exec php php run.php finish $(filter-out $@,$(MAKECMDGOALS))

update:
	docker-compose exec php php run.php update $(filter-out $@,$(MAKECMDGOALS))

summary:
	docker-compose exec php php run.php summary $(filter-out $@,$(MAKECMDGOALS))

reset:
	docker-compose exec php php run.php reset $(filter-out $@,$(MAKECMDGOALS))

create-db-schema:
	docker-compose exec php php run.php create-db-schema $(filter-out $@,$(MAKECMDGOALS))

test:
	docker-compose exec php ./vendor/bin/phpunit tests
