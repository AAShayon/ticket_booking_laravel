#!/bin/bash

# Simple curl test that only uses fields known to exist in the database
echo "Testing operator update with only existing fields..."

# First, let's copy the file to a local directory to avoid path issues
echo "Step 1: Copying file to local directory..."
cp "/Volumes/ExternalSSDM.2/nid Front.jpg" ./nid_front.jpg

# Step 2: Run curl command with only existing fields
echo "Step 2: Running curl command with only existing fields..."
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