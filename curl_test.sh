#!/bin/bash

# Corrected curl command for updating operator with image upload
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
--form 'logo=@"./nid_Front.jpg"'

echo -e "\n\nIf the above file path doesn't work, try this alternative with escaped spaces:"
echo "--------------------------------------------------------------------------------"

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