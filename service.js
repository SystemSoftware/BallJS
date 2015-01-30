var express = require('express')
var bodyparser = require('body-parser')
var app = express()

balls = []
var server

//notwendig fuer das Parsen von POST-Daten
app.use(bodyparser.urlencoded({}))

function logIt(request, response, details){
    date = new Date().toString()
    console.log("%s - %s %s %s", date, request.method, response.statusCode, request.url)
    if(details!=""){
        console.log(details)
    }
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
          logIt(request, response, "")
      })

      app.post('/', function (request, response){

         var post = {},
             details = ""
         if (request.body["ball"] != undefined) {
            post = JSON.parse(request.body["ball"])
         }

         var name = post["id"]

         if (name != undefined){
            var new_ball = post
            details = 'New ball received: ' + JSON.stringify(new_ball)
            new_ball["hop-count"]++
            new_ball["payload"]["NodeJS"] = "hamhamhamäßé文字"
            
            balls.push(new_ball)
            var output = JSON.stringify(new_ball)
            response.set("Content-Type", "application/json; charset=utf-8")
            response.send(output)
            response.statusCode = 200;
            response.end()
         }
         else{
            details = "Invalid ball received."
            response.statusCode = 400
         }
         response.end()
         logIt(request, response, details)
      })
   })
   return server
}
exports.createServer = createServer;
exports.closeServer = closeServer;

