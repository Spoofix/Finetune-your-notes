import subprocess
import re
import json

with open('keywords.txt', 'r') as file:
   contents = file.read()
   domain = contents.strip()

# Define the command to run
command = f'echo "http://{domain}/" | ../../../go/bin/wrlookup -apikey=AIzaSyDvh5aBEVjTlXul-jJT7w9XRYmYnRLJrp4'

# Run the command and capture its output
output = subprocess.run(command, shell=True, capture_output=True, text=True)
#print(output)

# Parse the output to find the line containing "Unsafe URL"
unsafe_url_line = None
for line in output.stdout.split('\n'):
    if "Unsafe URL" in line:
        # Extract the part after "Unsafe URL: "
        unsafe_url_line = line.split("Unsafe URL: ")[1].strip()
        break

# Print the result
if unsafe_url_line:
    # print(unsafe_url_line)
    # Prepare the result in a dictionary
    result = {}
    if unsafe_url_line:
        result["Unsafe URL"] = unsafe_url_line

    # Convert the result to JSON format
    json_output = json.dumps(result)
    print(json_output)
else:
    # print("Safe URL")
    safe = {}
    safe["Safe URL"] = ""

    json_output = json.dumps(safe)
    print(json_output)


    
# import subprocess
# import re

# unsafe_url_line = None

# def webrisk(domain):
#     global unsafe_url_line 
#     # Define the command to run
#     command = f'echo "http://{domain}/" | ../../../go/bin/wrlookup -apikey=AIzaSyDvh5aBEVjTlXul-jJT7w9XRYmYnRLJrp4'

#     # Run the command and capture its output
#     output = subprocess.run(command, shell=True, capture_output=True, text=True)
#     #print(output)

#     # Parse the output to find the line containing "Unsafe URL"
#     for line in output.stdout.split('\n'):
#         if "Unsafe URL" in line:
#             unsafe_url_line = line.strip()
#             break

# if __name__ == "__main__":
#     with open('keywords.txt', 'r') as file:
#         contents = file.read()
#     domain_string = contents.strip()
#     webrisk(domain_string)
#     # Print the result
#     if unsafe_url_line:
#         print(unsafe_url_line)
#     else:
#         print("Safe URL")
