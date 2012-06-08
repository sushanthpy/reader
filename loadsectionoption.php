<?php  // $Id:,v 1.0 2008/01/20 16:10:00 Serafim Panov

    require_once("../../config.php");
 
    $id                = optional_param('id', 0, PARAM_INT); 

    require_login($id);
    
    $currentcoursesections = $DB->get_records("course_sections", array("course" => $id));
    
    $tocourse = $DB->get_record("course", array("id" => $id));
    $t = 0;
    
    foreach ($currentcoursesections as $currentcoursesections_) {
      if ($t <= $tocourse->numsections) {
        $sectionname = strip_tags(trim(str_replace(array("\r", "\n"), "", $currentcoursesections_->summary)));
        if (empty($sectionname)) $sectionname = 'Section '.$currentcoursesections_->section;
        if (strlen($sectionname) > 25) $sectionname = substr($sectionname, 0, 25)."...";
        echo '<option value="'.$currentcoursesections_->section.'">'.$sectionname.'</option>';
      }
      $t++;
    }
