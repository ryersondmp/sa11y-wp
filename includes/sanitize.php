<?php

/* ************************************************************ */
/*  Sanitize                                                    */
/* ************************************************************ */

// Exit if accessed directly.
if (!defined('ABSPATH')) exit;

global $sa11y_replaceHTML;
$sa11y_replaceHTML = [
  '&lt;' => '',
  '&apos;' => '',
  '&amp;' => '',
  '&percnt;' => '',
  '&#96;' => '',
  '<' => '',
  ';' => '',
  '@' => '',
  '$' => '',
  '|' => '',
  '%' => '',
  '&' => '',
  '{' => '',
  '}' => '',
  '!' => '',
  '?' => '',
  '`' => '',
  '\\"' => '"', // Allow double quotes.
  "\\'" => '"', // Change single quote to double.
  '\'' => '"', // Change single quote to double.
];
$sa11y_replaceExtra = [
  'gt;' => '',
  '>' => '',
];
global $sa11y_replaceExtraHTML;
$sa11y_replaceExtraHTML = array_merge($sa11y_replaceHTML, $sa11y_replaceExtra);

// Validate CHECKBOXES
function sa11y_sanitize_checkboxes($value)
{
  $value = absint($value);
  $value = isset($value) && 1 == $value ? 1 : 0;
  return $value;
}

// Sanitize TARGET fields.
function sa11y_sanitize_target_fields($value)
{
  global $sa11y_replaceExtraHTML;
  $sanitizedValue = sanitize_text_field($value);
  $sanitizedValue = trim(strtr($sanitizedValue, $sa11y_replaceExtraHTML));
  $sanitizedValue = preg_replace('/^(javascript|data):/', '', $sanitizedValue); // URL schemes
  $sanitizedValue = preg_replace('/\s+/', '', $sanitizedValue); // Remove whitespace
  $sanitizedValue = substr($sanitizedValue, 0, 400); // Max 400 characters.
  return $sanitizedValue;
}

// Sanitize TEXT fields.
function sa11y_sanitize_text_fields($value)
{
  global $sa11y_replaceHTML;
  $sanitizedValue = sanitize_text_field($value);
  $sanitizedValue = trim(strtr($sanitizedValue, $sa11y_replaceHTML));
  $sanitizedValue = preg_replace('/^(javascript|data):/', '', $sanitizedValue); // URL schemes
  // Remove excessive white space and replace with single whitespace.
  $sanitizedValue = preg_replace('/\s{2,}|\h(?<!\h)\h+/', ' ', $sanitizedValue);
  $sanitizedValue = substr($sanitizedValue, 0, 400); // Max 400 characters.
  return $sanitizedValue;
}

// Sanitize TEXT fields (extra).
function sa11y_sanitize_extra_text_fields($value)
{
  global $sa11y_replaceExtraHTML;
  $sanitizedValue = sanitize_text_field($value);
  $sanitizedValue = preg_replace('/[^a-zA-Z0-9.,:\s]/', '', $sanitizedValue);
  $sanitizedValue = preg_replace('/^(javascript|data):/', '', $sanitizedValue); // URL schemes
  $sanitizedValue = trim(strtr($sanitizedValue, $sa11y_replaceExtraHTML));
  // Remove excessive white space and replace with single whitespace.
  $sanitizedValue = preg_replace('/\s{2,}|\h(?<!\h)\h+/', ' ', $sanitizedValue);
  $sanitizedValue = substr($sanitizedValue, 0, 400); // Max 400 characters.
  return $sanitizedValue;
}

// Sanitize TEXT AREA fields.
function sa11y_sanitize_textarea_fields($value)
{
  global $sa11y_replaceExtraHTML;
  $sanitizedValue = sanitize_textarea_field($value);
  // Strip all characters except numbers, letters, .,: and whitespace
  $sanitizedValue = preg_replace('/[^a-zA-Z0-9.,:\s]/', '', $sanitizedValue);
  $sanitizedValue = preg_replace('/^(javascript|data):/', '', $sanitizedValue); // URL schemes
  $sanitizedValue = trim(strtr($sanitizedValue, $sa11y_replaceExtraHTML));
  $sanitizedValue = substr($sanitizedValue, 0, 400); // Max 400 characters.
  return $sanitizedValue;
}
