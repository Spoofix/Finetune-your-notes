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

import distance
import re
from sklearn.feature_extraction.text import CountVectorizer
from sklearn.metrics.pairwise import cosine_similarity

def remove_url_prefix(text):
    # Remove 'http://' or 'https://' from the text
    return re.sub(r'^https?://', '', text)

def preprocess_text(text):
    # Convert text to lowercase and split into individual words (alphanumeric characters)
    words = set(re.findall(r'\w+', text.lower()))
    return ' '.join(words)

def jaccard_similarity(word1, word2):
    set1 = set(word1.split())
    set2 = set(word2.split())
    intersection = len(set1.intersection(set2))
    union = len(set1.union(set2))
    similarity = intersection / union
    return similarity

def levenshtein_similarity(word1, word2):
    lev_distance = distance.levenshtein(word1, word2)
    max_length = max(len(word1), len(word2))
    similarity = 1 - lev_distance / max_length
    return similarity
def calculate_cosine_similarity(text1, text2):
    vectorizer = CountVectorizer().fit_transform([text1, text2])
    vectors = vectorizer.toarray()
    similarity = cosine_similarity(vectors)
    return similarity[0, 1]


def custom_similarity(word1, word2):
    if word1 and word2:
        similarity = 0.0
        if word1[0] == word2[0]:
            similarity += 0.5
        if word1[-1] == word2[-1]:
            similarity += 0.5
        return similarity
    else:
        return 0.0
    
def calculate_text_similarity(text1, text2):
    # Remove 'http://' or 'https://' from the texts
    text1 = remove_url_prefix(text1)
    text2 = remove_url_prefix(text2)

    # Preprocess the texts for Jaccard and Levenshtein Distance
    words_set1 = preprocess_text(text1)
    words_set2 = preprocess_text(text2)

    if not words_set1 or not words_set2:
        # Handle the case where one or both texts are empty or contain no alphanumeric characters
        return 0.0

    # Calculate individual similarity scores
    jaccard_sim = jaccard_similarity(words_set1, words_set2)
    cosine_sim = calculate_cosine_similarity(text1, text2)
    levenshtein_sim = levenshtein_similarity(text1, text2)
    custom_sim = custom_similarity(text1, text2)

    # Define the weights for each similarity metric
    jaccard_weight = 0.3
    cosine_weight = 0.3
    levenshtein_weight = 0.2
    custom_weight = 0.2

    # Calculate the overall similarity score as a weighted average
    overall_similarity = (jaccard_weight * jaccard_sim +
                          cosine_weight * cosine_sim +
                          levenshtein_weight * levenshtein_sim +
                          custom_weight * custom_sim)

    return overall_similarity

# Example usage:
text1 = f"https://{domainOne}"
text2 = f"https://{domainTwo}"

similarity_score = calculate_text_similarity(text1, text2)
print(f"{similarity_score:.2f}")




