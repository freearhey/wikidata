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

/**
 * Get ID from URL
 *
 * @param string $string String from which to get the ID
 *
 * @return string
 */
function get_id($string)
{
  preg_match('/(Q|P)\d+/i', $string, $matches);

  return !empty($matches) ? $matches[0] : $string;
}
