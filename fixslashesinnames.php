<?php

include_once "../../config.php";

    $publishersquizzes = $DB->get_records ("reader_publisher");
    
    echo '<pre>';
    
    foreach ($publishersquizzes as $publishersquizze) {
      if (strstr($publishersquizze->name, "\'")) {
        $DB->set_field("reader_publisher",  "name", stripslashes($publishersquizze->name), array( "id" => $publishersquizze->id));
        echo '..reader title updating: '.$publishersquizze->name.'
';
      }
    }