<?php
    
    require_once("../../config.php");
    require_once("lib.php");
    
    $id        = optional_param('id', 0, PARAM_INT); 
    $uid       = optional_param('uid', 0, PARAM_INT); 

    if ($id) {
        if (! $cm = get_coursemodule_from_id('reader', $id)) {
            error("Course Module ID was incorrect");
        }
        if (! $course = $DB->get_record("course", array( "id" => $cm->course))) {
            error("Course is misconfigured");
        }
        if (! $reader = $DB->get_record("reader", array( "id" => $cm->instance))) {
            error("Course module is incorrect");
        }
    } else {
        if (! $reader = $DB->get_record("reader", array( "id" => $a))) {
            error("Course module is incorrect");
        }
        if (! $course = $DB->get_record("course", array( "id" => $reader->course))) {
            error("Course is misconfigured");
        }
        if (! $cm = get_coursemodule_from_instance("reader", $reader->id, $course->id)) {
            error("Course Module ID was incorrect");
        }
    }

    require_login($course->id);

    add_to_log($course->id, "reader", "show incorrect quizzes", "showincorrectquizzes.php?id=$id", "$cm->instance");
    
    
    if ($oldattempts = $DB->get_records_sql("SELECT * FROM {reader_attempts} WHERE userid= ? and timefinish <= ? ORDER BY timefinish", array($uid,$reader->ignordate))) {
        foreach ($oldattempts as $oldattempt) {
            //-----------------Book old covers------------------//
            if (strtolower($oldattempt->passed) != 'true') {
                $bookdata = $DB->get_record("reader_publisher", array( "id" => $oldattempt->quizid));
                $bookincorrectinprevterm .= "{$bookdata->name}<br />";
            }
            //----------------------------------------------//
        }
    }
    
    if ($bookincorrectinprevterm) {
        echo $bookincorrectinprevterm;
    }
    else
    {
        print_string("noincorrectquizzes", "reader");
    }
    
