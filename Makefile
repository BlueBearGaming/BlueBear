.PHONY: help
help: ## This help
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(TARGETS) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

-include docker/.env
docker/.env:
	cp docker/.env.dist docker/.env
	$(MAKE) docker/.env

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

.PNONY: reset-bdd
reset-bdd: ## Reset the database completely and load fixtures
	cd docker && docker-compose exec www zsh -c "app/console doctrine:schema:drop --force && app/console doctrine:schema:create && app/console doctrine:fixtures:load --append"

jikpoze: ## Initialize Jikpoze project
	git clone https://github.com/BlueBearGaming/Jikpoze.git jikpoze

#install-apt:
#	sudo apt-get install npm nodejs nodejs-legacy
#
#install-packager:
#	npm install
#	node_modules/.bin/bower install
#	composer install
#
#copy-assets:
#	cp node_modules/socket.io/node_modules/socket.io-client/socket.io.js web/js/socket.io.js
#	cp bower_components/babylonjs/dist/babylon.2.2.js web/js/babylon.js
#
#run-server:
#	nodejs bluebear-node.js
#
#
#fire-fixtures:
#	bin/console doctrine:fixtures:load --fixtures src/BlueBear/FireBundle/DataFixtures/ORM
