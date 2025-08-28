#!/bin/bash

# Corrected curl command based on project specifications
echo "Testing operator update with corrected curl command..."

# Method 1: Using the correct URL structure (with /public/) and proper form-data format
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
--form 'logo=@"/Volumes/ExternalSSDM.2/nid Front.jpg"'

echo -e "\n\nAlternative with escaped spaces:"
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
--form 'logo=@"/Volumes/ExternalSSDM.2/nid\ Front.jpg"'

echo -e "\n\nMethod 2: Copy file to local directory first (recommended):"
echo "First run: cp \"/Volumes/ExternalSSDM.2/nid Front.jpg\" ./nid_front.jpg"
echo "Then run the following command:"
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