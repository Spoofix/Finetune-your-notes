#should know the domain and the spoofy to compare to then we will get the rating, will work on it later
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

import cv2
from PIL import Image
from skimage.metrics import structural_similarity as ssim
import imagehash
import os

def image_similarity(image_path1, image_path2):
    # Open and convert images to grayscale for similarity comparison
    img1 = cv2.imread(image_path1, cv2.IMREAD_GRAYSCALE)
    img2 = cv2.imread(image_path2, cv2.IMREAD_GRAYSCALE)

    # Calculate Structural Similarity Index (SSIM)
    ssim_value, _ = ssim(img1, img2, full=True)

    # Calculate Hamming distance between image hashes
    hash1 = imagehash.average_hash(Image.open(image_path1))
    hash2 = imagehash.average_hash(Image.open(image_path2))
    hamming_distance = hash1 - hash2

    # Calculate the similarity rating out of 10
    similarity_rating = 10 * (1 - hamming_distance / len(hash1.hash) ** 2)

    return similarity_rating


# Function to get the last modified file in a folder
def get_last_modified_file(folder_path):
    files = os.listdir(folder_path)
    files = [f for f in files if os.path.isfile(os.path.join(folder_path, f))]
    file_paths = [os.path.join(folder_path, f) for f in files]
    return max(file_paths, key=os.path.getmtime)

# Directory containing the screenshot files
screenshot_folder_path_domainOne = f"../public/assets/screenshots/{domainOne}"
screenshot_folder_path_domainTwo = f"../public/assets/screenshots/{domainTwo}"

# Get the last modified screenshot file for domainOne and domainTwo
latest_screenshot_domainOne = get_last_modified_file(screenshot_folder_path_domainOne)
latest_screenshot_domainTwo = get_last_modified_file(screenshot_folder_path_domainTwo)

# Get the filenames from the paths
latest_domainOne_file = os.path.basename(latest_screenshot_domainOne)
latest_domainTwo_file = os.path.basename(latest_screenshot_domainTwo)

# Construct the image paths
image1_path = f"../public/assets/screenshots/{domainOne}/{latest_domainOne_file}"  # First image
image2_path = f"../public/assets/screenshots/{domainTwo}/{latest_domainTwo_file}"  # Second image

average_rating  = image_similarity(image1_path, image2_path)
# print(f"{average_rating:.2f}")
rating = (average_rating / 10) * 100
print(f"{rating:.2f}")


