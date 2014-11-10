<?php 
function virgin_theme_preprocess_html(&$variables) { 
}
/**
 * This method is used to override the theme function used for responsive layout 
 * This will add wrapper div elements with spanX classes 
 * @todo This needs to be revisited for code clean up (for removing unwanted classes)
 * @see theme_layout_responsive()
 * @param array $vars
 * @return string 
 */
function virgin_theme_layout_responsive($vars) {
  $css_id = $vars['css_id'];
  $content = $vars['content'];
  $settings = $vars['settings'];
  $display = $vars['display'];
  $layout = $vars['layout'];
  $handler = $vars['renderer'];

  layout_responsive_merge_default_settings($settings, $layout);

  $breakpoints = layout_breakpoint_load_all();
  $grids = gridbuilder_load_all();

  // Render the regions ordered as configured with minimal wrappers.
  $output = '';
  $breakpoint_counters = array();
  foreach ($content as $name => $rendered) {
    if (!empty($rendered)) {
      // Add a minimal wrapper with some common classes + configured custom classes.
      // The custom classes are used for grid placement.
      $classes = array();
      $classes[] = 'layout-responsive-region';
      $classes[] = 'layout-responsive-region-' . $name;
      $classes[] = 'rld-col';
      // Add breakpoint specific column number classes.
      foreach ($settings['overrides'] as $breakpoint_name => $breakpoint_overrides) {

        // Initialize breakpoint counter. We use this counter to figure out when
        // to inject 'first' regions depending on breakpoints. This ensures the
        // spacing of regions is properly maintained.
        if (!isset($breakpoint_counters[$breakpoint_name])) {
          $breakpoint_counters[$breakpoint_name] = 0;
        }

        // Assume that this column will span the whole width.
        $this_column = $all_columns = $grids[$breakpoints[$breakpoint_name]->grid_name]->columns;

        // If the existing counters indicate we were at the end of a row with
        // the previous region, mark this one up as being the first.
        if (is_int($breakpoint_counters[$breakpoint_name] / $all_columns)) {
          $classes[] = 'rld-span-' . $breakpoint_name . '_first';
        }

        // Check if we have region specific overrides for this breakpoint.
        foreach ($breakpoint_overrides as $region_override) {
          if ($region_override['name'] == $name) {
            // Found a region override. Modify the column width to this value.
            $this_column = $region_override['columns'];
            break;
          }
        }
        $classes[] = 'rld-span-' . $breakpoint_name . '_' . $this_column;
        
        if('standard' == $breakpoint_name){ 
          //Override all class items added 
          $classes  = array('span' . $this_column);
        }
        
        $breakpoint_counters[$breakpoint_name] += $this_column;
      }
      $output .= '<div class="' . join(' ', $classes) . '">';
      $output .= '<div class="block feature simple">';
      $output .= $rendered;
      $output .= '</div>';
      $output .= '</div>';
    }
  }

  $grid_css = layout_breakpoint_get_css();
  drupal_add_css($grid_css, array('type' => 'inline'));

  return '<div class="row">' . $output . '</div>';
}

/**
* Adding this function for breadcrumbs similar to MDS.
*/
function virgin_theme_breadcrumb($variables) {
  $breadcrumb = $variables['breadcrumb'];
  if (!empty($breadcrumb)) {
    $output = '<h2 class="element-invisible">' . t('You are here') . '</h2>';
    $crumbs = '';
    $array_size = count($breadcrumb);
    $i = 0;
      while ($i < $array_size) {
        // Using li here for adding the similar html structure.
        $crumbs .= '<li class="breadcrumb';
        if ($i == 0) {
          $crumbs .= ' first';
        }
        if ($i + 1 == $array_size) {
          $crumbs .= ' last';
        }
        // Using the icon classes from MDS to create the same look.
        $crumbs .= '">' . $breadcrumb[$i] . '<span class="divider">/</span></li> ';
        $i++;
      }
    $crumbs .= '<li class="active">' . drupal_get_title() . '</li>';
    return $crumbs;
  }
}


/**
 * Implements hook_preprocess_page().
 *
 * @see page.tpl.php
 */
function virgin_theme_preprocess_page(&$variables) {
  // Add information about the number of sidebars.
  if (!empty($variables['page']['sidebar_first']) && !empty($variables['page']['sidebar_second'])) {
    $variables['content_column_class'] = ' class="col-sm-6"';
  }
  elseif (!empty($variables['page']['sidebar_first']) || !empty($variables['page']['sidebar_second'])) {
    $variables['content_column_class'] = ' class="col-sm-9"';
  }
  else {
    $variables['content_column_class'] = ' class="col-sm-12"';
  }

  // Primary nav.
  $variables['primary_nav'] = FALSE;
  if ($variables['main_menu']) {
    // Build links.
    $variables['primary_nav'] = menu_tree(variable_get('menu_main_links_source', 'main-menu'));
    // Provide default theme wrapper function.
    $variables['primary_nav']['#theme_wrappers'] = array('menu_tree__primary');
  }

  // Secondary nav.
  $variables['secondary_nav'] = FALSE;
  if ($variables['secondary_menu']) {
    // Build links.
    $variables['secondary_nav'] = menu_tree(variable_get('menu_secondary_links_source', 'user-menu'));
    // Provide default theme wrapper function.
    $variables['secondary_nav']['#theme_wrappers'] = array('menu_tree__secondary');
  }

  $variables['navbar_classes_array'] = array('navbar');

  if (theme_get_setting('bootstrap_navbar_position') !== '') {
    $variables['navbar_classes_array'][] = 'navbar-' . theme_get_setting('bootstrap_navbar_position');
  }
 /* else {
    $variables['navbar_classes_array'][] = 'container';
  }*/
  if (theme_get_setting('bootstrap_navbar_inverse')) {
    $variables['navbar_classes_array'][] = 'navbar-inverse';
  }
  else {
    $variables['navbar_classes_array'][] = 'navbar-default';
  }
}

/**
 * Implements hook_process_page().
 *
 * @see page.tpl.php
 */
function virgin_theme_process_page(&$variables) {
  if (empty($variables['node'])) {
    $variables['title'] = '';
  }
  $variables['navbar_classes'] = implode(' ', $variables['navbar_classes_array']);
}

?>