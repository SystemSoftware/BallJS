REST_SERVER = http://localhost:3000
SOAP_SERVER_WSDL = http://localhost/?wsdl
BALL_JSON = 'ball={"id": "NodeJS_Ball", "hold-time": 3, "hop-count": 0, "payload":{}}'

ball: start_service.js
	curl -d $(BALL_JSON) $(REST_SERVER)

server: start_service.js
	nodejs start_service.js

client:
	php client.php $(SOAP_SERVER_WSDL) $(REST_SERVER)

