#help: @ List available tasks on this project
#A comment with specific format needs to be added before each function (see examples)
help:
	@grep -E '[a-zA-Z\.\-\/]+:.*?@ .*$$' $(MAKEFILE_LIST)| tr -d '#' | awk 'BEGIN {FS = ":.*?@ "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

#restart: @ Docker containers restart
build: start build/backend stop

#start: @ Docker containers start
start:
	@(DOCKER_CLIENT_TIMEOUT=300 COMPOSE_HTTP_TIMEOUT=300 docker-compose up -d --remove-orphans)

#stop: @ Docker containers stop
stop:
	@docker-compose stop

#restart: @ Docker containers restart
restart: stop start

#build/backend: @ Build backend
build/backend:
	@echo "Backend: doing composer install..."
	@docker-compose run --rm backend composer install
	@docker-compose run --rm backend bash -c "./bin/console cache:warmup"

#backend/console: @ Runs bash on backend container
backend/console:
	@docker-compose exec backend bash

#php-code-style: @ Scans the entire codebase to sniff non-standard code
php-code-style:
	@docker-compose exec backend vendor/bin/phpcs -p --report=summary,source,code --parallel=8

#php-code-beautifier-and-fixer: @ Fix errors detected by PHP Code Sniffer
php-code-beautifier-and-fixer:
	@docker-compose exec backend vendor/bin/phpcbf -p --parallel=8

#psalm: @ Scans the entire codebase to find code problems
psalm:
	@docker-compose exec backend vendor/bin/psalm --threads=8

#psalm-clearing-cache: @ Clears Psalm cache
psalm-clearing-cache:
	@docker-compose exec backend vendor/bin/psalm --clear-cache
	@docker-compose exec backend vendor/bin/psalm --clear-global-cache
	@docker-compose exec backend vendor/bin/psalm --threads=8

#tests/backend: @ Runs backend tests
tests/backend:
	@docker-compose exec backend vendor/bin/phpunit --testsuite Unit