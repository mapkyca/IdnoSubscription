#!/bin/sh
# Send a notify message to a subscriber

curl -i --data "subscription=$1" $2
