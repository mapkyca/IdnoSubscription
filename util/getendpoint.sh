#!/bin/sh
# Get the endpoint from a header
curl -i -s $1 | grep 'rel="http://mapkyc.me/1dM84ud"'
