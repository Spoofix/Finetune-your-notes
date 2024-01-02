# # pip install selenium requests_cache beautifulsoup4 nltk scikit-learn

with open('keywords.txt', 'r') as file:
    contents = file.read()

domain_string = contents


domain_list = domain_string.split(',')

domainOne = domain_list[0].strip() 
domainTwo = domain_list[1].strip()



import os
from selenium import webdriver
from selenium.webdriver.chrome.service import Service as ChromeService
from selenium.webdriver.chrome.options import Options as ChromeOptions
from selenium.webdriver.common.by import By
from urllib.parse import urlparse



def take_screenshot(url):
    try:
        chrome_service = ChromeService(executable_path='/usr/bin/chromedriver')  # path to chromedriver
        chrome_options = ChromeOptions()
        chrome_options.add_argument('--headless')

        # Set window size to match your laptop screen resolution
        chrome_options.add_argument('--window-size=1366,768')
        driver = webdriver.Chrome(service=chrome_service, options=chrome_options)

        driver.get(url)
        #

        # Create the 'screenshots' subdirectory if it doesn't exist
        if not os.path.exists("../public/assets/screenshots"):
            os.makedirs("../public/assets/screenshots")

        domain_name = urlparse(url).netloc
        screenshot_file = os.path.join("../public/assets/screenshots", f"{domain_name}.png")
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
