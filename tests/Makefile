all: js php
js: test*.js
	nodejs ../node_modules/.bin/mocha -u tdd $?
php: *Test.php
	phpunit --bootstrap ../Ball.php $?