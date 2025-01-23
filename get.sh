#!/bin/bash

# Exit immediately if a command exits with a non-zero status.
set -e

REPO_URL="https://github.com/ryersondmp/sa11y.git"
BRANCH="master"

# Temporary directory for the clone.
TMP_DIR="temp"

# Target directory for assets.
TARGET_DIR="assets/src"

# Create the lang directory inside the target if it doesn't exist.
LANG_DIR="$TARGET_DIR/lang"
mkdir -p "$LANG_DIR"

# Directories and files to pull.
SPECIFIC_FILES=(
  "dist/js/sa11y.umd.min.js"
  "dist/css/sa11y.min.css"
)

# Clone the repository from the master branch.
echo "Cloning the repository..."
mkdir -p "$TMP_DIR"
git clone --depth 1 --branch "$BRANCH" "$REPO_URL" "$TMP_DIR"

# Navigate into the temporary directory.
cd "$TMP_DIR"

# Dynamically find all umd.js files in the /dist/js/lang directory.
LANG_FILES=$(find "dist/js/lang" -type f -name "*umd.js")

# Clean up old files in the target directory.
echo "Removing old files..."
for FILE in "${SPECIFIC_FILES[@]}"; do
  BASENAME=$(basename "$FILE")
  rm -f "../$TARGET_DIR/$BASENAME"
done

# Move the lang files to the lang directory inside assets/src.
echo "Copying lang files to $LANG_DIR..."
for FILE in $LANG_FILES; do
  if [[ -f "$FILE" ]]; then
    # Get the base name of the file.
    BASENAME=$(basename "$FILE")
    # Move the file to the lang directory.
    mv "$FILE" "../$LANG_DIR/$BASENAME"
    echo "Moved $FILE to $LANG_DIR/"
  else
    echo "Warning: File $FILE not found, skipping..."
  fi
done

# Move the specific files (sa11y.umd.min.js and sa11y.min.css) to the target directory.
echo "Copying specific files..."
for FILE in "${SPECIFIC_FILES[@]}"; do
  if [[ -f "$FILE" ]]; then
    mv "$FILE" "../$TARGET_DIR/"
    echo "Moved $FILE to $TARGET_DIR/"
  else
    echo "Warning: File $FILE not found, skipping..."
  fi
done

# Clean up.
echo "Cleaning up temporary files..."
cd ..
rm -rf "$TMP_DIR"

echo "Done!"
