file_path = 'returnDraft.txt'
# cleaning results.txt content
with open(file_path, 'w') as file:
    pass  

with open('keywords.txt', 'r') as file:
    # Read the contents of the file
    contents = file.read()

    # Assuming you have a string containing the two domains separated by a comma
domain_string = contents

# Split the string at the comma to get the individual domains
domain_list = domain_string.split(',')

# Assign the domains to different variables
# domainOne = domain_list[0].strip()  # Use strip() to remove any leading or trailing whitespaces
domainTwo = domain_list[0].strip()


import requests
from bs4 import BeautifulSoup
import json
# from requests.cookies import RequestsCookieJarcookies

# Initialize an array to store key-value pairs
result_array = []

# Define the URL to inspect
url = f'https://{domainTwo}'
print(url)

# Send an HTTP GET request to the URL
response = requests.get(url)

# Check for redirects
if response.history:
    print('Redirects:')
    redirects = {}
    redirects_array = []
    for resp in response.history:
        redirect_info = f'{resp.status_code} - {resp.url}'
        print(redirect_info)
        redirects_array.append(redirect_info)
    redirects["Redirects"] = redirects_array
    result_array.append(redirects)
    final_url_info = {}
    final_url_info['Final_URL'] = response.url
    print(final_url_info)
    result_array.append(final_url_info)

# Check for redirects
# if response.history:
#     print('Redirects:')
#     with open(file_path, 'a') as file:
#         file.write("Redirects:")
#     for resp in response.history:
#         print(f'{resp.status_code} - {resp.url}')
#         with open(file_path, 'a') as file:
#             file.write(f'{resp.status_code} - {resp.url}\n')
#     print(f'Final URL: {response.url}')
#     with open(file_path, 'a') as file:
#         file.write(f'Final URL: {response.url}\n')

# Get the final HTTP status code
status_code_info = {}
status_code_info['HTTP_Status_Code'] = response.status_code
print(status_code_info)
result_array.append(status_code_info)


cookie_info = {'Cookies': []}
cookies = response.cookies

# Convert RequestsCookieJar to a list of dictionaries
cookies_list = []
for cookie in cookies:
    cookie_dict = {
        "name": cookie.name,
        "value": cookie.value,
        "domain": cookie.domain,
        "path": cookie.path,
        "expires": cookie.expires,
        "secure": cookie.secure,
        "httpOnly": cookie.has_nonstandard_attr('HttpOnly'),  # Check if HttpOnly attribute is present
    }
    cookies_list.append(cookie_dict)

# Replace the original RequestsCookieJar with the list of dictionaries
cookie_info['Cookies'] = cookies_list
result_array.append(cookie_info)
# for cookie in cookies:
#     cookie_detail = f'{cookie.name} - {cookie.value}'
#     print(cookie_detail)
#     result_array.append(cookie_detail)

# Parse the HTML content to check for console messages and security headers
if response.headers.get('content-type', '').startswith('text/html'):
    soup = BeautifulSoup(response.text, 'html.parser')
    scripts = soup.find_all('script')

    # Extract console messages
    console_messages = []
    for script in scripts:
        if script.string:
            console_messages.append(script.string)

    console_info = {}
    console_info['Console_Messages'] = console_messages
    # print(console_info)
    result_array.append(console_info)
    # for message in console_messages:
    #     print(message)
    #     result_array.append(message)

# Check security headers
security_headers = {
    'Content-Security-Policy': 'Content Security Policy header',
    'Strict-Transport-Security': 'HTTP Strict Transport Security header',
    'X-Content-Type-Options': 'X-Content-Type-Options header',
    'X-Frame-Options': 'X-Frame-Options header',
    'X-XSS-Protection': 'X-XSS-Protection header',
}

security_info = {}

# print(security_info)

for header, description in security_headers.items():
    if header in response.headers:
        header_info = f'{header}: {response.headers[header]} ({description})'
        # print(header_info)
        security_info['Security_Headers'] = header_info
        result_array.append(security_info)
        # result_array.append(header_info)
    else:
        not_found_info = f'{header} not found'
        security_info['Security_Headers'] = not_found_info

        result_array.append(security_info)
        # print(not_found_info)
        # result_array.append(not_found_info)

# Write the array to a text file
file_path = 'returnDraft.txt'
end_data = json.dumps(result_array)
with open(file_path, 'w') as file:
    file.write( end_data )



# import requests
# from bs4 import BeautifulSoup

# # Define the URL to inspect
# url = f'https://{domainTwo}'
# print(url)

# # Send an HTTP GET request to the URL
# response = requests.get(url)

# # Check for redirects
# if response.history:end_dataend_dataend_dataend_dataend_dataend_data
#     print('Redirects:')
#     with open(file_path, 'a') as file:
#         file.write("Redirects:")
#     for resp in response.history:
#         print(f'{resp.status_code} - {resp.url}')
#         with open(file_path, 'a') as file:
#             file.write(f'{resp.status_code} - {resp.url}\n')
#     print(f'Final URL: {response.url}')
#     with open(file_path, 'a') as file:
#         file.write(f'Final URL: {response.url}\n')

# # Get the final HTTP status code
# print(f'HTTP Status Code: {response.status_code}')
# with open(file_path, 'a') as file:
#     file.write(f'HTTP Status Code: {response.status_code}\n')

# # Get and print cookies
# cookies = response.cookies
# print('Cookies:')
# with open(file_path, 'a') as file:
#     file.write('Cookies:')
# for cookie in cookies:
#     print(f'{cookie.name} - {cookie.value}')
#     with open(file_path, 'a') as file:
#         file.write(f'{cookie.name} - {cookie.value}\n')

# # Parse the HTML content to check for console messages and security headers
# if response.headers.get('content-type', '').startswith('text/html'):
#     soup = BeautifulSoup(response.text, 'html.parser')
#     scripts = soup.find_all('script')

#     # Extract console messages
#     console_messages = []
#     for script in scripts:
#         if script.string:
#             console_messages.append(script.string)

#     print('Console Messages:')
#     with open(file_path, 'a') as file:
#         file.write('Console Messages:')
#     for message in console_messages:
#         print(message)
#         with open(file_path, 'a') as file:
#             file.write(f'{message}\n')
        

# # Check security headers
# security_headers = {
#     'Content-Security-Policy': 'Content Security Policy header',
#     'Strict-Transport-Security': 'HTTP Strict Transport Security header',
#     'X-Content-Type-Options': 'X-Content-Type-Options header',
#     'X-Frame-Options': 'X-Frame-Options header',
#     'X-XSS-Protection': 'X-XSS-Protection header',
# }

# print('Security Headers:')
# with open(file_path, 'a') as file:
#     file.write('Security Headers:')
# for header, description in security_headers.items():
#     if header in response.headers:
#         print(f'{header}: {response.headers[header]} ({description})')
#         with open(file_path, 'a') as file:
#             file.write(f'{header}: {response.headers[header]} ({description})\n')
#     else:
#         print(f'{header} not found')
#         with open(file_path, 'a') as file:
#             file.write(f'{header} not found\n')      