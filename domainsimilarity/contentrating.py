# pip install requests beautifulsoup4 nltk scikit-learn requests-cache


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
from nltk.corpus import stopwords
from nltk.tokenize import word_tokenize
from nltk.stem import PorterStemmer
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.metrics.pairwise import cosine_similarity
import requests_cache

# Enable caching for web requests to reduce repetitive requests
requests_cache.install_cache('web_cache', expire_after=3600)  # Cache expires after 1 hour

def get_content(domain):
    try:
        response = requests.get(domain, timeout=10)  # Increase timeout value if needed
        response.raise_for_status()  # Raise an exception for non-2xx HTTP status codes
        soup = BeautifulSoup(response.content, 'html.parser')
        content = soup.get_text()
        return content
    except requests.exceptions.RequestException as e:
        print(f"Error fetching content from {domain}: {e}")
        return None

def preprocess_text(text):
    # Tokenize the text
    words = word_tokenize(text.lower())

    # Remove stopwords
    stop_words = set(stopwords.words('english'))
    filtered_words = [word for word in words if word not in stop_words]

    # Stem the words (reducing words to their base form)
    stemmer = PorterStemmer()
    stemmed_words = [stemmer.stem(word) for word in filtered_words]

    # Join the words back into a single string
    preprocessed_text = ' '.join(stemmed_words)
    return preprocessed_text

def calculate_cosine_similarity(text1, text2):
    vectorizer = TfidfVectorizer()
    tfidf_matrix = vectorizer.fit_transform([text1, text2])
    similarity_matrix = cosine_similarity(tfidf_matrix)
    similarity_score = similarity_matrix[0][1]
    return similarity_score

def compare_domains(domain1, domain2):
    content1 = get_content(domain1)
    content2 = get_content(domain2)

    if content1 is not None and content2 is not None:
        similarity_score = calculate_cosine_similarity(content1, content2)
        return similarity_score
    else:
        return None

domain1 = f'https://{domainOne}'
domain2 = f'https://{domainTwo}'

similarity_score = compare_domains(domain1, domain2)

if similarity_score is not None:
    print(f"{similarity_score:.2f}")
else:
    print("none")
