with open('keywords.txt', 'r') as file:
    # Read the contents of the file
    contents = file.read()


import json
import whois
from datetime import datetime
import pycountry


domain = contents
result = whois.whois(domain)

#full country name
def get_country_name(code):
    try:
        country = pycountry.countries.get(alpha_2=code)
        if country:
            return country.name
        else:
            return "Unknown"
    except Exception as e:
        print(f"Error retrieving country name: {e}")
        return "Unknown"

# Define a custom JSON encoder class
class CustomJSONEncoder(json.JSONEncoder):
    def default(self, obj):
        if isinstance(obj, datetime):
            return obj.strftime('%Y-%m-%d %H:%M:%S')
        return super().default(obj)

# Use the custom encoder when dumping the data to JSON
result["country"] = get_country_name(result["country"])
data = json.dumps(result, cls=CustomJSONEncoder)

print(data)
