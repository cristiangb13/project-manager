.DEFAULT_GOAL := help

help:## help
	@perl -e '$(HELP_FUN)' $(MAKEFILE_LIST)

up:## docker-compose up
	docker-compose -f docker-compose.yml up -d

build:## docker-compose build
	docker-compose -f docker-compose.yml build --no-cache

stop:## docker-compose stop
	docker-compose stop

down:## docker-compose down
	docker-compose down

restart:## docker-compose restart
	docker-compose restart

enter:## enter in container shell
	docker exec -it php-project-manager-service sh

test:## run unit tests
	docker-compose -f docker-compose.yml -p test stop
	docker-compose -f docker-compose.yml -p test run php-project-manager-service sh -c "php ./vendor/phpunit/phpunit/phpunit --testsuite=unit"
	docker-compose -f docker-compose.yml -p test down

composer-install:## run composer install
	docker exec -it php-project-manager-service sh -c "composer install --no-interaction"

fixer:## run php-cs-fixer in src
	docker exec -it php-project-manager-service sh -c "./vendor/friendsofphp/php-cs-fixer/php-cs-fixer --config=.php-cs-fixer.dist.php fix src tests"

setup: build up composer-install stop ## setup the project

HELP_FUN = \
    %help; \
    while(<>) { push @{$$help{$$2 // 'options'}}, [$$1, $$3] if /^([a-zA-Z\-]+)\s*:.*\#\#(?:@([a-zA-Z\-]+))?\s(.*)$$/ }; \
    print "usage: make [target]\n\n"; \
    for (sort keys %help) { \
    print "${WHITE}$$_:${RESET}\n"; \
    for (@{$$help{$$_}}) { \
    $$sep = " " x (32 - length $$_->[0]); \
    print "  ${YELLOW}$$_->[0]${RESET}$$sep${GREEN}$$_->[1]${RESET}\n"; \
    }; \
    print "\n"; }