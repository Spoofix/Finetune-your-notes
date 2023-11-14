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


import random
import time
import logging
import os  # Import the os module
from selenium import webdriver
from selenium.webdriver.chrome.options import Options
from selenium.common.exceptions import TimeoutException, NoSuchElementException
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from selenium.webdriver.common.by import By
import requests_cache
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.metrics.pairwise import cosine_similarity

# Enable caching for web requests to reduce repetitive requests
requests_cache.install_cache('web_cache', expire_after=3600)  # Cache expires after 1 hour

# Initialize logging
logging.basicConfig(filename='scraping.log', level=logging.INFO,
                    format='%(asctime)s - %(levelname)s - %(message)s', datefmt='%Y-%m-%d %H:%M:%S')

def get_content_with_selenium(domain, user_agents=None, page_load_timeout=20):
    if not user_agents:
        user_agents = [
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/92.0.4515.159 Safari/537.36',
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.164 Safari/537.36',
            # Add more User-Agent strings as needed
        ]
    try:
        options = Options()
        options.add_argument('--headless')  # Enable headless mode
        user_agent = random.choice(user_agents)
        options.add_argument(f'user-agent={user_agent}')
        
        # Specify the path to the Chrome WebDriver executable
        chromedriver_path = '/usr/bin/chromedriver'  # Replace with the actual path
        
        driver = webdriver.Chrome(executable_path=chromedriver_path, options=options)
        driver.set_page_load_timeout(page_load_timeout)  # Set a maximum page load timeout
        driver.get(domain)
        WebDriverWait(driver, 10).until(EC.presence_of_element_located((By.TAG_NAME, 'body')))
        content = driver.page_source
        driver.quit()
        return content
    except TimeoutException:
        logging.error(f"Timeout occurred while fetching content from {domain}")
        return None
    except NoSuchElementException:
        logging.error(f"Element not found while fetching content from {domain}")
        return None
    except Exception as e:
        logging.error(f"Error fetching content from {domain}: {e}")
        return None

def throttle_requests(delay):
    time.sleep(delay)

def preprocess_text(text):
    vectorizer = TfidfVectorizer(stop_words='english')
    tfidf_matrix = vectorizer.fit_transform([text])
    return tfidf_matrix

def calculate_cosine_similarity(tfidf_matrix1, tfidf_matrix2):
    similarity_matrix = cosine_similarity(tfidf_matrix1, tfidf_matrix2)
    return similarity_matrix[0][0]

def compare_domains(domain1, domain2, user_agents=None, page_load_timeout=20):
    content1 = get_content_with_selenium(domain1, user_agents, page_load_timeout)
    throttle_requests(2)
    content2 = get_content_with_selenium(domain2, user_agents, page_load_timeout)

    if content1 is not None and content2 is not None:
        tfidf_matrix1 = preprocess_text(content1)
        tfidf_matrix2 = preprocess_text(content2)
        similarity_score = calculate_cosine_similarity(tfidf_matrix1, tfidf_matrix2)
        return similarity_score
    else:
        return None

# Example usage:
with open('keywords.txt', 'r') as file:
    domain_string = file.read()
domain_list = domain_string.split(',')
domainOne = domain_list[0].strip()
domainTwo = domain_list[1].strip()

# domain1 = f'https://{domainOne}'
# domain2 = f'https://{domainTwo}'
domain1 = 'https://google.com'
domain2 = 'https://google.com'
user_agents_list = [
    'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/92.0.4515.159 Safari/537.36',
    'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.164 Safari/537.36',
    # Add more User-Agent strings as needed
]
page_timeout = 30

similarity_score = compare_domains(domain1, domain2, user_agents_list, page_timeout)

if similarity_score is not None:
    rating = similarity_score * 100
    print(f"{rating:.2f}")
else:
    print("none")




# from selenium import webdriver
# from selenium.webdriver.chrome.options import Options
# from selenium.common.exceptions import TimeoutException, NoSuchElementException
# from selenium.webdriver.support.ui import WebDriverWait
# from selenium.webdriver.support import expected_conditions as EC
# from selenium.webdriver.common.by import By
# import requests_cache
# import random
# import time
# import logging
# from sklearn.feature_extraction.text import TfidfVectorizer
# from sklearn.metrics.pairwise import cosine_similarity
# import numpy as np
# from sklearn.feature_extraction.text import TfidfVectorizer
# from sklearn.metrics.pairwise import cosine_similarity

# # Enable caching for web requests to reduce repetitive requests
# requests_cache.install_cache('web_cache', expire_after=3600)  # Cache expires after 1 hour

# # Initialize logging
# logging.basicConfig(filename='scraping.log', level=logging.INFO,
#                     format='%(asctime)s - %(levelname)s - %(message)s', datefmt='%Y-%m-%d %H:%M:%S')

# def get_content_with_selenium(domain, user_agents=None, page_load_timeout=20):
#     if not user_agents:
#         user_agents = [
#             'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
#             'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/92.0.4515.159 Safari/537.36',
#             'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.164 Safari/537.36',
#             # Add more User-Agent strings as needed
#         ]
#     try:
#         options = Options()
#         options.add_argument('--headless')  # Enable headless mode
#         user_agent = random.choice(user_agents)
#         options.add_argument(f'user-agent={user_agent}')
#         driver = webdriver.Chrome(options=options)
#         driver.set_page_load_timeout(page_load_timeout)  # Set a maximum page load timeout
#         driver.get(domain)
#         # Use explicit wait to wait for specific elements to appear
#         # For example, wait for the body element to ensure the page is loaded
#         WebDriverWait(driver, 10).until(EC.presence_of_element_located((By.TAG_NAME, 'body')))
#         content = driver.page_source
#         driver.quit()
#         return content
#     except TimeoutException:
#         logging.error(f"Timeout occurred while fetching content from {domain}")
#         return None
#     except NoSuchElementException:
#         logging.error(f"Element not found while fetching content from {domain}")
#         return None
#     except Exception as e:
#         logging.error(f"Error fetching content from {domain}: {e}")
#         return None

# def throttle_requests(delay):
#     time.sleep(delay)

# def preprocess_text(text):
#     # Basic text preprocessing using scikit-learn's TfidfVectorizer
#     vectorizer = TfidfVectorizer(stop_words='english')
#     tfidf_matrix = vectorizer.fit_transform([text])
#     return tfidf_matrix

# def calculate_cosine_similarity(text1, text2):
#     # Get the TF-IDF matrix for both content samples using the same vectorizer
#     vectorizer = TfidfVectorizer(stop_words='english')
#     tfidf_matrix1 = vectorizer.fit_transform([text1])
#     tfidf_matrix2 = vectorizer.transform([text2])  # Note: Using `transform` instead of `fit_transform`

#     # Calculate cosine similarity between the two TF-IDF matrices
#     similarity_matrix = cosine_similarity(tfidf_matrix1, tfidf_matrix2)
#     return similarity_matrix[0][0]  # The similarity score is at position (0, 0) in the similarity matrix

# # ... (remaining code)
# def compare_domains(domain1, domain2, user_agents=None, page_load_timeout=20):
#     content1 = get_content_with_selenium(domain1, user_agents, page_load_timeout)
#     throttle_requests(2)  # Add a 2-second delay between domain comparisons to avoid overloading servers
#     content2 = get_content_with_selenium(domain2, user_agents, page_load_timeout)

#     if content1 is not None and content2 is not None:
#         similarity_score = calculate_cosine_similarity(content1, content2)
#         return similarity_score
#     else:
#         return None

# # Example usage:
# domain1 = f'https://{domainOne}'  # Replace with the URL of a website to compare
# domain2 = f'https://{domainTwo}'  # Replace with the URL of another website to compare

# # Customize user_agents and page_load_timeout as needed for different scenarios
# user_agents_list = [
#     'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/92.0.4515.159 Safari/537.36',
#     'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.164 Safari/537.36',
#     # Add more User-Agent strings as needed
# ]
# page_timeout = 30  # Set the maximum page load timeout in seconds

# similarity_score = compare_domains(domain1, domain2, user_agents_list, page_timeout)

# if similarity_score is not None:
#     # print(f"The similarity score between {domain1} and {domain2} is: {similarity_score:.2f}")   
#     # print(f"{similarity_score:.2f}")       
#     rating = similarity_score / 1 * 100
#     print(f"{rating:.2f}") 
# else:
#     # print("Error occurred during domain comparison.")
#       print("none")