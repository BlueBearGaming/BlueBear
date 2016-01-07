all: install


install: install-apt install-composer install-npm copy-assets

install-apt:
	sudo apt-get install npm nodejs nodejs-legacy

install-composer:
	composer install

install-npm:
	npm install

copy-assets:
	cp node_modules/socket.io/node_modules/socket.io-client/socket.io.js web/js/socket.io.js


run-server:
	nodejs bluebear-node.js
