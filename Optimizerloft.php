<?php
/*
Plugin Name: Loft Optimizer
Plugin URI: http://optimizerLoft.com
Description: Наш первый плагин по оптимизации изображений на wordpress.
Version: 1.0
Author: Basicteam
Author URL: http://basicteam.com
 */

function add_new_word() {
    echo "Hello World123123";
}

add_action('admin_init', 'add_new_word');