import os
import subprocess
from bs4 import BeautifulSoup
import tempfile

# Folder paths
current_folder = os.getcwd()  # Current directory
output_folder = os.path.join(current_folder, 'wobfruscated')  # Output directory for modified files
config_path = '/home/selab/Desktop/wobfuscator_tool/Wobfuscator/build/config.json'  # Path to the config file for the tool
node_tool_path = '/home/selab/Desktop/wobfuscator_tool/Wobfuscator/build/index.js'  # Path to your Node tool

# Create the output folder if it doesn't exist
os.makedirs(output_folder, exist_ok=True)

# Function to log errors
error_log = os.path.join(output_folder, "error_log.txt")


def log_error(message):
    with open(error_log, "a") as log_file:
        log_file.write(message + "\n")


# Function to read file with fallback encodings
def read_file_with_encoding(file_path):
    encodings = ['utf-8', 'ISO-8859-1', 'latin1']
    for enc in encodings:
        try:
            with open(file_path, 'r', encoding=enc) as file:
                return file.read(), enc
        except UnicodeDecodeError:
            continue
    raise UnicodeDecodeError(f"Unable to decode the file {file_path} with the available encodings.")


# Check Node.js availability
try:
    subprocess.run(['node', '--version'], check=True, stdout=subprocess.PIPE, stderr=subprocess.PIPE)
except FileNotFoundError:
    print("Error: Node.js is not installed. Please install it and try again.")
    exit(1)

# Iterate over all HTML files in the current folder
for filename in os.listdir(current_folder):
    if filename.endswith('.html'):
        html_file_path = os.path.join(current_folder, filename)

        # Try to read the HTML file
        try:
            html_content, encoding_used = read_file_with_encoding(html_file_path)
            print(f"Successfully read {filename} using {encoding_used} encoding.")
        except UnicodeDecodeError as e:
            log_error(f"Error reading {filename}: {e}")
            continue

        # Parse the HTML
        soup = BeautifulSoup(html_content, 'html.parser')

        # Find the <script> tag with JavaScript
        script_tag = soup.find('script', string=True)
        if not script_tag or not script_tag.string.strip():
            print(f"No JavaScript code found in {filename}. Skipping.")
            continue

        js_code = script_tag.string.strip()

        # Create temporary files for obfuscation
        with tempfile.NamedTemporaryFile(delete=False, suffix='.js') as input_js_file:
            input_js_path = input_js_file.name
            input_js_file.write(js_code.encode('utf-8'))

        output_js_path = input_js_path.replace('input', 'output')

        # Run the Node.js obfuscation tool
        try:
            subprocess.run([
                'node', node_tool_path,
                '-f', input_js_path,
                '-o', output_js_path,
                '-c', config_path
            ], check=True)
        except subprocess.CalledProcessError as e:
            log_error(f"Error obfuscating JavaScript in {filename}: {e}")
            os.remove(input_js_path)
            continue

        # Replace the <script> content with obfuscated JavaScript
        try:
            with open(output_js_path, 'r', encoding='utf-8') as output_js_file:
                output_js_code = output_js_file.read().strip()

            script_tag.string.replace_with(output_js_code)

            # Save the modified HTML to the output folder
            modified_html_path = os.path.join(output_folder, filename)
            with open(modified_html_path, 'w', encoding='utf-8') as modified_html_file:
                modified_html_file.write(str(soup))

            print(f"Successfully processed {filename}. Saved to {modified_html_path}")

        except Exception as e:
            log_error(f"Error writing obfuscated JavaScript in {filename}: {e}")

        # Clean up temporary files
        finally:
            os.remove(input_js_path)
            if os.path.exists(output_js_path):
                os.remove(output_js_path)

print("All HTML files in the current folder have been processed.")

