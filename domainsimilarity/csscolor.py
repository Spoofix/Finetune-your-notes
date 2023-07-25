

with open('keywords.txt', 'r') as file:
    # Read the contents of the file
    contents = file.read()

    # Assuming you have a string containing the two domains separated by a comma
domain_string = contents

# Split the string at the comma to get the individual domains
domain_list = domain_string.split(',')

# Assign the domains to different variables
domainOne = domain_list[0].strip()  # Use strip() to remove any leading or trailing whitespaces
domainTwo = domain_list[1].strip()

import requests
from bs4 import BeautifulSoup
from selenium import webdriver
from selenium.webdriver.chrome.service import Service as ChromeService
from selenium.webdriver.chrome.options import Options as ChromeOptions
from selenium.webdriver.common.by import By

try:
    import cssutils
except ImportError:
    print("Error: cssutils library not found. Please install it using 'pip install cssutils'")
    exit(1)

def fetch_image_urls(url):
    response = requests.get(url)
    if response.status_code == 200:
        soup = BeautifulSoup(response.content, 'html.parser')
        image_urls = [img['src'] for img in soup.find_all('img', src=True)]
        return image_urls
    else:
        print(f"Error fetching content from {url}")
        return []

def fetch_web_page_content(url):
    response = requests.get(url)
    if response.status_code == 200:
        return response.text
    else:
        print(f"Error fetching content from {url}")
        return ""

def fetch_css_colors(url):
    chrome_service = ChromeService(executable_path='path_to_chromedriver')  # Replace with the path to chromedriver
    chrome_options = ChromeOptions()
    chrome_options.add_argument('--headless')
    chrome_driver = webdriver.Chrome(service=chrome_service, options=chrome_options)
    
    try:
        chrome_driver.get(url)
        style_elements = chrome_driver.find_elements(By.TAG_NAME, 'style')
        css_content = ""
        for style in style_elements:
            css_content += style.get_attribute('innerText')
        
        css_parser = cssutils.CSSParser()
        style_sheet = css_parser.parseString(css_content)
        color_set = set()
        for rule in style_sheet:
            if rule.type == rule.STYLE_RULE:
                for prop in rule.style:
                    if prop.name.endswith("color"):
                        color_set.add(prop.value)
        return list(color_set)
    except Exception as e:
        print(f"Error fetching CSS colors: {e}")
        return []
    finally:
        chrome_driver.quit()

def calculate_css_color_similarity(css_colors1, css_colors2):
    if not css_colors1 or not css_colors2:
        return 0.0

    css_color_similarity = len(set(css_colors1) & set(css_colors2)) / max(len(css_colors1), len(css_colors2))
    return css_color_similarity

def main():
    web_page_url1 = f"https://{domainOne}"  # Replace with the URL of the first web page
    web_page_url2 = f"https://{domainTwo}"  # Replace with the URL of the second web page

    # Fetch CSS colors from the web pages
    try:
        css_colors1 = fetch_css_colors(web_page_url1)
        css_colors2 = fetch_css_colors(web_page_url2)
    except Exception as e:
        1+1
    try:
        # Perform CSS color similarity comparison
        css_color_similarity = calculate_css_color_similarity(css_colors1, css_colors2)
        print(f"crating_{css_color_similarity:.2f}")
    except Exception as e:
    #    error =f"Error: {e}"
       print('none')

if __name__ == "__main__":
    main()

