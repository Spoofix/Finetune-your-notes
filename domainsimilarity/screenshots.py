# pip install selenium requests_cache beautifulsoup4 nltk scikit-learn
import os
from selenium import webdriver
from selenium.webdriver.chrome.service import Service as ChromeService
from selenium.webdriver.chrome.options import Options as ChromeOptions
from urllib.parse import urlparse
from datetime import datetime

def take_screenshot(url):
    try:
        chrome_service = ChromeService(executable_path='/usr/bin/chromedriver')  # path to chromedriver
        chrome_options = ChromeOptions()
        chrome_options.add_argument('--headless')

        # Set window size to match your laptop screen resolution
        chrome_options.add_argument('--window-size=1366,768')
        driver = webdriver.Chrome(service=chrome_service, options=chrome_options)

        driver.get(url)

        # Create the 'screenshots' subdirectory if it doesn't exist
        if not os.path.exists("../public/assets/screenshots"):
            os.makedirs("../public/assets/screenshots")

        domain_name = urlparse(url).netloc
        # Create subfolder of the domain name if it does not exist
        domain_folder = os.path.join("../public/assets/screenshots", domain_name)
        if not os.path.exists(domain_folder):
            os.makedirs(domain_folder)
        
        timestamp = datetime.now().strftime("%Y%m%d%H%M%S")
        screenshot_file = os.path.join(domain_folder, f"{domain_name}_{timestamp}.png")
        # Append a timestamp before png extension to give the file a unique identifier
        driver.save_screenshot(screenshot_file)
        print(f"Screenshot saved as: {screenshot_file}")

    except Exception as e:
        print(f"Error occurred: {e}")
    finally:
        driver.quit()

if __name__ == "__main__":
    with open('keywords.txt', 'r') as file:
        contents = file.read()

    domain_string = contents.strip()
    domain_list = domain_string.split(',')

    for domain in domain_list:
        web_page_url = f'https://{domain.strip()}'
        try:
            take_screenshot(web_page_url)
        except Exception as e:
            print(f"Error taking screenshot for {web_page_url}: {e}")
