import pandas as pd
import numpy as np
from sklearn.decomposition import PCA
from sklearn.preprocessing import StandardScaler

def combine_csv_files(file_list):
    combined_df = pd.DataFrame()
    for file in file_list:
        temp_df = pd.read_csv(file)
        combined_df = pd.concat([combined_df, temp_df], ignore_index=True)
    print(f"Combined dataset has {combined_df.shape[0]} rows and {combined_df.shape[1]} columns.")
    return combined_df

def perform_pca_and_save_report(df, target_column, n_components=2):
    try:
        # Drop the 'website' column if it exists
        if 'Website' in df.columns:
            df = df.drop(columns=['Website'])
            print("'website' column dropped.")
        
        # Separate features and the target column
        X = df.drop(columns=[target_column])
        y = df[target_column]

        # Standardize the features
        scaler = StandardScaler()
        X_scaled = scaler.fit_transform(X)

        # Apply PCA
        pca = PCA(n_components=n_components)
        pca.fit(X_scaled)

        # Get feature contributions (absolute values of component loadings)
        feature_contributions = np.abs(pca.components_)
        feature_names = X.columns

        # Create a DataFrame for feature importance
        importance_df = pd.DataFrame(feature_contributions.T, 
                                      columns=[f"PCA{i+1}" for i in range(n_components)], 
                                      index=feature_names)

        # Calculate the overall contribution of each feature
        importance_df['Total_Importance'] = importance_df.sum(axis=1)

        # Sort by total importance
        importance_df = importance_df.sort_values(by='Total_Importance', ascending=False)

        # Save the complete importance report to an Excel file
        output_file = f"Complete_Feature_Importance_{target_column}_combined.xlsx"
        with pd.ExcelWriter(output_file) as writer:
            importance_df.to_excel(writer, sheet_name=f'{target_column}_Importance')
        print(f"Complete feature importance report saved as: {output_file}")

        # Display the top 10 contributing features
        print(f"\nTop contributing features for '{target_column}':")
        print(importance_df.head(10))

    except Exception as e:
        print(f"An error occurred during PCA analysis: {e}")

# File list to combine
input_files = ["JSBenign.csv", "JSMalicious.csv", "WebassemblyBenign.csv", "WebassemblyMalicious.csv"]

# Combine the CSV files
combined_df = combine_csv_files(input_files)

# Perform PCA and save the report for 'malicious'
print("\nAnalyzing feature importance for diagnosing 'malicious'")
perform_pca_and_save_report(combined_df, target_column='malicious', n_components=2)

# Perform PCA and save the report for 'webassembly'
print("\nAnalyzing feature importance for diagnosing 'webassembly'")
perform_pca_and_save_report(combined_df, target_column='webassembly', n_components=2)

