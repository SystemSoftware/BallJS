import requests

r = requests.get("http://localhost:3000")
print r.status_code
print r.text


print "--------------"

payload = {'increment': 2}
r2 = requests.post("http://localhost:3000", data = payload)

print r2.status_code
print r2.text
