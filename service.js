var express = require('express')
var bodyparser = require('body-parser')
var app = express()

balls = []
var server

//notwendig fuer das Parsen von POST-Daten
app.use(bodyparser.urlencoded({}))

function logIt(request, response){
	date = new Date().toString()
	console.log("%s - %s %s %s", date, request.method, response.statusCode, request.url)
}

function closeServer(){
   server.close()
}

function createServer(port){
   var port = port || 3000
   server = app.listen(port, function () {

      console.log('NodeJs-Server is waiting for requests on port %s', port)
      app.get('/', function (request, response) {
         
	      if (balls.length == 0){
		      response.statusCode = 404
	      }
	      else{
		      var output = JSON.stringify(balls.shift())
		      response.set("Content-Type", "application/json; charset=utf-8")
		      response.send(output)
		      response.statusCode = 200
	      }
         
	      response.end()
	      logIt(request, response)
      })

      app.post('/', function (request, response){

         var post = {}
         if (request.body["ball"] != undefined) {
            post = JSON.parse(request.body["ball"])
         }

	      var name = post["id"]

         if (name != undefined){
            var new_ball = post
            new_ball["hop-count"]++
            new_ball["payload"]["NodeJS"] = "hamhamham"
            
            balls.push(new_ball)
	         var output = JSON.stringify(new_ball)
	         response.set("Content-Type", "application/json; charset=utf-8")
	         response.send(output)
	         response.statusCode = 200;
	         response.end()
         }
         else{
            response.statusCode = 400
         }
         response.end()
	      logIt(request, response)
      })
   })
   return server
}
exports.createServer = createServer;
exports.closeServer = closeServer;

