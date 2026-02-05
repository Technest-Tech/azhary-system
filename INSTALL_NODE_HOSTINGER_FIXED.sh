#!/bin/bash

# Install Node.js on Hostinger Server (using .tar.gz to avoid xz dependency)
# Run this on your Hostinger server

echo "=== Step 1: Download Node.js (using .tar.gz format) ==="
cd ~
wget https://nodejs.org/dist/v18.20.0/node-v18.20.0-linux-x64.tar.gz

echo ""
echo "=== Step 2: Extract Node.js ==="
tar -xzf node-v18.20.0-linux-x64.tar.gz
mv node-v18.20.0-linux-x64 nodejs

echo ""
echo "=== Step 3: Add Node.js to PATH ==="
echo 'export PATH=$HOME/nodejs/bin:$PATH' >> ~/.bashrc
source ~/.bashrc

echo ""
echo "=== Step 4: Verify Node.js installation ==="
export PATH=$HOME/nodejs/bin:$PATH
node --version
npm --version

echo ""
echo "=== Step 5: Navigate to your project ==="
cd ~/domains/azharyfr.com/public_html/manage

echo ""
echo "=== Step 6: Install Puppeteer ==="
export PATH=$HOME/nodejs/bin:$PATH
npm install puppeteer

echo ""
echo "=== Step 7: Verify Puppeteer installation ==="
ls -la node_modules/puppeteer

echo ""
echo "✅ Installation complete!"
echo ""
echo "IMPORTANT: Add this to your ~/.bashrc to make Node.js available permanently:"
echo "export PATH=\$HOME/nodejs/bin:\$PATH"
echo ""
echo "Or run this command every time you SSH in:"
echo "export PATH=\$HOME/nodejs/bin:\$PATH"
