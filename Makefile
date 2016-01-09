all: install


install: install-apt install-packager copy-assets

install-apt:
	sudo apt-get install npm nodejs nodejs-legacy

install-packager:
	npm install
	node_modules/.bin/bower install
	composer install

copy-assets:
	cp node_modules/socket.io/node_modules/socket.io-client/socket.io.js web/js/socket.io.js
	cp bower_components/babylonjs/dist/babylon.2.2.js web/js/babylon.js

run-server:
	nodejs bluebear-node.js
