

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
import re
from bs4 import BeautifulSoup
from selenium import webdriver
from selenium.webdriver.chrome.service import Service as ChromeService
from selenium.webdriver.chrome.options import Options as ChromeOptions
from selenium.webdriver.common.by import By


# ... (Previous imports and code)

def extract_css_colors(css_content):
    color_regex = r'#(?:[0-9a-fA-F]{3}){1,2}|(?:rgb|hsl)a?\([^)]*\)'
    return re.findall(color_regex, css_content)

def fetch_css_colors(url):
    chrome_service = ChromeService(executable_path='/usr/bin/chromedriver')  # Replace with the path to chromedriver
    chrome_options = ChromeOptions()
    chrome_options.add_argument('--headless')
    chrome_driver = webdriver.Chrome(service=chrome_service, options=chrome_options)
    
    try:
        chrome_driver.get(url)
        style_elements = chrome_driver.find_elements(By.TAG_NAME, 'style')
        css_content = ""
        for style in style_elements:
            css_content += style.get_attribute('innerText')
        
        css_colors = extract_css_colors(css_content)
        return css_colors
    except Exception as e:
        # print(f"Error fetching CSS colors: {e}")
        return []
    finally:
        chrome_driver.quit()

def calculate_css_color_similarity(css_colors1, css_colors2):
    if not css_colors1 or not css_colors2:
        return 0.0

    css_colors1_set = set(css_colors1)
    css_colors2_set = set(css_colors2)

    # Find the common colors in both sets
    common_colors = css_colors1_set.intersection(css_colors2_set)

    css_color_similarity = len(common_colors) / max(len(css_colors1_set), len(css_colors2_set))
    return css_color_similarity

def main():
    web_page_url1 = f"https://{domainOne}"  # Replace with the URL of the first web page
    web_page_url2 = f"https://{domainTwo}"  # Replace with the URL of the second web page
    # web_page_url1 = "https://google.com"  
    # web_page_url2 = "https://youtube.com" 

    try:
        # Fetch CSS colors from the web pages
        css_colors1 = fetch_css_colors(web_page_url1)
        css_colors2 = fetch_css_colors(web_page_url2)

        # Perform CSS color similarity comparison
        css_color_similarity = calculate_css_color_similarity(css_colors1, css_colors2)

        if {css_color_similarity}:
            # print(f"{css_color_similarity:.2f}")
            rating = css_color_similarity / 1 * 100
            print(f"{rating:.2f}")
        else:
            print("none")
    except Exception as e:
        # Log the error message for debugging purposes
        # print(f"Error: {e}")
        print('none')

if __name__ == "__main__":
    main()
