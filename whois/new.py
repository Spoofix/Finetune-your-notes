# import whois
# import json

# domain = "google.com"
# result = whois.whois(domain)
# # data = json.dumps(result) 

# # print(data)
# print(result)
# Open the file in read mode
with open('keywords.txt', 'r') as file:
    # Read the contents of the file
    contents = file.read()

# Print the contents of the file
# print(contents)


import json
import whois
from datetime import datetime

domain = contents
result = whois.whois(domain)

# Define a custom JSON encoder class
class CustomJSONEncoder(json.JSONEncoder):
    def default(self, obj):
        if isinstance(obj, datetime):
            return obj.strftime('%Y-%m-%d %H:%M:%S')
        return super().default(obj)

# Use the custom encoder when dumping the data to JSON
data = json.dumps(result, cls=CustomJSONEncoder)

print(data)
