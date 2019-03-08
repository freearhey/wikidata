<?php

/**
 * Check if given string is valid Wikidata entity ID
 * 
 * @param string $value
 * 
 * @return bool Return true if string is valid or false
 */
function is_qid($value) 
{
    return preg_match("/^Q[0-9]+/", $value);
}

/**
 * Check if given string is valid Wikidata property ID
 * 
 * @param string $value
 * 
 * @return bool Return true if string is valid or false
 */
function is_pid($value) 
{
    return preg_match("/^P[0-9]+/", $value);
}