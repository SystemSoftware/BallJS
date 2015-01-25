# BallJS

### Start Service
In the root-directory of BallJS please type:
```sh
$ nodejs start_service.js
```

While developing it is very helpful to restart the server when files are changed. You can reach this with the following commands:
```sh
$ sudo npm install forever -g
$ forever -w start_service.js
```

### Installation (Debian/Ubuntu)
First of all you have to install NodeJS with npm (Node Package Manager)
```sh
$ sudo apt-get install npm
```
Then you have to install all the Node-Packages we use in our project. In the root-directory of BallJS please type:
```sh
$ npm install
```