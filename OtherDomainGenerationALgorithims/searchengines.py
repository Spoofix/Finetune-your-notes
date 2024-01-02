#should know the domain and the spoofy to compare to then we will get the rating, will work on it later
import os

file_path = 'keywords.txt'

with open(file_path, 'r') as file:
    # Read the contents of the file
    contents = file.read()

    # Assuming you have a string containing the two domains separated by a comma
domain_string = contents

# Split the string at the comma to get the individual domains
# domain_list = domain_string.split(',')

# Assign the domains to different variables
# domainOne = domain_list[0].strip() 
domainkey = domain_string.split('.') # Use strip() to remove any leading or trailing whitespaces
domainKeyword = domainkey[0].strip() 

# domainTwo = domain_list[1].strip()


import requests
from bs4 import BeautifulSoup
from urllib.parse import urlparse, parse_qs, unquote

def scrape_search_results(keyword):
    # Function to scrape search engine results for the given keyword.

    search_engine_url = f"https://www.google.com/search?q={keyword}"
    headers = {'User-Agent': 'Mozilla/5.0'}
    response = requests.get(search_engine_url, headers=headers)

    if response.status_code == 200:
        soup = BeautifulSoup(response.content, 'html.parser')
        # Extract and process search results here, looking for domains and subdomains.
        # Note: Be cautious about scraping search engines and follow their terms of service.
        links = soup.find_all('a')
        domains = set()
        for link in links:
            href = link.get('href')
            if href and href.startswith('/url?q='):
                # Extract the encoded URL from the href.
                encoded_url = href[7:]
                # Decode the URL using urllib.parse.unquote.
                decoded_url = unquote(encoded_url)
                # Extract the domain from the decoded URL using urllib.parse.
                parsed_url = urlparse(decoded_url)
                domain = parsed_url.netloc
                if domain.startswith('www.'):
                    domain = domain[4:]
                domains.add(domain)
        return domains

def main():
    keyword = domainKeyword
    file_path = 'results.txt'

    # Open the file in write mode ('w' for write)
    with open(file_path, 'w') as file:
        # This truncates the file, removing existing content
        pass  # You can also write an empty string here if you want

    # Step 1: Scrape search engine results for domains and subdomains containing the keyword.
    search_results = scrape_search_results('site:"{}".*'.format(keyword))
    filtered_domains = [domain for domain in search_results if keyword in domain.lower()]

    if search_results:
        # print(f"Domains and subdomains containing '{keyword}':")
        for domain in filtered_domains:
            # Open the file in append mode ('a' for append)
            with open(file_path, 'a') as file:
                # Append content to the file
                file.write(f"{domain}\n")
            # print(domain)
    else:
        print(f"No domains or subdomains found containing '{keyword}'.")

main()
