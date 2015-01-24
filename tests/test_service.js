#!/usr/bin/node

var assert = require('chai').assert;
var fs = require('fs');
var restify = require('restify');

var create_server = function() {
    // the test-db is created via the makefile
    require('../service.js').createServer(123456);
}

var create_client = function() {
    client = restify.createStringClient({
        //version: '*',
        url: 'http://127.0.0.1:123456'
    });
    return client;
};

suite('API', function() {

    before(function(done) {
        create_server();
        this.client = create_client();
        done();
    });

    after(function(done) {
        require('../service.js').closeServer();
        done();
    });

    test('404 if there´s no ball', function(done) {
        this.client.get('/', function(err, req, res, data) {
            assert.equal(res.statusCode, 404);
            done();
        });
    });

   test('post new invalid ball', function(done){
        this.client.post('/', {"ballxx" : "alksdjfas"}, function(err, req, res, data) {
            assert.equal(res.statusCode, 400);
            done();
        });
      });

   test('post new valid ball', function(done){
        this.client.post('/', {"ball" : JSON.stringify({"id": "Ball 1", "hold-time": 1, "hop-count": 5, "payload": {"Soap-Dings": 100, "JavaBeans": 120}})}, function(err, req, res, data) {
            assert.equal(res.statusCode, 200);
            done();
        });
      });

    test('new ball accepted. get it', function(done) {
        this.client.get('/', function(err, req, res, data) {
            assert.equal(res.statusCode, 200);
            assert.equal(data, '{"Ball 1":{"id":"Ball 1","hold-time":1,"hop-count":6,"payload":{"Soap-Dings":100,"JavaBeans":120,"NodeJS":"hamhamham"}}}');
            done();
        });
    });  

    test('try to get this ball again', function(done) {
        this.client.get('/', function(err, req, res, data) {
            assert.equal(res.statusCode, 404);
            done();
        });
    });
  
});

