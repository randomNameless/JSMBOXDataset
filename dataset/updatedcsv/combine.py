import pandas as pd
import os

def combine_csv_files(file_list, output_file="mac_combined.csv", encoding="utf-8"):
    """
    Combines multiple CSV files into one, renames the 'malicious' column to 'Flag',
    and saves the resulting DataFrame.
    """
    combined_df = pd.DataFrame()
    for file in file_list:
        if not os.path.exists(file):
            print(f"Error: File '{file}' not found. Skipping.")
            continue
        try:
            temp_df = pd.read_csv(file, encoding=encoding)
            combined_df = pd.concat([combined_df, temp_df], ignore_index=True)
        except UnicodeDecodeError:
            print(f"Encoding error: Could not read file '{file}'. Trying 'latin1' encoding.")
            try:
                temp_df = pd.read_csv(file, encoding="latin1")
                combined_df = pd.concat([combined_df, temp_df], ignore_index=True)
            except Exception as e:
                print(f"Failed to read file '{file}' with 'latin1' encoding: {e}")
        except Exception as e:
            print(f"Error reading file '{file}': {e}")
    
    if combined_df.empty:
        raise ValueError("No valid data found. Combined dataset is empty.")
    
    print(f"Combined dataset has {combined_df.shape[0]} rows and {combined_df.shape[1]} columns.")
    
    # Rename the 'malicious' column to 'Flag' if it exists
    if 'malicious' in combined_df.columns:
        combined_df = combined_df.rename(columns={'malicious': 'Flag'})
        print("'malicious' column renamed to 'Flag'.")

    # Save the combined dataset to a CSV file
    try:
        combined_df.to_csv(output_file, index=False, encoding='utf-8')
        print(f"Combined dataset saved as: {output_file}")
    except Exception as e:
        print(f"Error saving combined file: {e}")
    
    return combined_df

# File list to combine
input_files = ["JSBenign.csv", "JSMalicious.csv", "WebassemblyBenign.csv", "WebassemblyMalicious.csv"]

# Combine the CSV files and save as mac_combined.csv
try:
    combined_df = combine_csv_files(input_files, output_file="mac_combined.csv")
except Exception as e:
    print(f"An error occurred during combination: {e}")

