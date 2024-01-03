#!/bin/bash

terms=(10)
session_periods=(1 2 3 4 5 6 7 8)
proposal_types=("gov" "legis" "comt")

for term in "${terms[@]}"
do
    for session_period in "${session_periods[@]}"
    do
        for proposal_type in "${proposal_types[@]}"
        do
            php crawler.php $term $session_period $proposal_type 
        done
    done
done
