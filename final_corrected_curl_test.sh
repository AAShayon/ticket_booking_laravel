#!/bin/bash

# Final corrected curl command that addresses both the database schema and file path issues
echo "Testing operator update with final corrected curl command..."

# First, let's copy the file to a local directory to avoid path issues
echo "Step 1: Copying file to local directory..."
cp "/Volumes/ExternalSSDM.2/nid Front.jpg" ./nid_front.jpg

# Method 1: Using the correct URL structure and proper form-data format
echo "Step 2: Running curl command with local file..."
curl --location --request POST 'https://ansteches.shop/public/api/operators/1' \
--header 'Accept: application/json' \
--header 'Authorization: Bearer 29|Fr3SWCOuva9QFbxYwtUbcQgu65kUb7jBItwl1RAP71f0aef0' \
--form '_method="PUT"' \
--form 'name="Updated Operator Name"' \
--form 'contact_email="contact@example.com"' \
--form 'contact_phone="+1234567890"' \
--form 'admin_commission_percentage="15.5"' \
--form 'nid="123456789"' \
--form 'address="123 Updated Street, City"' \
--form 'transport_business_license="TBL-98765"' \
--form 'logo=@"./nid_front.jpg"'

echo -e "\n\nIf the above doesn't work, try this minimal version without the problematic fields:"
curl --location --request POST 'https://ansteches.shop/public/api/operators/1' \
--header 'Accept: application/json' \
--header 'Authorization: Bearer 29|Fr3SWCOuva9QFbxYwtUbcQgu65kUb7jBItwl1RAP71f0aef0' \
--form '_method="PUT"' \
--form 'name="Updated Operator Name"' \
--form 'contact_email="contact@example.com"' \
--form 'contact_phone="+1234567890"' \
--form 'admin_commission_percentage="15.5"' \
--form 'logo=@"./nid_front.jpg"'

echo -e "\n\nClean up the local file:"
rm ./nid_front.jpg