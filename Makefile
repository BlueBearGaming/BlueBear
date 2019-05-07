.PHONY: help
help: ## This help
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(TARGETS) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

#-include docker/.env
#docker/.env:
#	cp docker/.env.dist docker/.env
#	$(MAKE) docker/.env

.PHONY: install
install: start ## Run docker instance and launch composer install
	cd docker && docker-compose exec www composer install

.PHONY: start
start: jikpoze docker/.env ## Start docker
	cd docker && docker-compose up --build -d

.PHONY: stop
stop: ## Stop and destroy docker images
	cd docker && docker-compose down

.PHONY: shell
shell: ## Deploy to staging
	cd docker && docker-compose exec www zsh -c "export COLUMNS=`tput cols`; export LINES=`tput lines`; exec zsh"

.PHONY: reset-bdd
reset-bdd: ## Reset the database completely and load fixtures
	cd docker && docker-compose exec www zsh -c "app/console doctrine:schema:drop --force && app/console doctrine:schema:create && app/console doctrine:fixtures:load --append"

jikpoze: ## Initialize Jikpoze project
	git clone https://github.com/BlueBearGaming/Jikpoze.git jikpoze

.PHONY: assets@watch assets@install assets@build
assets@install:
	yarn install

assets@build:
	yarn run encore dev

assets@watch:
	yarn run encore dev --watch

.PHONY: serve
serve:
	bin/console server:run --env=dev

### Database ###
.PHONY: database@drop database@update database@drop-and-load-fixtures

database@drop:
	bin/console doctrine:schema:drop --force

database@update:
	bin/console doctrine:schema:update --force

database@drop-and-load-fixtures: database@drop database@update
	bin/console hautelook:fixtures:load -n
################
