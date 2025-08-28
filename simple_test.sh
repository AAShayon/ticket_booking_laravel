#!/bin/bash

# Simple test to verify basic operator update functionality
echo "Testing basic operator update without image..."

curl --location --request POST 'https://ansteches.shop/api/operators/1' \
--header 'Accept: application/json' \
--header 'Authorization: Bearer 29|Fr3SWCOuva9QFbxYwtUbcQgu65kUb7jBItwl1RAP71f0aef0' \
--form '_method="PUT"' \
--form 'name="Simple Test Operator"' \
--form 'contact_email="simple@test.com"' \
--form 'contact_phone="+9876543210"' \
--form 'admin_commission_percentage="12.5"'