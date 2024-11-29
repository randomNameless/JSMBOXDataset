# Open the input file in read mode
with open("Benign_wobfruscated.txt", "r") as input_file:
    # Read all lines from the file
    lines = input_file.readlines()

# Filter lines containing 'catchwild'
wild_lines = [line for line in lines if 'catchwild' in line]

# Write the filtered lines to the output file
with open("WildBenign_wobfruscated.txt", "w") as output_file:
    output_file.writelines(wild_lines)

print("Lines containing 'catchwild' have been written to WildBenign.txt.")

