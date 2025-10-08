<?php

/**
 *
 * Codestar Framework
 * A Simple and Lightweight WordPress Option Framework for Themes and Plugins
 *
 */

require_once get_theme_file_path() .'/admin/codestar-framework/codestar-framework.php';

// if (class_exists('CSF')) {
//     $prefix = 'freshia_options';
// }

// Control core classes for avoid errors
if( class_exists( 'CSF' ) ) {

  //
  // Set a unique slug-like ID
  $prefix = 'freshia_options';

  //
  // Create options
  CSF::createOptions( $prefix, array(
    'menu_title' => 'freshia主题设置',
    'menu_slug'  => 'freshia-options',
  ) );

  //
  // Create a top-tab
  CSF::createSection( $prefix, array(
    'id'    => 'primary_tab', // Set a unique slug-like ID
    'title' => 'Primary Tab',
  ) );

  //
  // Create a sub-tab
  CSF::createSection( $prefix, array(
    'parent' => 'primary_tab', // The slug id of the parent section
    'title'  => 'Sub Tab 1',
    'fields' => array(

      // A text field
      array(
        'id'    => 'opt-text',
        'type'  => 'text',
        'title' => 'Simple Text',
      ),

    )
  ) );

  //
  // Create a sub-tab
  CSF::createSection( $prefix, array(
    'parent' => 'primary_tab',
    'title'  => 'Sub Tab 2',
    'fields' => array(

      // A textarea field
      array(
        'id'    => 'opt-textarea',
        'type'  => 'textarea',
        'title' => 'Simple Textarea',
      ),

    )
  ) );

  //
  // Create a top-tab
  CSF::createSection( $prefix, array(
    'id'    => 'secondry_tab', // Set a unique slug-like ID
    'title' => 'Secondry Tab',
  ) );


  //
  // Create a sub-tab
  CSF::createSection( $prefix, array(
    'parent' => 'secondry_tab', // The slug id of the parent section
    'title'  => 'Sub Tab 1',
    'fields' => array(

      // A switcher field
      array(
        'id'    => 'opt-switcher',
        'type'  => 'switcher',
        'title' => 'Simple Switcher',
      ),

    )
  ) );

}
