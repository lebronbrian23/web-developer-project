#!/bin/bash

# Test server-side validation without JavaScript

echo "🔍 Testing Server-Side Validation..."
echo ""

# Step 1: Get valid CSRF token (macOS compatible grep)
echo "1️⃣ Fetching valid CSRF token..."
CSRF_TOKEN=$(curl -s http://localhost:8000/index.php | grep 'csrf_token' | sed -n 's/.*value="\([^"]*\)".*/\1/p')
echo "✓ CSRF Token: ${CSRF_TOKEN:0:20}..."
echo ""

# Step 2: Submit form with > 1000 words
echo "2️⃣ Submitting form with 1001 words (exceeds limit)..."
RESPONSE=$(curl -s -X POST http://localhost:8000/index.php \
  -F "csrf_token=$CSRF_TOKEN" \
  -F "title=Test Title" \
  -F "country=CA" \
  -F "state_or_province=ON" \
  -F "budget=medium" \
  -F "script=$(printf 'word %.0s' {1..1001})")

echo ""
echo "3️⃣ Checking for error message..."

# Look for script field error (macOS compatible)
if echo "$RESPONSE" | grep -q "script must not exceed 1000 words"; then
    echo "✅ Server-side validation WORKING!"
    echo "   Error message found: 'Job script must not exceed 1000 words.'"
else
    echo "❌ Server-side validation NOT showing error in HTML"
    echo "   Checking if error exists in response data..."
    
    # Check if form was re-rendered (validation failed but error not shown)
    if echo "$RESPONSE" | grep -q "Post your project"; then
        echo "   ✓ Form was re-rendered (validation triggered)"
        echo "   ✗ But error message not in HTML output"
        echo ""
        echo "🔧 Looking for where script textarea value is..."
        echo "$RESPONSE" | grep -A 1 'id="script"' | head -5
    fi
fi

echo ""
echo "4️⃣ Saving full response to debug.html..."
echo "$RESPONSE" > debug.html
echo "   View: cat debug.html | grep -A 5 'script' | head -20"
