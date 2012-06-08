<?php // $Id: ,v 1.0 2008/01/20 16:10:00 Serafim Panov

    require_once("../../config.php");
    require_once("lib.php");

    $id = optional_param('id', 0, PARAM_INT); // Course Module ID, or
    
    if (!$course = $DB->get_record("course", array( "id" => $id))) {
        error("Course ID is incorrect");
    }
    
    require_course_login($course);
    
    add_to_log($course->id, "Reader", "view all", "index.php?id=$course->id", "");
    

    $PAGE->set_url('/mod/reader/index.php', array('id' => $cm->id));
    
    $title = $course->shortname . ': ' . format_string($reader->name);
    $PAGE->set_title($title);
    $PAGE->set_heading($course->fullname);
    /*

    $navlinks = array ();
    $navlinks[] = array ('name' => get_string('modulenameplural', 'reader'), 'link' => 'index.php?id='.$course->id, 'type' => 'activity');
    $navigation = build_navigation($navlinks);

    print_header_simple(get_string('modulenameplural', 'reader'), "", $navigation, "", "", true, '', '');
                 
    */
                 
    if (! $displays = get_all_instances_in_course("reader", $course)) {
        notice("There are no displays", "../../course/view.php?id=$course->id");
        die;
    }
    
    echo $OUTPUT->header();
    
    
    $timenow = time();
    
    echo "<br />";
    
    print_simple_box_start('center', '500', '#ffffff', 10); 

    foreach ($displays as $display) {

        echo '<a href="view.php?id='.$display->coursemodule.'">'.$display->name.'</a><br />';

    }

    print_simple_box_end();

    echo "<br />";

    echo $OUTPUT->footer();

