#################
# Variables
##################

DOCKER_COMPOSE = docker compose -f ./docker/docker-compose.yaml
DOCKER_COMPOSE_PHP_FPM_EXEC = ${DOCKER_COMPOSE} exec -u www-data php-fpm

##################
# Docker compose
##################

build:
	${DOCKER_COMPOSE} build
start:
	${DOCKER_COMPOSE} start
stop:
	${DOCKER_COMPOSE} stop
up:
	${DOCKER_COMPOSE} up -d --remove-orphans
ps:
	${DOCKER_COMPOSE} ps
logs:
	${DOCKER_COMPOSE} logs -f
down:
	${DOCKER_COMPOSE} down -v --rmi=all --remove-orphans
restart:
	make stop start
rebuild:
	make down build up

##################
# App
##################

bash:
	${DOCKER_COMPOSE_PHP_FPM_EXEC} bash
fixture:
	${DOCKER_COMPOSE_PHP_FPM_EXEC} php bin/console doctrine:fixtures:load
test:
	${DOCKER_COMPOSE_PHP_FPM_EXEC} php bin/phpunit
cache:
	${DOCKER_COMPOSE_PHP_FPM_EXEC} php bin/console cache:clear
	${DOCKER_COMPOSE_PHP_FPM_EXEC} php bin/console cache:clear --env=test

##################
# Database
##################

migrate:
	${DOCKER_COMPOSE_PHP_FPM_EXEC} php bin/console doctrine:migrations:migrate --no-interaction
diff:
	${DOCKER_COMPOSE_PHP_FPM_EXEC} php bin/console doctrine:migrations:diff --no-interaction
drop:
	${DOCKER_COMPOSE_PHP_FPM_EXEC} php bin/console doctrine:schema:drop --force


##################
# JWT
##################

generate-keypair:
	${DOCKER_COMPOSE_PHP_FPM_EXEC} php bin/console lexik:jwt:generate-keypair

##################
# Cache
##################

cache-warmup:
	${DOCKER_COMPOSE_PHP_FPM_EXEC} php bin/console cache:warmup
