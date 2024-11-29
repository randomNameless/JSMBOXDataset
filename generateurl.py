import os

# Base URL for the GitHub Pages site
base_url = 'https://randomnameless.github.io/JSMBOXDataset'

# Directory to start reading from (current directory)
start_directory = os.getcwd()

# List to store generated URLs
url_list = []

# Walk through the directory structure
for root, dirs, files in os.walk(start_directory):
    for file in files:
        if file.endswith('.html'):
            # Get the relative path from the start directory to the HTML file
            relative_path = os.path.relpath(os.path.join(root, file), start_directory)
            # Replace backslashes with forward slashes if running on Windows
            url_path = relative_path.replace(os.sep, '/')
            # Construct the full URL
            full_url = f"{base_url}/{url_path}"
            # Append to the list
            url_list.append(full_url)

# Save the URLs to a text file
output_file = 'url_list.txt'
with open(output_file, 'w') as f:
    for url in url_list:
        f.write(url + '\n')

print(f"URLs have been generated and saved to {output_file}")

