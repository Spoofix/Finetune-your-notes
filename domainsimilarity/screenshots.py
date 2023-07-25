# # pip install selenium requests_cache beautifulsoup4 nltk scikit-learn

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



import os
from selenium import webdriver
from selenium.webdriver.chrome.service import Service as ChromeService
from selenium.webdriver.chrome.options import Options as ChromeOptions
from selenium.webdriver.common.by import By
from urllib.parse import urlparse

# ... (other code remains the same)

def take_screenshot(url):
    try:
        chrome_service = ChromeService(executable_path='path_to_chromedriver')  # Replace with the path to chromedriver
        chrome_options = ChromeOptions()
        chrome_options.add_argument('--headless')
        driver = webdriver.Chrome(service=chrome_service, options=chrome_options)

        driver.get(url)
        # Rest of your code for interacting with the web page, if needed.

        # Create the 'screenshots' subdirectory if it doesn't exist
        if not os.path.exists("screenshots"):
            os.makedirs("screenshots")

        domain_name = urlparse(url).netloc
        screenshot_file = os.path.join("screenshots", f"{domain_name}.png")
        driver.save_screenshot(screenshot_file)
        print(f"Screenshot saved as: {screenshot_file}")

    except Exception as e:
        print(f"Error occurred: {e}")
    finally:
        driver.quit()

if __name__ == "__main__":
    web_page_url1 =  f'https://{domainOne}' # Replace with the URL of the first web page
    web_page_url2 =  f'https://{domainTwo}'    # Replace with the URL of the second web page
try:
    take_screenshot(web_page_url1)
    take_screenshot(web_page_url2)
except:
    1+1
