<?php 

function str_slug($string) 
{
  $separator = '_';
  $flip = '-';
  $string = preg_replace('!['.preg_quote($flip).']+!u', $separator, $string);
  $string = preg_replace('![^'.preg_quote($separator).'\pL\pN\s]+!u', '', mb_strtolower($string));
  $string = preg_replace('!['.preg_quote($separator).'\s]+!u', $separator, $string);

  return trim($string, $separator);
}