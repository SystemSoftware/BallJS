import requests
import json

r = requests.get("http://localhost:3000")
print r.status_code
print r.text


print "--------------"

payload = {"ball" : json.dumps({"id": "Ball 1", "hold-time": 1, "hop-count": 5, "payload": {"Soap-Dings": 100, "JavaBeans": 120}})}
r2 = requests.post("http://localhost:3000", data = payload)

print r2.status_code
print r2.text
