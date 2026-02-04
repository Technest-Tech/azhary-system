#!/bin/bash
# Install Node.js and npm on Hostinger Server
# Run these commands via SSH

echo "=== Installing Node.js on Hostinger ==="

# Step 1: Go to home directory
cd ~

# Step 2: Download Node.js (Linux x64)
echo "Downloading Node.js..."
wget https://nodejs.org/dist/v18.20.0/node-v18.20.0-linux-x64.tar.xz

# Step 3: Extract
echo "Extracting..."
tar -xf node-v18.20.0-linux-x64.tar.xz

# Step 4: Rename for easier access
mv node-v18.20.0-linux-x64 nodejs

# Step 5: Add to PATH (permanent)
echo 'export PATH=$HOME/nodejs/bin:$PATH' >> ~/.bashrc
source ~/.bashrc

# Step 6: Verify installation
echo ""
echo "=== Verification ==="
echo "Node.js version:"
$HOME/nodejs/bin/node --version
echo "npm version:"
$HOME/nodejs/bin/npm --version

echo ""
echo "=== Installation Complete ==="
echo "Now navigate to your project and run:"
echo "cd /path/to/your/project"
echo "npm install puppeteer"
