var express = require('express')
var bodyparser = require('body-parser')
var app = express()

zaehler = 0
verfuegbar = false

//notwendig fuer das Parsen von POST-Daten
app.use(bodyparser.urlencoded({ extended: false }))

function logIt(request){
	date = new Date().toString()
	console.log("%s - %s %s", date, request.method, request.url)
}
app.get('/', function (request, response) {
	logIt(request)
	if (verfuegbar == false){
		response.statusCode = 404
	}
	else{
		var output = JSON.stringify(zaehler)
		response.set("Content-Type", "application/json; charset=utf-8")
		response.send(output)
		response.statusCode = 200
		verfuegbar = false
	}
	response.end()
})

app.post('/', function (request, response){
	logIt(request)
	var post = request.body
	var inc = Number(post["increment"]) || 0
	zaehler += inc
	verfuegbar = true
	var output = JSON.stringify(zaehler)
	response.set("Content-Type", "application/json; charset=utf-8")
	response.send(output)
	response.statusCode = 200;
	response.end()
})
var server = app.listen(3000, function () {


var host = server.address().address
var port = server.address().port
console.log('Example app listening at http://%s:%s', host, port)

})

