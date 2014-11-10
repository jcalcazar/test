<?php

/**
 * Implements hook_panels_layouts().
 */
// Plugin definition
$plugin = array(
  'title' => t('Virgin Multiple Rows'),
  'category' => t('Mixed responsive'),
  'icon' => 'virgin_multpile_rows_mixed.png',
  'theme' => 'virgin_multpile_rows_mixed',
  'css' => 'virgin_multpile_rows_mixed.css',
  'regions' => array(
    'top_left' => t('Top Left'),
	'top_right' => t('Top Right'),
    'middle_left' => t('Middle Left'),
	'middle_center' => t('Middle Center'),
	'middle_right' => t('Middle Right'),
    'bottom' => t('Bottom'),
  ),
);
