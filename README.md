# BallJS


## Usage
To start server and client, use
```sh
make server & make client
```

To generate a new ball and send it to the (already running) server, use
```sh
make ball
```

### Requirements
 * make
 * php5-cli
 * npm
 * nodejs


## Development

```sh
$ npm install
```

While developing it is very helpful to restart the server when files are changed. You can achieve this with the following commands:
```sh
$ sudo npm install forever -g
$ forever -w start_service.js
```

And also have a look at the Makefile within the ```tests```-directory.

### Additional Requirements
For development, in addition to the requirements for using BallJS, you will also need
 * phpunit
