#!/bin/bash

# Test script for updating operator with image upload
echo "Testing operator update with image upload..."

# First, let's test without the image to see if the basic update works
echo "Step 1: Testing update without image..."
curl --location --request POST 'https://ansteches.shop/api/operators/1' \
--header 'Accept: application/json' \
--header 'Authorization: Bearer 29|Fr3SWCOuva9QFbxYwtUbcQgu65kUb7jBItwl1RAP71f0aef0' \
--form '_method="PUT"' \
--form 'name="Updated Operator Name"' \
--form 'contact_email="contact@example.com"' \
--form 'contact_phone="+1234567890"' \
--form 'admin_commission_percentage="15.5"' \
--form 'nid="123456789"' \
--form 'address="123 Updated Street, City"' \
--form 'transport_business_license="TBL-98765"'

echo -e "\n\nStep 2: Testing with image (if the above worked)..."

# If the first test works, then test with image
# First, copy the image to a path without spaces
# cp "/Volumes/ExternalSSDM.2/nid Front.jpg" ./nid_front.jpg

curl --location --request POST 'https://ansteches.shop/api/operators/1' \
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

echo -e "\n\nAlternative with escaped spaces (if file path is the issue):"
curl --location --request POST 'https://ansteches.shop/api/operators/1' \
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
--form 'logo=@"/Volumes/ExternalSSDM.2/nid\ Front.jpg"'