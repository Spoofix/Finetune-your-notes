file_path = 'results.txt'
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
#run as sudo in the terminal
import re
import requests
import socket
import subprocess
import ssl
import OpenSSL.crypto
import matplotlib.pyplot as plt
import os
import pycountry
import cartopy.crs as ccrs
import cartopy.feature as cfeature
import json

results_values = []

# Function to get the IP address of a domain
def get_ip_address(domain):
    try:
        ip = socket.gethostbyname(domain)
        return ip
    except socket.gaierror:
        return None

#Function to download flag
def download_flag(country_code_in_caps):
    # Define the country code
    country_code = country_code_in_caps.lower()  # Replace with the desired country code (e.g., "us", "ca", "gb", etc.)

    # Construct the flag image URL
    flag_url = f"https://cdn.ip2location.com/assets/img/flags/{country_code}.png"

    # Define the directory to save the flag images
    # flag_dir = "flags"
    flag_dir = os.path.abspath('../public/assets/flags')

    # Create the "frags" directory if it doesn't exist
    if not os.path.exists(flag_dir):
        os.makedirs(flag_dir)

    # Define the filename for saving the flag image
    filename = f"{country_code}.png"

    # Download and save the flag image
    response = requests.get(flag_url)
    if response.status_code == 200:
        with open(os.path.join(flag_dir, filename), "wb") as f:
            f.write(response.content)
        # print(f"Success in downloading frag: {country_code}")
    else:
        print(f"Failed to download flag for {country_code}.")

# Function to get server's operating system using nmap
def get_server_os(domain):
    try:
        result = subprocess.check_output(["nmap", "-O", domain]).decode("utf-8")
        # Parse the result to extract the operating system information
        os_info = result.split("OS details: ")[-1].split("\n")[0].strip()
        return os_info
    except subprocess.CalledProcessError as e:
        print(f"Error running nmap: {e}")
        return "Unknown"
    except Exception as e:
        print(f"Error: {e}")
        return "Unknown"

# Function to get basic SSL certificate details
def get_ssl_certificate(domain):
    try:
        # Create a default SSL context
        context = ssl.create_default_context()
        
        # Establish a secure connection to the domain
        with context.wrap_socket(socket.socket(), server_hostname=domain) as ssock:
            ssock.connect((domain, 443))
            
            # Retrieve the SSL certificate presented by the server
            cert = ssock.getpeercert(binary_form=True)
            
            # Load the SSL certificate into an OpenSSL.crypto.X509 object
            x509 = OpenSSL.crypto.load_certificate(OpenSSL.crypto.FILETYPE_ASN1, cert)
            
            # Extract subject and issuer information
            subject = dict((name.decode(), value.decode()) for name, value in x509.get_subject().get_components())
            issuer = dict((name.decode(), value.decode()) for name, value in x509.get_issuer().get_components())
            
            # Construct the data dictionary
            data = {
                'Subject': subject,
                'Issuer': issuer
            }
            # Serialize the data dictionary to a JSON string
            # data_str = json.dumps(data)
            
            return data
    except Exception as e:
        print(f"Error getting SSL certificate details: {e}")
        return None

# Replace with the domain name you want to look up
domain_name = domainTwo

# Get the IP address of the domain
ip_address = get_ip_address(domain_name)
#get isp with whois "ip address"
def get_netname_from_whois(ip_address):
    try:
        # Run the whois command and capture the output
        whois_output = subprocess.check_output(["whois", ip_address]).decode("utf-8")

        # Define the regular expression pattern to search for the netname key
        # pattern = re.compile(r"netname\s*:\s*(.*?)$", re.IGNORECASE | re.MULTILINE)
        pattern = re.compile(r"Organization\s*:\s*(.*?)$", re.IGNORECASE | re.MULTILINE)

        # Search for the netname key in the whois output
        match = re.search(pattern, whois_output)

        if match:
            # Return the netname value
            return match.group(1).strip()
        else:
            return None

    except subprocess.CalledProcessError as e:
        print(f"Error running whois command: {e}")
        return None
    except Exception as e:
        print(f"Error: {e}")
        return None

def get_email_from_whois(ip_address):
    try:
        # Run the whois command and capture the output
        whois_output = subprocess.check_output(["whois", ip_address]).decode("utf-8")

        # Define the regular expression pattern to search for the netname key
        # pattern = re.compile(r"netname\s*:\s*(.*?)$", re.IGNORECASE | re.MULTILINE)
        pattern = re.compile(r"email\s*:\s*(.*?)$", re.IGNORECASE | re.MULTILINE)

        # Search for the netname key in the whois output
        match = re.search(pattern, whois_output)

        if match:
            # Return the netname value
            return match.group(1).strip()
        else:
            return None

    except subprocess.CalledProcessError as e:
        print(f"Error running whois command: {e}")
        return None
    except Exception as e:
        print(f"Error: {e}")
        return None
    
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
    
# Function to get geolocation information for an IP address using ipinfo.io
def get_geolocation_info(ip):
    try:
        url = f"https://ipinfo.io/{ip}/json"
        response = requests.get(url)
        data = response.json()
        download_flag(data.get("country"))
        return {
            "IP Address": data.get("ip"),
            "City": data.get("city"),
            "Region": data.get("region"),
            "Country": get_country_name(data.get("country")),
            "Latitude": data.get("loc").split(",")[0],
            "Longitude": data.get("loc").split(",")[1],
            "Organization": data.get("org"),
            # "ISP": data.get("org").split()[-1] if data.get("org") else None,
            "ISP": get_netname_from_whois(ip),
            "ISP_Abuse": get_email_from_whois(ip),
        }
    except Exception as e:
        print(f"Error getting geolocation information: {e}")
        return {}

# Function to get the web server's OS based on HTTP headers
def get_server_os(domain):
    try:
        url = f"http://{domain}"
        response = requests.get(url, timeout=5)
        server_header = response.headers.get("Server")

        if server_header:
            return server_header
        else:
            return "none"
    except requests.exceptions.RequestException as e:
        return str(e)


# domain_name = "worldcoin.org"

server_os = get_server_os(domain_name)
server_os_data = {}  
server_os_data['Web_Server'] = server_os
results_values.append(server_os_data)
# print(f"Web Server for {domain_name}: {server_os}")


geolocation_info = get_geolocation_info(ip_address)

if geolocation_info:
    # print("Geolocation Information:")
    geolocationInfo = {}
    # for key, value in geolocation_info.items():
        # print(f"{key}: {value}")
    geolocationInfo['Geolocation_Information'] = geolocation_info
    results_values.append(geolocationInfo)
else:
    # print(f"Cannot retrieve geolocation information for {ip_address}.")
    geolocationInfo = {}
    geolocationInfo['Geolocation_Information'] = 'none'
    results_values.append(geolocationInfo)

if ip_address:
    # print(f"IP Address: {ip_address}")
    ipAddress = {}
    ipAddress['IP_Address'] = ip_address
    results_values.append(ipAddress)

    # Get server's operating system
    server_os = get_server_os(domain_name)
    # print(f"Server_Operating_System: {server_os}")
    
    # Get basic SSL certificate details
    ssl_certificate = get_ssl_certificate(domain_name)
    


    sslInfo ={}
    sslInfo['SSL-Certificate_Details'] = ssl_certificate
    results_values.append(sslInfo)

else:
    # print(f"Cannot resolve the IP address for {domain_name}.")
    sslInfo ={}
    sslInfo['SSL_Certificate_Details'] = 'none'
    results_values.append(sslInfo)

Data = []

with open('returnDraft.txt', 'r') as file:
     datas = file.read()
     python_list = json.loads(datas)
     for data in python_list:
       Data.append(data)
     for data in results_values:
        Data.append(data)

with open(file_path, 'w') as file:
    json_data = json.dumps(Data, indent=2)
    file.write("%s\n" % json_data)