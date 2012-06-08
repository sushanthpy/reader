<?php  // $Id:,v 1.0 2008/01/20 16:10:00 Serafim Panov

    require_once("../../config.php");
    require_once("lib.php");
    require_once("$CFG->libdir/tablelib.php");
    require_once("$CFG->libdir/xmlize.php");
    require_once($CFG->dirroot.'/course/moodleform_mod.php');
    //require_once("dlquizzes_form.php");
    
    $id                = optional_param('id', 0, PARAM_INT); 
    $a                 = optional_param('a', NULL, PARAM_CLEAN); 
    /* OLD LINES START **
    $quiz              = optional_param('quiz', NULL, PARAM_CLEAN); 
    $installall        = optional_param('installall', NULL, PARAM_CLEAN); 
    ** OLD LINES STOP **/
    // NEW LINES START
    if (function_exists('optional_param_array')) {
        $quiz          = optional_param_array('quiz', NULL, PARAM_CLEAN); 
        $installall    = optional_param_array('installall', NULL, PARAM_CLEAN); 
    } else {
        $quiz          = optional_param('quiz', NULL, PARAM_CLEAN); 
        $installall    = optional_param('installall', NULL, PARAM_CLEAN); 
    }
    // NEW LINES STOP
    $password          = optional_param('password', NULL, PARAM_CLEAN); 
    $second            = optional_param('second', NULL, PARAM_CLEAN); 
    $step              = optional_param('step', NULL, PARAM_CLEAN); 
    
    if ($id) {
        if (! $cm = get_coursemodule_from_id('reader', $id)) {
            error("Course Module ID was incorrect");
        }
        if (! $course = $DB->get_record("course", array("id" => $cm->course))) {
            error("Course is misconfigured");
        }
        if (! $reader = $DB->get_record("reader", array("id" => $cm->instance))) {
            error("Course module is incorrect");
        }
    } else {
        if (! $reader = $DB->get_record("reader", array("id" => $a))) {
            error("Course module is incorrect");
        }
        if (! $course = $DB->get_record("course", array("id" => $reader->course))) {
            error("Course is misconfigured");
        }
        if (! $cm = get_coursemodule_from_instance("reader", $reader->id, $course->id)) {
            error("Course Module ID was incorrect");
        }
    }

    require_login($course->id);

    add_to_log($course->id, "reader", "Download Quizzes", "dlquizzes.php?id=$id", "$cm->instance");
    
    //$navigation = build_navigation('', $cm); 
    
    $readercfg = get_config('reader');
    
    //print_header_simple(format_string($reader->name), "", $navigation, "", "", true,
    //                  update_module_button($cm->id, $course->id, get_string('modulename', 'reader')), navmenu($course, $cm));
    
// Initialize $PAGE, compute blocks
    $PAGE->set_url('/mod/reader/dlquizzes.php', array('id' => $cm->id));
    
    $title = $course->shortname . ': ' . format_string($reader->name);
    $PAGE->set_title($title);
    $PAGE->set_heading($course->fullname);
    
    echo $OUTPUT->header();
    
    
    require_once('hide.js');
    
    if (!function_exists('file')) { 
       error("FILE function unavailable. "); 
    }

    $listofpublishers = reader_curlfile("{$readercfg->reader_serverlink}/?a=publishers&login={$readercfg->reader_serverlogin}&password={$readercfg->reader_serverpassword}");
    
    //echo $listofpublishers;
    
    $listofpublishers = xmlize(reader_makexml($listofpublishers));
    
    foreach ($listofpublishers['myxml']['#']['item'] as $listofpublisher) {
        $quizzes[$listofpublisher['@']['publisher']][$listofpublisher['@']['level']][$listofpublisher['@']['id']] = $listofpublisher['#'];
        $needpassword[$listofpublisher['@']['publisher']][$listofpublisher['@']['level']] = $listofpublisher['@']['needpass'];
    }
    
    $allquestionscount = 0;
    $printerrormessage = false;
    
    foreach ($quizzes as $key =>$value) {
        foreach ($value as $key2 => $value2) {
            foreach ($value2 as $key3 => $value3) {
                $allquestionscount++;
                $checkboxdatapublishersreal[$key][] = $key3;
                $checkboxdatalevelsreal[$key][$key2][] = $key3;
                
                $checkboxdatapublishers[$key][] = $allquestionscount;
                $checkboxdatalevels[$key][$key2][] = $allquestionscount;
                $quizzescountid[$key3] = $allquestionscount;
                
                /*  FOR LOGIN AND PASS CHECKING  */
                if (strstr($value3, "You should be student")) {
                    $printerrormessage = true;
                }
            }
        }
    }
    
    require_once ('tabs_dl.php');
    
    $context = get_context_instance(CONTEXT_COURSE, $course->id);
    $contextmodule = get_context_instance(CONTEXT_MODULE, $cm->id);
    if (!has_capability('mod/reader:manage', $contextmodule)) { 
        error("You should be \"Edit\" Teacher");
    }

    // NEW LINES START
    if (empty($quiz)) {
        $quiz = array();
    }
    // NEW LINES STOP

    if ($installall) {
        foreach ($installall as $installall_) {
            $installalldata = explode (",", $installall_);
            foreach ($installalldata as $installalldata_) {
                if (!empty($installalldata_)) {
                    $quiz[] = $installalldata_;
                }
            }
        }
        
        $quiz = array_unique($quiz); 
    }
    
    echo $OUTPUT->box_start('generalbox');
    
    /*  FOR LOGIN AND PASS CHECKING  */
    if ($printerrormessage) {
        echo "{$readercfg->reader_serverlink}/?a=publishers&login={$readercfg->reader_serverlogin}&password={$readercfg->reader_serverpassword} <br />";
        error(" In order to download quizzes, you need to be registered on the  \"Moodle Reader Users\" course on MoodleReader.org ( <a href=\"http://moodlereader.org/moodle/course/view.php?id=15\">http://moodlereader.org/moodle/course/view.php?id=15</a> ),  Please contact the system administrator ( admin@moodlereader.org ) to register yourself, providing information on your school, your position, the grade level of your students and the approximate number of students who will be using the system.");
        echo $OUTPUT->box_end();
        die();
    }
    
    
    if (!$quiz) {
        echo $OUTPUT->box_start('generalbox');
        
        echo '<script type="text/javascript">
function setChecked(obj,from,to)
{
    for (var i=from; i<=to; i++)
    {
      if (document.getElementById(\'quiz_\' + i)) {
        document.getElementById(\'quiz_\' + i).checked = obj.checked;
      }
    }
}
</script>';

        //echo '<div style="width:600px"><a href="#" onclick="expandall();">Show All</a> / <a href="#" onclick="collapseall();">Hide All</a><br /><br />';
        //$form = new reader_selectuploadbooks_form('dlquizzes.php?id='.$id);
        //$form->display();
        
        echo '<form action="dlquizzes.php?id='.$id.'" method="post" id="mform1">';
        echo '<div style="width:600px"><a href="#" onclick="expandall();">Show All</a> / <a href="#" onclick="collapseall();">Hide All</a><br />';

        //vivod
        $cp = 0;

        if ($quizzes) {
            foreach ($quizzes as $publiher => $datas) {
                $cp++;
                echo '<br /><a href="#" onclick="toggle(\'comments_'.$cp.'\');return false">
                      <span id="comments_'.$cp.'indicator"><img src="'.$CFG->wwwroot.'/mod/reader/open.gif" alt="Opened folder" /></span></a> ';
                echo ' <b>'.$publiher.'</b>';

                //echo '<span id="comments_'.$cp.'"><input type="checkbox" name="installall['.$cp.']" onclick="setChecked(this,'.$checkboxdatapublishers[$publiher][0].','.end($checkboxdatapublishers[$publiher]).')" value="'.implode(",", $checkboxdatapublishersreal[$publiher]).'" /><span id="seltext_'.$cp.'">Install All</span>';
                echo '<span id="comments_'.$cp.'"><input type="checkbox" name="installall['.$cp.']" onclick="setChecked(this,'.$checkboxdatapublishers[$publiher][0].','.end($checkboxdatapublishers[$publiher]).')" value="" /><span id="seltext_'.$cp.'">Install All</span>';
                foreach ($datas as $level => $quizzesdata) {
                    $cp++;

                    echo '<div style="padding-left:40px;padding-top:10px;padding-bottom:10px;"><a href="#" onclick="toggle(\'comments_'.$cp.'\');return false">
                          <span id="comments_'.$cp.'indicator"><img src="'.$CFG->wwwroot.'/mod/reader/open.gif" alt="Opened folder" /></span></a> ';

                    if ($needpassword[$publiher][$level] == "true") {
                        echo ' <img src="'.$CFG->wwwroot.'/mod/reader/pw.png" width="23" height="15" alt="Need password" /> ';
                    }

                    echo '<b>'.$level.'</b>';
                    //echo '<span id="comments_'.$cp.'"><input type="checkbox" name="installall['.$cp.']" onclick="setChecked(this,'.$checkboxdatalevels[$publiher][$level][0].','.end($checkboxdatalevels[$publiher][$level]).')" value="'.implode(",", $checkboxdatalevelsreal[$publiher][$level]).'" /><span id="seltext_'.$cp.'">Install All</span>';
                    echo '<span id="comments_'.$cp.'"><input type="checkbox" name="installall['.$cp.']" onclick="setChecked(this,'.$checkboxdatalevels[$publiher][$level][0].','.end($checkboxdatalevels[$publiher][$level]).')" value="" /><span id="seltext_'.$cp.'">Install All</span>';
                    foreach ($quizzesdata as $quizid => $quiztitle) {
                        echo '<div style="padding-left:20px;"><input type="checkbox" name="quiz[]" id="quiz_'.$quizzescountid[$quizid].'" value="'.$quizid.'" />'.$quiztitle.'</div>';
                    }
                    echo '</span></div>';
                }
                echo '</span>';
            }

            echo '<div style="margin-top:40px;margin-left:200px;"><input type="submit" name="downloadquizzes" value="Install Quizzes" /></div>';
        }
        
        echo '<input type="hidden" name="step" value="1" />';  //”¡–¿“‹ ≈—À» Õ”∆Õ€ œ¿–ŒÀ»

        echo '</div>';
        echo '</form>';
        
        echo $OUTPUT->box_end();
    }
    else
    {
        $listofpublishers = reader_curlfile("{$readercfg->reader_serverlink}/?a=publishers&login={$readercfg->reader_serverlogin}&password={$readercfg->reader_serverpassword}");
        $listofpublishers = xmlize(reader_makexml($listofpublishers));
    
        foreach ($listofpublishers['myxml']['#']['item'] as $listofpublisher) {
            $quizzes[$listofpublisher['@']['publisher']][$listofpublisher['@']['level']][$listofpublisher['@']['id']] = $listofpublisher['#'];
        }
    
        //Check Passwords
        if ($step == 1) {
            $postzapros = array('quiz'=>$quiz);
        }
        else
        {
            $postzapros = array('password'=>$password, 'quiz'=>$quiz);
        }
        $listofpublishers = xmlize(reader_file("{$readercfg->reader_serverlink}/?a=quizzes&login={$readercfg->reader_serverlogin}&password={$readercfg->reader_serverpassword}", $postzapros));
        foreach ($listofpublishers['myxml']['#']['item'] as $listofpublisher) {
            $publishers[$listofpublisher['@']['publisher']][$listofpublisher['@']['level']]['pass'] = $listofpublisher['#'];
            if (isset($listofpublisher['@']['status'])) {
                $publishers[$listofpublisher['@']['publisher']][$listofpublisher['@']['level']]['status'] = $listofpublisher['@']['status'];
            }
        }
        
        //Passwords form
        $passprefix = "";
        
        /*
        class reader_uploadbooks_form extends moodleform {
            function definition() {
                global $CFG,$DB,$course,$password,$publishers,$passprefix,$second,$quizzes,$removequizzes,$step,$id,$readercfg,$reader,$readercourseexist,$quiz;
                $mform    =& $this->_form;
                
                $mform->addElement('header','general', get_string('passwords_list', 'reader')); 
                if ($password) {
                    $mform->addElement('hidden', 'second', 'true');
                }
                
                foreach ($publishers as $key => $value) {
                    foreach ($value as $key2 => $value2) {
                        if ($value2['pass'] == 'true') {
                            if (!empty($value2['status'])) {
                                if ($value2['status'] == 'correct') {
                                    $mform->addElement('static', 'description', $passprefix.' '.$key.' ['.$key2.']', '<font color="green"> - correct</font>');
                                    $mform->addElement('hidden', 'password['.$key.']['.$key2.']', $password[$key][$key2]);
                                }
                                else if ($value2['status'] == 'incorrect')
                                {
                                    $checkincorrect = true;
                                    if (!$second) {
                                        $buttonarray=array();
                                        $buttonarray[] =& $mform->createElement('password', 'password['.$key.']['.$key2.']');
                                        $buttonarray[] =& $mform->createElement('static', 'description', '', '<font color="red">'.get_string('incorrect', 'reader').'</font>');
                                        $mform->addGroup($buttonarray, '', $passprefix.' '.$key.' ['.$key2.']');
                                    }
                                    else
                                    {
                                        foreach ($quizzes[$key][$key2] as $rmquizid => $rmquizname) {
                                            $removequizzes[$rmquizid] = $rmquizid;
                                        }
                                        $mform->addElement('static', 'description', $passprefix.' '.$key.' ['.$key2.']', '<font color="red">'.get_string('incorrect2', 'reader').'</font>');
                                    }
                                }
                            }
                            else
                            {
                                $mform->addElement('password', 'password['.$key.']['.$key2.']', $passprefix.' '.$key.' ['.$key2.']');
                            }
                        }
                        else
                        {
                            $mform->addElement('static', 'description', $passprefix.' '.$key.' ['.$key2.']', ' '.get_string('no_password', 'reader'));
                        }
                    }
                }
                
                if (!$checkincorrect && !$step) {
                    unset($mform);
                    $mform    =& $this->_form;
                    
                    echo $OUTPUT->box_start('generalbox');
                    echo get_string("quizzesmustbeinstalled", "reader");
                    echo $OUTPUT->box_end();
                    
                    $mform->addElement('header','general', get_string('select_course', 'reader')); 
                    
                    // ----------------- NEW CODE ------------//
                    
                    if ($readercfg->reader_usecourse) $puttocourse = $readercfg->reader_usecourse;
                    if ($reader->usecourse)     $puttocourse = $reader->usecourse;
                    
                    if (empty($puttocourse)) { 
                      $readercourseexist = $DB->get_record("course", array("shortname" => "Reader"));
                      $puttocourse = $readercourseexist->id;
                    }
                    
                    if (empty($puttocourse)) $selectcourseform[0] = 'Create new course';
                    
                    $courses = get_courses();
                    
                    foreach ($courses as $course) {
                        $selectcourseform[$course->id] = $course->fullname;
                    }
                    
                    $mform->addElement('select', 'courseid', get_string('use_this_course', 'reader'), $selectcourseform);
                    
                    if (empty($puttocourse)) {
                      $mform->setDefault('courseid', 0);
                    } else {
                      $mform->setDefault('courseid', $puttocourse);
                    }
                    
                    if (empty($puttocourse)) $puttocourse = $course->id;
                    
                    $currentcoursesections = $DB->get_records("course_sections", array("course" => $puttocourse));
                    
                    $selectorsection = '
                    <select name="selectorsection">';
                    foreach ($currentcoursesections as $currentcoursesections_) {
                      $sectionname = strip_tags(trim(str_replace(array("\r", "\n"), "", $currentcoursesections_->summary)));
                      if (empty($sectionname)) $sectionname = 'Section '.$currentcoursesections_->section;
                      if (strlen($sectionname) > 25) $sectionname = substr($sectionname, 0, 25)."...";
                      $selectorsection .= '<option value="'.$currentcoursesections_->section.'">'.$sectionname;
                      $selectorsection .= '</option>';
                      $selectsectionform[$currentcoursesections_->section] = $sectionname;
                    }
                    $selectorsection .= '</section>
                    ';
                    
                    //--------------- END -------------------------//
                    
                    
                    $mform->addElement('html', '<div style="clear:both;"></div><div style="padding:20px">
                <div><input type="radio" name="sectionchoosing" value="1"/> '.get_string('s_sectiontothebottom', 'reader').'</div>
                <div><input type="radio" name="sectionchoosing" value="2" checked="checked" /> '.get_string('s_sectiontoseparate', 'reader').'</div>
                <div><div style="float: left; width: 25px;"><input type="radio" name="sectionchoosing" value="3" id="sectionradio" /></div> <div style="float:left;padding-right:20px;">'.get_string('s_sectiontothissection', 'reader').'</div> <div id="loadersection" style="padding-left:20px;display:none;"><img src="img/zoomloader.gif" width="16" height="16" alt="" /></div> <div style="clear:both;"></div>');
                
                    $mform->addElement('select', 'section', '', $selectsectionform);
                
                }
                
                
        
                //Quizzes ID
                foreach ($quiz as $key => $value) {
                  if (isset($removequizzes) && is_array($removequizzes)) {
                    if (!in_array($value, $removequizzes)) {
                        $mform->addElement('hidden', 'quiz['.$key.']', $value);
                    }
                  }
                  else
                  {
                      $mform->addElement('hidden', 'quiz['.$key.']', $value);
                  }
                }
                
                
                //Passwords form
                if (!$checkincorrect) {
                  foreach ($publishers as $key => $value) {
                    foreach ($value as $key2 => $value2) {
                        if ($value2['pass'] == 'true') {
                            $mform->addElement('hidden', 'password['.$key.']['.$key2.']', $password[$key][$key2]);
                        }
                    }
                  }
                }
                
                
                $this->add_action_buttons($cancel = false, get_string('install_quizzes', 'reader'));
            }
        }
        
        $mform = new reader_uploadbooks_form('dlquizzes.php?id='.$id);

        $mform->display();
        */
        
        class reader_uploadbooks_form extends moodleform {
            function definition() {
                global $CFG,$DB,$course,$password,$publishers,$passprefix,$second,$quizzes,$removequizzes,$step,$id,$readercfg,$reader,$readercourseexist,$quiz,$OUTPUT;

                    $mform    =& $this->_form;
                    
                    echo $OUTPUT->box_start('generalbox');
                    echo get_string("quizzesmustbeinstalled", "reader");
                    echo $OUTPUT->box_end();
                    
                    $mform->addElement('header','general', get_string('select_course', 'reader')); 
                    
                    // ----------------- NEW CODE ------------//
                    
                    if ($readercfg->reader_usecourse) $puttocourse = $readercfg->reader_usecourse;
                    if ($reader->usecourse)     $puttocourse = $reader->usecourse;
                    
                    if (empty($puttocourse)) { 
                      $readercourseexist = $DB->get_record("course", array("shortname" => "Reader"));
                      $puttocourse = $readercourseexist->id;
                    }
                    
                    if (empty($puttocourse)) //$selectcourseform[0] = 'Create new course';
                      $selectcourseform[0] = 'Create new course';
                    
                    $courses = get_courses();
                    
                    foreach ($courses as $course) {
                      if ($course->id != 1)
                        $selectcourseform[$course->id] = $course->fullname;
                    }
                    
                    $mform->addElement('select', 'courseid', get_string('use_this_course', 'reader'), $selectcourseform);
                    
                    if (empty($puttocourse)) {
                      $mform->setDefault('courseid', 0);
                    } else {
                      $mform->setDefault('courseid', $puttocourse);
                    }
                    
                    if (empty($puttocourse)) $puttocourse = $course->id;
                    
                    $currentcoursesections = $DB->get_records("course_sections", array("course" => $puttocourse));
                    
                    $tocourse = $DB->get_record("course", array("id" => $puttocourse));
                    $t = 0;
                    $selectorsection = '
                    <select name="selectorsection">';
                    foreach ($currentcoursesections as $currentcoursesections_) {
                      if ($t <= $tocourse->numsections) {
                        $sectionname = strip_tags(trim(str_replace(array("\r", "\n"), "", $currentcoursesections_->summary)));
                        if (empty($sectionname)) $sectionname = 'Section '.$currentcoursesections_->section;
                        if (strlen($sectionname) > 25) $sectionname = substr($sectionname, 0, 25)."...";
                        $selectorsection .= '<option value="'.$currentcoursesections_->section.'">'.$sectionname;
                        $selectorsection .= '</option>';
                        $selectsectionform[$currentcoursesections_->section] = $sectionname;
                      }
                      $t++;
                    }
                    $selectorsection .= '</section>
                    ';
                    
                    //--------------- END -------------------------//
                    
                    
                    $mform->addElement('html', '<div style="clear:both;"></div><div style="padding:20px">
                <div><input type="radio" name="sectionchoosing" value="1"/> '.get_string('s_sectiontothebottom', 'reader').'</div>
                <div><input type="radio" name="sectionchoosing" value="2" checked="checked" /> '.get_string('s_sectiontoseparate', 'reader').'</div>
                <div><div style="float: left; width: 25px;"><input type="radio" name="sectionchoosing" value="3" id="sectionradio" /></div> <div style="float:left;padding-right:20px;">'.get_string('s_sectiontothissection', 'reader').'</div> <div id="loadersection" style="padding-left:20px;display:none;"><img src="img/zoomloader.gif" width="16" height="16" alt="" /></div> <div style="clear:both;"></div>');
                
                    $mform->addElement('select', 'section', '', $selectsectionform);
                
                
        
                //Quizzes ID
                foreach ($quiz as $key => $value) {
                  if (isset($removequizzes) && is_array($removequizzes)) {
                    if (!in_array($value, $removequizzes)) {
                        $mform->addElement('hidden', 'quiz['.$key.']', $value);
                    }
                  }
                  else
                  {
                      $mform->addElement('hidden', 'quiz['.$key.']', $value);
                  }
                }
                
                
                //Passwords form
                //if (!isset($checkincorrect)) {
                  foreach ($publishers as $key => $value) {
                    foreach ($value as $key2 => $value2) {
                        if ($value2['pass'] == 'true') {
                            $mform->addElement('hidden', 'password['.$key.']['.$key2.']', $password[$key][$key2]);
                        }
                    }
                  }
                //}
                
                
                $this->add_action_buttons($cancel = false, get_string('install_quizzes', 'reader'));
            }
        }
        
        $mform = new reader_uploadbooks_form('dlquizzes_proccess.php?id='.$id);

        $mform->display();
        
        

        
    }
    
    //print_r ($_REQUEST);
    
    echo $OUTPUT->box_end();
    
    if (!$quiz) {
        echo '<script type="text/javascript">
//<![CDATA[
var vh_numspans = '.$cp.';
collapseall();
//]]>
</script>'; //
    }
    
    echo '<script type="application/x-javascript" src="js/jquery-1.4.2.min.js"></script>
<script>
$(document).ready(function(){
  $("#id_courseid").change( function(){ 
    $("#loadersection").toggle();
    $.post("loadsectionoption.php?id=" + $(this).val(), function(data){
      $("#id_section").html(data);
      $("#loadersection").toggle();
    });
  });
  
  $("#id_section").click( function(){ 
    $("input[name=sectionchoosing]").attr("checked", true);
  });

});
</script>';
    
    echo $OUTPUT->footer();
