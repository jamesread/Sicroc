#!/bin/bash
echo "COMMIT=`git log | head -n 1 | awk '{print $2}'`"
echo "DATE=`date`"
