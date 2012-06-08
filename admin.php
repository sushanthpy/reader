<?php  // $Id:,v 1.0 2008/01/20 16:10:00 Serafim Panov

    require_once("../../config.php");
    require_once("lib.php");
    
    
//---------DEBUG&SPEED TEST------------S-//
reader_debug_speed_check(NULL);
//---------DEBUG&SPEED TEST------------E-//
    
    require_once($CFG->dirroot.'/course/moodleform_mod.php');
    require_once($CFG->dirroot.'/lib/excellib.class.php');
    require_once($CFG->libdir.'/tablelib.php');
    require_once($CFG->dirroot . '/question/editlib.php');
     
    $id                     = optional_param('id', 0, PARAM_INT); 
    $a                      = optional_param('a', NULL, PARAM_CLEAN); 
    $act                    = optional_param('act', NULL, PARAM_CLEAN); 
    $quizesid               = optional_param('quizesid', NULL, PARAM_CLEAN); 
    $publisher              = optional_param('publisher', NULL, PARAM_CLEAN); 
    $publisherex            = optional_param('publisherex', NULL, PARAM_CLEAN); 
    $difficulty             = optional_param('difficulty', NULL, PARAM_CLEAN); 
    $todifficulty           = optional_param('todifficulty', NULL, PARAM_CLEAN); 
    $difficultyex           = optional_param('difficultyex', NULL, PARAM_CLEAN); 
    $level                  = optional_param('level', NULL, PARAM_CLEAN); 
    $tolevel                = optional_param('tolevel', NULL, PARAM_CLEAN); 
    $topublisher            = optional_param('topublisher', NULL, PARAM_CLEAN); 
    $levelex                = optional_param('levelex', NULL, PARAM_CLEAN); 
    $length                 = optional_param('length', NULL, PARAM_CLEAN); 
    $tolength               = optional_param('tolength', NULL, PARAM_CLEAN); 
    $grid                   = optional_param('grid', NULL, PARAM_CLEAN); 
    $excel                  = optional_param('excel', NULL, PARAM_CLEAN); 
    $del                    = optional_param('del', NULL, PARAM_CLEAN); 
    $attemptid              = optional_param('attemptid', NULL, PARAM_CLEAN); 
    $restoreattemptid       = optional_param('restoreattemptid', NULL, PARAM_CLEAN); 
    $upassword              = optional_param('upassword', NULL, PARAM_CLEAN); 
    $groupid                = optional_param('groupid', 0, PARAM_INT); 
    $activehours            = optional_param('activehours', NULL, PARAM_CLEAN); 
    $text                   = optional_param('text', NULL, PARAM_CLEAN); 
    /* OLD LINES START **
    $bookid                 = optional_param('bookid', NULL, PARAM_CLEAN); 
    ** OLD LINES STOP **/
    // NEW LINES START
    if (function_exists('optional_param_array')) {
        $bookid             = optional_param_array('bookid', NULL, PARAM_CLEAN); 
    } else {
        $bookid             = optional_param('bookid', NULL, PARAM_CLEAN); 
    }
    // NEW LINES STOP
    $deletebook             = optional_param('deletebook', 0, PARAM_INT); 
    $deleteallattempts      = optional_param('deleteallattempts', 0, PARAM_INT); 
    $editmessage            = optional_param('editmessage', 0, PARAM_INT); 
    $deletemessage          = optional_param('deletemessage', 0, PARAM_INT); 
    $hidebooks              = optional_param('hidebooks', 0, PARAM_ALPHA); 
    $unhidebooks            = optional_param('unhidebooks', 0, PARAM_ALPHA); 
    $sort                   = optional_param('sort', 'username', PARAM_CLEAN); 
    $orderby                = optional_param('orderby', 'ASC', PARAM_CLEAN); 
    $dir                    = optional_param('dir', 'ASC', PARAM_ALPHA);
    $slevel                 = optional_param('slevel', 0, PARAM_ALPHA);
    $page                   = optional_param('page', 0, PARAM_INT);
    $perpage                = optional_param('perpage', 0, PARAM_INT);
    $userid                 = optional_param('userid', 0, PARAM_INT);
    $changelevel            = optional_param('changelevel', NULL, PARAM_CLEAN);
    $searchtext             = optional_param('searchtext', NULL, PARAM_CLEAN);
    $needip                 = optional_param('needip', NULL, PARAM_CLEAN);
    $setip                  = optional_param('setip', NULL, PARAM_CLEAN);
    $nopromote              = optional_param('nopromote', NULL, PARAM_CLEAN);
    $promotionstop          = optional_param('promotionstop', NULL, PARAM_CLEAN);
    $private                = optional_param('private', 0, PARAM_INT);
    $ajax                   = optional_param('ajax', NULL, PARAM_CLEAN);
    $changeallstartlevel    = optional_param('changeallstartlevel', -1, PARAM_INT);
    $changeallcurrentlevel  = optional_param('changeallcurrentlevel', -1, PARAM_INT);
    $changeallcurrentgoal   = optional_param('changeallcurrentgoal', NULL, PARAM_CLEAN);
    $changeallpromo         = optional_param('changeallpromo', NULL, PARAM_CLEAN);
    $changeallstoppromo     = optional_param('changeallstoppromo', -1, PARAM_INT);
    $userimagename          = optional_param('userimagename', NULL, PARAM_CLEAN);
    $award                  = optional_param('award', NULL, PARAM_CLEAN);
    $student                = optional_param('student', NULL, PARAM_CLEAN);
    $useonlythiscourse      = optional_param('useonlythiscourse', NULL, PARAM_CLEAN);
    $ipmask                 = optional_param('ipmask', 3, PARAM_CLEAN);
    $fromtime               = optional_param('fromtime', 86400, PARAM_CLEAN);
    $maxtime                = optional_param('maxtime', 1800, PARAM_CLEAN);
    $cheated                = optional_param('cheated', NULL, PARAM_CLEAN);
    $uncheated              = optional_param('uncheated', NULL, PARAM_CLEAN);
    $findcheated            = optional_param('findcheated', NULL, PARAM_CLEAN);
    $separategroups         = optional_param('separategroups', NULL, PARAM_CLEAN);
    $levelall               = optional_param('levelall', NULL, PARAM_CLEAN);
    $levelc                 = optional_param('levelc', NULL, PARAM_CLEAN);
    $wordsorpoints          = optional_param('wordsorpoints', NULL, PARAM_CLEAN);
    $setgoal                = optional_param('setgoal', NULL, PARAM_CLEAN);
    $wordscount             = optional_param('wordscount', NULL, PARAM_CLEAN);
    $viewasstudent          = optional_param('viewasstudent', NULL, PARAM_CLEAN);
    $booksratingbest        = optional_param('booksratingbest', NULL, PARAM_CLEAN);
    $booksratinglevel       = optional_param('booksratinglevel', NULL, PARAM_CLEAN);
    //$booksratinglevel       = optional_param('booksratinglevel');
    $booksratingterm        = optional_param('booksratingterm', NULL, PARAM_CLEAN);
    $booksratingwithratings = optional_param('booksratingwithratings', NULL, PARAM_CLEAN);
    $booksratingshow        = optional_param('booksratingshow', NULL, PARAM_CLEAN);
    /* OLD LINES START **
    $quiz                   = optional_param('quiz', NULL, PARAM_CLEAN);
    ** OLD LINES STOP **/
    // NEW LINES START
    if (function_exists('optional_param_array')) {
        $quiz               = optional_param_array('quiz', NULL, PARAM_CLEAN); 
    } else {
        $quiz               = optional_param('quiz', NULL, PARAM_CLEAN); 
    }
    // NEW LINES STOP
    $sametitlekey           = optional_param('sametitlekey', NULL, PARAM_CLEAN);
    $sametitleid            = optional_param('sametitleid', NULL, PARAM_CLEAN);
    $wordstitlekey          = optional_param('wordstitlekey', NULL, PARAM_CLEAN);
    $wordstitleid           = optional_param('wordstitleid', NULL, PARAM_CLEAN);
    $leveltitlekey          = optional_param('leveltitlekey', NULL, PARAM_CLEAN);
    $leveltitleid           = optional_param('leveltitleid', NULL, PARAM_CLEAN);
    $publishertitlekey      = optional_param('publishertitlekey', NULL, PARAM_CLEAN);
    $publishertitleid       = optional_param('publishertitleid', NULL, PARAM_CLEAN);
    $checkattempt           = optional_param('checkattempt', NULL, PARAM_CLEAN);
    $checkattemptvalue      = optional_param('checkattemptvalue', 0, PARAM_INT); 
    $book                   = optional_param('book', 0, PARAM_INT); 
    $noquizuserid           = optional_param('noquizuserid', NULL, PARAM_CLEAN); 
    $withoutdayfilter       = optional_param('withoutdayfilter', NULL, PARAM_CLEAN); 
    $numberofsections       = optional_param('numberofsections', NULL, PARAM_CLEAN); 
    $ct                     = optional_param('ct', NULL, PARAM_CLEAN); 
    $adjustscoresupbooks    = optional_param('adjustscoresupbooks', NULL, PARAM_CLEAN); 
    $adjustscoresaddpoints  = optional_param('adjustscoresaddpoints', NULL, PARAM_CLEAN); 
    $adjustscoresupall      = optional_param('adjustscoresupall', NULL, PARAM_CLEAN); 
    $adjustscorespand       = optional_param('adjustscorespand', NULL, PARAM_CLEAN); 
    $adjustscorespby        = optional_param('adjustscorespby', NULL, PARAM_CLEAN); 
    $sctionoption           = optional_param('sctionoption', NULL, PARAM_CLEAN); 
    $studentuserid          = optional_param('studentuserid', 0, PARAM_INT); 
    $studentusername        = optional_param('studentusername', NULL, PARAM_CLEAN); 
    $bookquiznumber         = optional_param('bookquiznumber', 0, PARAM_INT); 
    
    $readercfg = get_config('reader');
    
    //--------From student view to teacher-------//
    if (isset($USER->realuser)) {
      $USER = get_complete_user_data('id', $USER->realuser);
    }
    
    if ((isset($_SESSION['SESSION']->reader_page) && $_SESSION['SESSION']->reader_page == 'view') || (isset($_SESSION['SESSION']->reader_lasttime) && $_SESSION['SESSION']->reader_lasttime < (time() - 300))) {
        unset ($_SESSION['SESSION']->reader_page);
        unset ($_SESSION['SESSION']->reader_lasttime);
        unset ($_SESSION['SESSION']->reader_lastuser);
        unset ($_SESSION['SESSION']->reader_lastuserfrom);
    }

    if (($ct || $ct == 0) && $ct != "") $_SESSION['SESSION']->reader_values_ct = $ct; else if (isset($_SESSION['SESSION']->reader_values_ct)) $ct = $_SESSION['SESSION']->reader_values_ct;
    //if (!isset($ct)) {$ct = $_SESSION['SESSION']->reader_values_ct; echo "SET!";} else {$_SESSION['SESSION']->reader_values_ct = $ct; echo "USED!";}
    //if (!isset($grid)) $grid = $_SESSION['SESSION']->reader_values_grid; else $_SESSION['SESSION']->reader_values_grid = $grid;
    
    //echo $ct."!";
    if (($grid || $grid == 0) && $grid != "") $_SESSION['SESSION']->reader_values_grid = $grid; else if (isset($_SESSION['SESSION']->reader_values_grid)) $grid = $_SESSION['SESSION']->reader_values_grid;
    //-------------------------------------------//
    
//---------DEBUG&SPEED TEST------------S-//
reader_debug_speed_check("page call");
//---------DEBUG&SPEED TEST------------E-//
    
    $row = 3; // Start row for Excel file
    
    if (!empty($searchtext)) {
        $perpage = 1000;
    } else if (isset($_SESSION['SESSION']->reader_perpage) && $_SESSION['SESSION']->reader_perpage == 1000) {
        $_SESSION['SESSION']->reader_perpage = 30;
    }
    
    if (!isset($_SESSION['SESSION']->reader_perpage)) {
        if (!$perpage) {
            $_SESSION['SESSION']->reader_perpage = 30;
        }
        else
        {
            $_SESSION['SESSION']->reader_perpage = $perpage;
        }
    }
    
    if (!$perpage) {
        $perpage = $_SESSION['SESSION']->reader_perpage;
    }
    else
    {
        $_SESSION['SESSION']->reader_perpage = $perpage;
    }
    


    if ($id) {
        if (! $cm = get_coursemodule_from_id('reader', $id)) {
            error("Course Module ID was incorrect");
        }
        if (! $course = $DB->get_record("course", array("id"=>$cm->course))) {
            error("Course is misconfigured");
        }
        if (! $reader = $DB->get_record("reader", array("id"=> $cm->instance))) {
            error("Course module is incorrect");
        }
    } else {
        if (! $reader = $DB->get_record("reader", array("id"=> $a))) {
            error("Course module is incorrect");
        }
        if (! $course = $DB->get_record("course", array("id"=> $reader->course))) {
            error("Course is misconfigured");
        }
        if (! $cm = get_coursemodule_from_instance("reader", $reader->id, $course->id)) {
            error("Course Module ID was incorrect");
        }
    }

    require_login($course->id);

    add_to_log($course->id, "reader", "admin area", "admin.php?id=$id", "$cm->instance");


    //FullReports ignor date fix----//
    if ($act == "fullreports" && $ct == 0) $reader->ignordate = 0;
    //------------------------------//

    $context = get_context_instance(CONTEXT_COURSE, $course->id);
    $contextmodule = get_context_instance(CONTEXT_MODULE, $cm->id);

    if (has_capability('mod/reader:manage', $contextmodule) && $quizesid) {
        if (empty($publisher) && ($publisherex == '0')) {
            error ("Please choose publisher", "admin.php?a=admin&id=".$id."&act=addquiz");
        }
        else if (!isset($difficulty) && $difficulty != 0 && $difficultyex != 0 && !$difficultyex) {
            error ("Please choose Reading Level", "admin.php?a=admin&id=".$id."&act=addquiz");
        }
        else if (!isset($level) && ($levelex == '0')) {
            error ("Please choose level", "admin.php?a=admin&id=".$id."&act=addquiz");
        }
        
        if ($_FILES['userimage']) {
            if (is_uploaded_file($_FILES['userimage']['tmp_name'])) {
                $ext = substr($_FILES['userimage']['name'], 1 + strrpos($_FILES['userimage']['name'], "."));
                if (in_array(strtolower($ext), array("jpg", "jpeg", "gif"))) {
                    if (! make_upload_directory($cm->course.'/images')) {
                        //return false;
                    }
                    if (file_exists ($CFG->dataroot."/".$cm->course."/images/".$_FILES['userimage']['name'])) {
                        list ($imgname, $imgtype) = explode (".", $_FILES['userimage']['name']);
                        $newimagename = $imgname.rand (9999,9999999);
                        $newimagename .= ".".$ext;
                        $newimagename = strtolower($newimagename);
                    }
                    else
                    {
                        $newimagename = $_FILES['userimage']['name'];
                    }
                    @move_uploaded_file($_FILES['userimage']['tmp_name'], $CFG->dataroot."/".$cm->course."/images/".$newimagename);
                }
            }
        }
        
        if ($userimagename) {
            $newimagename = $userimagename;
        }
        
        foreach ($quizesid as $quizesid_) {
            $newquiz = new object;
            if (!$publisher) {
                $newquiz->publisher = $publisherex;
            }
            else
            {
                $newquiz->publisher = $publisher;
            }
            
            if (!isset($difficulty) && $difficulty != 0) {
                $newquiz->difficulty = $difficultyex;
            }
            else
            {
                $newquiz->difficulty = $difficulty;
            }
            
            if (!isset($level)) {
                $newquiz->level = $levelex;
            }
            else
            {
                $newquiz->level = $level;
            }
            
            if ($length) {
                $newquiz->length = $length;
            }
            
            if ($newimagename) {
                $newquiz->image = $newimagename;
            }
            
            if ($wordscount) {
                $newquiz->words = $wordscount;
            }
            
            $quizdata = $DB->get_record("quiz", array( "id" => $quizesid_));
            
            //$newquiz->name = addslashes($quizdata->name);
            $newquiz->name = $quizdata->name;
            
            $newquiz->quizid = $quizesid_;
            $newquiz->private = $private;
            
            $DB->insert_record ("reader_publisher", $newquiz);
        }
        
        $message_forteacher = '<center><h3>'.get_string("quizzesadded", "reader").'</h3></center><br /><br />';
        
        add_to_log($course->id, "reader", "AA-Quizzes Added", "admin.php?id=$id", "$cm->instance");
    }
    
    if (has_capability('mod/reader:deletereaderattempts', $contextmodule) && $act == "viewattempts" && $attemptid) {
        //if (authenticate_user_login($USER->username, $upassword)) {
            $attemptdata = $DB->get_record("reader_attempts", array( "id" => $attemptid));
            unset($attemptdata->id);
            $DB->insert_record ("reader_deleted_attempts", $attemptdata);
            $DB->delete_records ("reader_attempts", array("id" => $attemptid));
            add_to_log($course->id, "reader", "AA-reader_deleted_attempts", "admin.php?id=$id", "$cm->instance");
        //}
    }
    
    if (has_capability('mod/reader:deletereaderattempts', $contextmodule) && $act == "viewattempts" && $bookquiznumber) {
        if (empty($studentuserid)) {
          $data = $DB->get_record("user", array("username" => $studentusername));
          $studentuserid = $data->id;
        }
        
        if (!empty($studentuserid)) {
          $attemptdata = $DB->get_record("reader_deleted_attempts", array( "userid" => $studentuserid, "quizid" => $bookquiznumber));
          unset($attemptdata->id);
          $DB->insert_record ("reader_attempts", $attemptdata);
          $DB->delete_records ("reader_deleted_attempts", array( "userid" => $studentuserid, "quizid" => $bookquiznumber));
          add_to_log($course->id, "reader", "AA-reader_restore_attempts", "admin.php?id=$id", "$cm->instance");
        }
    }
    
    if (has_capability('mod/reader:manage', $contextmodule) && $text && $activehours) {
        $message = new object;
        
        foreach ($groupid as $groupkey => $groupvalue) {
            $message->users .= $groupvalue.',';
        }
        
        $message->users = substr($message->users,0,-1);

        $message->instance = $cm->instance;
        $message->teacherid = $USER->id;
        $message->text = $text;
        $message->timebefore = time() + ($activehours * 60 * 60);
        $message->timemodified = time();
        
        if ($editmessage) {
            $message->id = $editmessage;
            $DB->update_record("reader_messages", $message);
        }
        else
        {
            $DB->insert_record("reader_messages", $message);
        }
        
        add_to_log($course->id, "reader", "AA-Message Added", "admin.php?id=$id", "$cm->instance");
    }
    
    if (has_capability('mod/reader:manage', $contextmodule) && $deletemessage) {
        $DB->delete_records("reader_messages", array("id" => $deletemessage));
        
        add_to_log($course->id, "reader", "AA-Message Deleted", "admin.php?id=$id", "$cm->instance");
    }
    
    if (has_capability('mod/reader:manage', $contextmodule) && $checkattempt && $ajax == 'true') {
        $DB->set_field("reader_attempts",  "checkbox",  $checkattemptvalue, array( "id" => $checkattempt));
        die();
    }
    
    //-------------Hide UnHide Books-------------//
    if (has_capability('mod/reader:manage', $contextmodule) && $bookid) {
        if ($hidebooks) {
            foreach ($bookid as $bookidkey => $bookidvalue) {
                $DB->set_field("reader_publisher",  "hidden",  1, array( "id" => $bookidkey));
            }
        }
        if ($unhidebooks) {
            foreach ($bookid as $bookidkey => $bookidvalue) {
                $DB->set_field("reader_publisher",  "hidden",  0, array( "id" => $bookidkey));
            }
        }
        
        add_to_log($course->id, "reader", "AA-Books status changed", "admin.php?id=$id", "$cm->instance");
    }
    
    //---Delete Attempts---------------//
    
    if (has_capability('mod/reader:manage', $contextmodule) && $deletebook && $deleteallattempts) {
        $DB->delete_records("reader_attempts", array("quizid" => $deletebook, "reader" => $reader->id));
        
        add_to_log($course->id, "reader", "AA-Attempts Deleted", "admin.php?id=$id", "$cm->instance");
    }
    
    //---Delete Books---------------//
    if (has_capability('mod/reader:manage', $contextmodule) && $deletebook) {
        if ($DB->count_records("reader_attempts", array("quizid" => $deletebook, "reader" => $reader->id)) == 0) {
            $DB->delete_records("reader_publisher", array("id" => $deletebook));
        }
        else
        {
            $needdeleteattemptsfirst = $DB->get_records_sql("SELECT * FROM {reader_attempts} WHERE quizid= ?  and reader= ?  ORDER BY timefinish", array($deletebook,$reader->id));
        }
        add_to_log($course->id, "reader", "AA-Book Deleted", "admin.php?id=$id", "$cm->instance");
    }
    
    //----Set sametitlekey to books---------//
    
    if (has_capability('mod/reader:manage', $contextmodule) && $ajax == "true" && isset($sametitlekey)) {
        $DB->set_field("reader_publisher",  'sametitle',  $sametitlekey, array( "id" => $sametitleid));
        echo $sametitlekey;
        die();
    }
    
    //----Set wordstitlekey to books---------//
    
    if (has_capability('mod/reader:manage', $contextmodule) && $ajax == "true" && isset($wordstitlekey)) {
        $DB->set_field("reader_publisher",  'words',  $wordstitlekey, array( "id" => $wordstitleid));
        echo $wordstitlekey;
        die();
    }
    
    //----Set publishertitlekey to books---------//
    
    if (has_capability('mod/reader:manage', $contextmodule) && $ajax == "true" && isset($publishertitlekey)) {
        $DB->set_field("reader_publisher",  'publisher',  $publishertitlekey, array( "id" => $publishertitleid));
        echo $publishertitlekey;
        die();
    }
    
    //----Set leveltitlekey to books---------//
    
    if (has_capability('mod/reader:manage', $contextmodule) && $ajax == "true" && isset($leveltitlekey)) {
        $DB->set_field("reader_publisher",  'level',  $leveltitlekey, array( "id" => $leveltitleid));
        echo $leveltitlekey;
        die();
    }
    
    //---Change Student Level---------------//
    if (has_capability('mod/reader:manage', $contextmodule) && ($changelevel || $changelevel == 0) && $slevel) {
        if ($DB->get_record("reader_levels", array("userid" => $userid, "readerid" => $reader->id))) {
            $DB->set_field("reader_levels",  $slevel,  $changelevel, array( "userid" => $userid,  "readerid" => $reader->id));
            $DB->set_field("reader_levels", 'time', time(), array("userid" => $userid, "readerid" => $reader->id));
        }
        else
        {
            $data = new object;
            $data->userid = $userid;
            $data->startlevel = $changelevel;
            $data->currentlevel = $changelevel;
            $data->readerid = $reader->id;
            $data->time = time();
            
            $DB->insert_record("reader_levels", $data);
        }
        add_to_log($course->id, "reader", substr("AA-Student Level Changed ({$userid} {$slevel} to {$changelevel})", 0, 39), "admin.php?id=$id", "$cm->instance");
        if ($ajax == "true") {
            $studentlevel = $DB->get_record("reader_levels", array("userid" => $userid,  "readerid" => $reader->id));
            echo reader_selectlevel_form ($userid, $studentlevel, $slevel);
            //echo "set {$changelevel}";
            die();
        }
    }
    
    if (has_capability('mod/reader:manage', $contextmodule) && $sctionoption == "massdifficultychange" && (isset($difficulty) || $difficulty == 0) && (isset($todifficulty) || $todifficulty == 0) && isset($publisher)) {
        if ($reader->individualbooks == 0) {
            $DB->set_field("reader_publisher",  'difficulty',  $todifficulty, array( "difficulty" => $difficulty,  "publisher" => $publisher));
        }
        else
        {
            $data = $DB->get_records("reader_publisher", array("publisher" => $publisher));
            foreach ($data as $key => $value) {
                $difficultystring .= $value->id.",";
            }
            $difficultystring = substr($difficultystring, 0, -1);
            $DB->execute("UPDATE {reader_individual_books} SET difficulty = ? WHERE difficulty = ? and readerid = ? and bookid IN (?)", array($todifficulty,$difficulty,$reader->id,$difficultystring)); 
        }
        add_to_log($course->id, "reader", substr("AA-Mass changes difficulty ({$difficulty} to {$todifficulty})", 0, 39), "admin.php?id=$id", "$cm->instance");
    }
    
    if (has_capability('mod/reader:manage', $contextmodule) && $level && $tolevel && $publisher) {
        $DB->set_field("reader_publisher",  'level',  $tolevel, array( "level" => $level,  "publisher" => $publisher));
        add_to_log($course->id, "reader", substr("AA-Mass changes level ({$level} to {$tolevel})", 0, 39), "admin.php?id=$id", "$cm->instance");
    }
    
    if (has_capability('mod/reader:manage', $contextmodule) && $topublisher && $publisher) {
        $DB->set_field("reader_publisher",  'publisher',  $topublisher, array( "publisher" => $publisher));
        add_to_log($course->id, "reader", substr("AA-Mass changes publisher ({$publisher} to {$topublisher})", 0, 39), "admin.php?id=$id", "$cm->instance");
    }
    
    if (has_capability('mod/reader:manage', $contextmodule) && $length && $tolength && $publisher) {
        if ($reader->individualbooks == 0) {
            $DB->set_field("reader_publisher",  'length',  $tolength, array( "length" => $length,  "publisher" => $publisher));
        }
        else
        {
            $data = $DB->get_records("reader_publisher", array("publisher" => $publisher));
            foreach ($data as $key => $value) {
                $lengthstring .= $value->id.",";
            }
            $lengthstring = substr($lengthstring, 0, -1);
            $DB->execute("UPDATE {reader_individual_books} SET length = ? WHERE length = ? and readerid = ? and bookid IN (?)", array($tolength,$length,$reader->id,$lengthstring)); 
        }
        add_to_log($course->id, "reader", substr("AA-Mass changes length ({$length} to {$tolength})", 0, 39), "admin.php?id=$id", "$cm->instance");
    }
    //------------------------------//
    
    //---Change difficulty---------------//
    if (has_capability('mod/reader:manage', $contextmodule) && $act == "changereaderlevel" && ($difficulty || $difficulty == 0) && empty($length)) {
      if ($reader->individualbooks == 0) {
        if ($DB->get_record("reader_publisher", array("id" => $bookid))) {
            $DB->set_field("reader_publisher",  "difficulty",  $difficulty, array( "id" => $bookid));
        }
      }
      else
      {
        if ($DB->get_record("reader_individual_books", array("readerid" => $reader->id, "bookid" => $bookid))) {
            $DB->set_field("reader_individual_books",  "difficulty",  $difficulty, array( "readerid" => $reader->id,  "bookid" => $bookid));
        }

        add_to_log($course->id, "reader", substr("AA-Change difficulty individual ({$bookid} {$slevel} to {$difficulty})",0,39), "admin.php?id=$id", "$cm->instance");
      }
      if ($ajax == "true") {
          $book = $DB->get_record("reader_publisher", array("id" => $bookid));
          echo reader_select_difficulty_form (reader_get_reader_difficulty($reader, $bookid), $book->id, $reader);
          die();
      }
    }
    //------------------------------//
    
    //---Change length---------------//
    if (has_capability('mod/reader:manage', $contextmodule) && $act == "changereaderlevel" && $length) {
      if ($reader->individualbooks == 0) {
        if ($DB->get_record("reader_publisher", array("id" => $bookid))) {
            $DB->set_field("reader_publisher",  "length",  $length, array( "id" => $bookid));
        }
        add_to_log($course->id, "reader", substr("AA-Change length ({$bookid} {$slevel} to {$length})",0,39), "admin.php?id=$id", "$cm->instance");
      }
      else
      {
        if ($DB->get_record("reader_individual_books", array("readerid" => $reader->id, "bookid" => $bookid))) {
            $DB->set_field("reader_individual_books",  "length",  $length, array( "readerid" => $reader->id,  "bookid" => $bookid));
        }
        add_to_log($course->id, "reader", substr("AA-Change length individual ({$bookid} {$slevel} to {$length})",0,39), "admin.php?id=$id", "$cm->instance");
      }
      if ($ajax == "true") {
          $book = $DB->get_record("reader_publisher", array("id" => $bookid));
          echo reader_select_length_form (reader_get_reader_length($reader, $bookid), $book->id, $reader);
          die();
      }
    }
    //------------------------------//
    
    //---View as student---------------//
    if (has_capability('mod/reader:viewstudentreaderscreens', $contextmodule) && $viewasstudent > 1) {
      $systemcontext = get_context_instance(CONTEXT_SYSTEM);
      $coursecontext = get_context_instance(CONTEXT_COURSE, $course->id);
      
      if (has_capability('moodle/user:loginas', $systemcontext)) {
          if (is_siteadmin($userid)) {
              print_error('nologinas');
          }
          $context = $systemcontext;
      } else {
          require_login($course);
          require_capability('moodle/user:loginas', $coursecontext);
          if (is_siteadmin($userid)) {
              print_error('nologinas');
          }
          if (!is_enrolled($coursecontext, $userid)) {
              print_error('usernotincourse');
          }
          $context = $coursecontext;
      }
  /// Login as this user and return to course home page.

      $oldfullname = fullname($USER, true);
      $olduserid   = $USER->id;
      session_loginas($viewasstudent, $context);
      $newfullname = fullname($USER, true);

      add_to_log($course->id, "course", "loginas", "../user/view.php?id=$course->id&amp;user=$userid", "$oldfullname -> $newfullname");
      header("Location: view.php?a=quizes&id=".$id);
    }
    //------------------------------//
    
    //---Change Set goal---------------//
    if (has_capability('mod/reader:manage', $contextmodule) && $act == "studentslevels" && $setgoal) {
        if ($data = $DB->get_record("reader_levels", array("userid" => $userid, "readerid" => $reader->id))) {
            $DB->set_field("reader_levels",  "goal",  $setgoal, array( "id" => $data->id));
        }
        else
        {
            $data = new object;
            $data->userid = $userid;
            $data->startlevel = 0;
            $data->currentlevel = 0;
            $data->readerid = $reader->id;
            $data->goal = $setgoal;
            $data->time = time();
            
            $DB->insert_record("reader_levels", $data);
        }
        add_to_log($course->id, "reader", "AA-Change Student Goal ({$setgoal})", "admin.php?id=$id", "$cm->instance");
        if ($ajax == "true") {
            $data = $DB->get_record("reader_levels", array("id" => $data->id));
            echo reader_goal_box ($userid, $data, 'goal', 3, $reader);
            die();
        }
    }
    //------------------------------//
    
    
    //---Change NoPromote---------------//
    if (has_capability('mod/reader:manage', $contextmodule) && isset($nopromote) && $userid) {
        if ($DB->get_record("reader_levels", array("userid" => $userid, "readerid" => $reader->id))) {
            $DB->set_field("reader_levels",  'nopromote',  $nopromote, array( "userid" => $userid,  "readerid" => $reader->id));
        }
        add_to_log($course->id, "reader", substr("AA-Student NoPromote Changed ({$userid} set to {$nopromote})",0,39), "admin.php?id=$id", "$cm->instance");
        if ($ajax == "true") {
            $studentlevel = $DB->get_record("reader_levels", array("userid" => $userid,  "readerid" => $reader->id));
            echo reader_yes_no_box ($userid, $studentlevel, 'nopromote', 1);
            die();
        }
    }
    //------------------------------//
    
    //---Change Promotion Stop---------------//
    if (has_capability('mod/reader:manage', $contextmodule) && isset($promotionstop) && $userid) {
        if ($DB->get_record("reader_levels", array("userid" => $userid, "readerid" => $reader->id))) {
            $DB->set_field("reader_levels",  'promotionstop',  $promotionstop, array( "userid" => $userid,  "readerid" => $reader->id));
        }
        add_to_log($course->id, "reader", substr("AA-Student Promotion Stop Changed ({$userid} set to {$promotionstop})",0,39), "admin.php?id=$id", "$cm->instance");
        if ($ajax == "true") {
            //echo "set {$promotionstop}";
            $studentlevel = $DB->get_record("reader_levels", array("userid" => $userid,  "readerid" => $reader->id));
            echo reader_promotion_stop_box ($userid, $studentlevel, 'promotionstop', 2);
            die();
        }
    }
    //------------------------------//
    
    //----------If need to set individual strict users acsess-----//
    if (has_capability('mod/reader:manage', $contextmodule) && $setip) {
        if ($DB->get_record("reader_strict_users_list", array("userid" => $userid, "readerid" => $reader->id))) {
            $DB->set_field("reader_strict_users_list",  "needtocheckip",  $needip, array( "userid" => $userid,  "readerid" => $reader->id));
        }
        else
        {
            $data = new object;
            $data->userid = $userid;
            $data->readerid = $reader->id;
            $data->needtocheckip = $needip;
            
            $DB->insert_record("reader_strict_users_list", $data);
        }
        add_to_log($course->id, "reader", substr("AA-Student check ip Changed ({$userid} {$needip})",0,39), "admin.php?id=$id", "$cm->instance");
        if ($ajax == "true") {
            echo reader_selectip_form ($userid, $reader);
            die();
        }
    }
    //------------------------------------------------------------//

    //---------Mass Change Levels----------//
    if (has_capability('mod/reader:manage', $contextmodule) && $changeallstartlevel >= 0) {
        $coursestudents = get_enrolled_users($context, NULL, $grid);
        foreach ($coursestudents as $coursestudent) {
            if ($DB->get_record("reader_levels", array("userid" => $coursestudent->id, "readerid" => $reader->id))) {
                $DB->set_field("reader_levels",  "startlevel",  $changeallstartlevel, array( "userid" => $coursestudent->id,  "readerid" => $reader->id));
            }
            else
            {
                $data = new object;
                $data->startlevel = $changeallstartlevel;
                $data->currentlevel = $changeallstartlevel;
                $data->userid = $coursestudent->id;
                $data->readerid = $reader->id;
                $data->time = time();
                
                $DB->insert_record("reader_levels", $data);
            }
            add_to_log($course->id, "reader", substr("AA-changeallstartlevel userid: {$coursestudent->id}, startlevel={$changeallstartlevel}",0,39), "admin.php?id=$id", "$cm->instance");
        }
    }
    
    if (has_capability('mod/reader:manage', $contextmodule) &&  $changeallcurrentlevel >= 0) {
        $coursestudents = get_enrolled_users($context, NULL, $grid);
        foreach ($coursestudents as $coursestudent) {
            if ($DB->get_record("reader_levels", array("userid" => $coursestudent->id, "readerid" => $reader->id))) {
                $DB->set_field("reader_levels",  "currentlevel",  $changeallcurrentlevel, array( "userid" => $coursestudent->id,  "readerid" => $reader->id));
            }
            else
            {
                $data = new object;
                $data->startlevel = $changeallcurrentlevel;
                $data->currentlevel = $changeallcurrentlevel;
                $data->userid = $coursestudent->id;
                $data->readerid = $reader->id;
                $data->time = time();
                
                $DB->insert_record("reader_levels", $data);
            }
            add_to_log($course->id, "reader", substr("AA-changeallcurrentlevel userid: {$coursestudent->id}, currentlevel={$changeallcurrentlevel}",0,39), "admin.php?id=$id", "$cm->instance");
        }
    }
    //-------------------------------------//
    

    //---------Mass Change Promo----------//
    if (has_capability('mod/reader:manage', $contextmodule) && $changeallpromo) {
        $coursestudents = get_enrolled_users($context, NULL, $grid);
        foreach ($coursestudents as $coursestudent) {
            if ($DB->get_record("reader_levels", array("userid" => $coursestudent->id, "readerid" => $reader->id))) {
                if (strtolower($changeallpromo) == "promo") {$nopromote = 0;} else {$nopromote = 1;} 
                $DB->set_field("reader_levels",  'nopromote',  $nopromote, array( "userid" => $coursestudent->id,  "readerid" => $reader->id));
            }
            add_to_log($course->id, "reader", substr("AA-Student Promotion Stop Changed ({$coursestudent->id} set to {$promotionstop})",0,39), "admin.php?id=$id", "$cm->instance");
        }
    }
    
    if (has_capability('mod/reader:manage', $contextmodule) && $changeallstoppromo >= 0 && $grid) {
        $coursestudents = get_enrolled_users($context, NULL, $grid);
        foreach ($coursestudents as $coursestudent) {
            if ($DB->get_record("reader_levels", array("userid" => $coursestudent->id, "readerid" => $reader->id))) {
                $DB->set_field("reader_levels",  'promotionstop',  $changeallstoppromo, array( "userid" => $coursestudent->id,  "readerid" => $reader->id));
            }
            add_to_log($course->id, "reader", substr("AA-Student NoPromote Changed ({$coursestudent->id} set to {$changeallstoppromo})",0,39), "admin.php?id=$id", "$cm->instance");
        }
    }
    //-------------------------------------//
    
    //---------Mass Change Goal----------//
    if (has_capability('mod/reader:manage', $contextmodule) && $changeallcurrentgoal) {
        $coursestudents = get_enrolled_users($context, NULL, $grid);
        foreach ($coursestudents as $coursestudent) {
            if ($data = $DB->get_record("reader_levels", array("userid" => $coursestudent->id, "readerid" => $reader->id))) {
                $DB->set_field("reader_levels",  "goal",  $changeallcurrentgoal, array( "id" => $data->id));
            }
            else
            {
                $data = new object;
                $data->userid = $coursestudent->id;
                $data->startlevel = 0;
                $data->currentlevel = 0;
                $data->readerid = $reader->id;
                $data->goal = $changeallcurrentgoal;
                $data->time = time();
                
                $DB->insert_record("reader_levels", $data);
            }
            add_to_log($course->id, "reader", substr("AA-goal userid: {$coursestudent->id}, goal={$changeallcurrentgoal}",0,39), "admin.php?id=$id", "$cm->instance");
        }
    }
    
    //---------Award Extra Points----------//
    if (has_capability('mod/reader:manage', $contextmodule) && $act == "awardextrapoints" && $award && $student) {
        $useridold = $USER->id;
        if ($bookdata = $DB->get_record("reader_publisher", array("name" => $award))) {
            foreach ($student as $student_) {
                if(!$attemptnumber = (int)$DB->get_field_sql('SELECT MAX(attempt)+1 FROM ' .
                        "{reader_attempts} WHERE reader = ? AND " .
                        "userid = ? AND timefinish > 0 AND preview != 1", array($reader->id, $student_))) {
                    $attemptnumber = 1;
                }
                
                $USER->id = $student_;
                
                $attempt = reader_create_attempt($reader, $attemptnumber, $bookdata->id);
                $attempt->ip = getremoteaddr();
                // Save the attempt
                if (!$attempt->id = $DB->insert_record('reader_attempts', $attempt)) {
                    error('Could not create new attempt');
                }
                
                $totalgrade = 0;
                $answersgrade = $DB->get_records ("reader_question_instances", array("quiz" => $bookdata->id)); // Count Grades (TotalGrade)
                foreach ($answersgrade as $answersgrade_) {
                    $totalgrade += $answersgrade_->grade;
                }
                
                $attemptnew               = new object;
                $attemptnew->id           = $attempt->id;
                $attemptnew->sumgrades    = $totalgrade;
                $attemptnew->persent      = 100;
                $attemptnew->passed       = "true";
                
                //if ($reader->attemptsofday != 0) {
                    $attemptnew->timefinish   = time() - $reader->attemptsofday * 3600 * 24;
                    $attemptnew->timecreated  = time() - $reader->attemptsofday * 3600 * 24;
                    $attemptnew->timemodified = time() - $reader->attemptsofday * 3600 * 24;
                //}
                
                $DB->update_record("reader_attempts", $attemptnew);
                add_to_log($course->id, "reader", "AWP (userid: {$student_}; set: {$award})", "admin.php?id=$id", "$cm->instance");
            }
        }
        $USER->id = $useridold;
    }
    //-------------------------------------//
    
    //---------cheated---------------------//
    if (has_capability('mod/reader:manage', $contextmodule) && $cheated) {
        list($cheated1, $cheated2) = explode("_", $cheated);
        $DB->set_field("reader_attempts",  "passed",  "cheated", array( "id" => $cheated1));
        $DB->set_field("reader_attempts",  "passed",  "cheated", array( "id" => $cheated2));
        add_to_log($course->id, "reader", "AA-cheated", "admin.php?id=$id", "$cm->instance");
        
        $userid1 = $DB->get_record("reader_attempts", array("id" => $cheated1));
        $userid2 = $DB->get_record("reader_attempts", array("id" => $cheated2));
        
        $data = new object;
        $data->byuserid  = $USER->id;
        $data->userid1   = $userid1->userid;
        $data->userid2   = $userid2->userid;
        $data->attempt1  = $cheated1;
        $data->attempt2  = $cheated2;
        $data->courseid  = $course->id;
        $data->readerid  = $reader->id;
        $data->quizid    = $userid1->quizid;
        $data->status    = "cheated";
        $data->date      = time();
        
        //print_r ($data);

        $DB->insert_record("reader_cheated_log", $data);
        
        if ($reader->sendmessagesaboutcheating == 1) {
            $user1 = $DB->get_record("user", array("id" => $userid1->userid));
            $user2 = $DB->get_record("user", array("id" => $userid2->userid));
            email_to_user($user1,get_admin(),"Cheated notice",$reader->cheated_message);
            email_to_user($user2,get_admin(),"Cheated notice",$reader->cheated_message);
        }
    }
    //-------------------------------------//
    
    //---------uncheated-------------------//
    if (has_capability('mod/reader:manage', $contextmodule) && $uncheated) {
        list($cheated1, $cheated2) = explode("_", $uncheated);
        $DB->set_field("reader_attempts",  "passed",  "true", array( "id" => $cheated1));
        $DB->set_field("reader_attempts",  "passed",  "true", array( "id" => $cheated2));
        add_to_log($course->id, "reader", "AA-set passed (uncheated)", "admin.php?id=$id", "$cm->instance");
        
        $userid1 = $DB->get_record("reader_attempts", array("id" => $cheated1));
        $userid2 = $DB->get_record("reader_attempts", array("id" => $cheated2));
        
        $data = new object;
        $data->byuserid  = $USER->id;
        $data->userid1   = $userid1->userid;
        $data->userid2   = $userid2->userid;
        $data->attempt1  = $cheated1;
        $data->attempt2  = $cheated2;
        $data->courseid  = $course->id;
        $data->readerid  = $reader->id;
        $data->quizid    = $userid1->quizid;
        $data->status    = "passed";
        $data->date      = time();
        
        $DB->insert_record("reader_cheated_log", $data);
        
        if ($reader->sendmessagesaboutcheating == 1) {
            $user1 = $DB->get_record("user", array("id" => $userid1->userid));
            $user2 = $DB->get_record("user", array("id" => $userid2->userid));
            email_to_user($user1,get_admin(),"Points restored notice",$reader->not_cheated_message);
            email_to_user($user2,get_admin(),"Points restored notice",$reader->not_cheated_message);
        }
    }
    //-------------------------------------//

    //---------Set goal -------------------//
    if (has_capability('mod/reader:manage', $contextmodule) && $act == "setgoal") {
        if ($wordsorpoints) {
            $DB->set_field("reader",  "wordsorpoints",  $wordsorpoints, array( "id" => $reader->id));
        }
        if (!$levelall) {
            // NEW LINES START
            if (empty($levelc)) {
                $levelc = array();
            }
            // NEW LINES STOP
            foreach ($levelc as $key => $value) {
              if ($value) {
                $data              = new object;
                if ($separategroups) $data->groupid = $separategroups;
                $data->readerid    = $reader->id;
                $data->level       = $key;
                $data->goal        = $value;
                $data->changedate  = time();
                $dataid = $DB->insert_record("reader_goal", $data);
              }
            }
            add_to_log($course->id, "reader", "AA-wordsorpoints goal=$value", "admin.php?id=$id", "$cm->instance");
        }
        else
        {
            $DB->delete_records("reader_goal", array("readerid" => $reader->id));
            if ($separategroups) {
                $data              = new object;
                $data->groupid     = $separategroups;
                $data->readerid    = $reader->id;
                $data->level       = 0;
                $data->goal        = $levelall;
                $data->changedate  = time();
                $DB->insert_record("reader_goal", $data);
            }
            else
            {
                $DB->set_field("reader", "goal", $levelall);
            }
            add_to_log($course->id, "reader", "AA-wordsorpoints goal=$levelall", "admin.php?id=$id", "$cm->instance");
        }
    }
    
    
    //---------Set individual quizzes -------------------//
    if (has_capability('mod/reader:manage', $contextmodule) && $act == "setindividualbooks" && is_array($quiz)) {
        $DB->delete_records ("reader_individual_books", array("readerid" => $reader->id));
        foreach ($quiz as $quiz_) {
            $oldbookdata = $DB->get_record("reader_publisher", array("id" => $quiz_));
            $data           = new object;
            $data->readerid = $reader->id;
            $data->bookid   = $quiz_;
            $data->difficulty   = $oldbookdata->difficulty;
            $data->length   = $oldbookdata->length;
            //print_r($data);
            $DB->insert_record("reader_individual_books", $data);
        }
    }
    
    
    //---------Set forced time delay -------------------//
    if (has_capability('mod/reader:manage', $contextmodule) && $act == "forcedtimedelay" && is_array($levelc)) {
        $DB->delete_records ("reader_forcedtimedelay", array("readerid" => $reader->id, "groupid" => $separategroups));
        foreach ($levelc as $key => $value) {
          if ($value != 0) {
            $data             = new object;
            $data->readerid   = $reader->id;
            $data->groupid    = $separategroups;
            $data->level      = $key;
            $data->delay      = $value;
            $data->changedate = time();
            $DB->insert_record("reader_forcedtimedelay", $data);
          }
        }
    }
    

    //---------Set forced time delay -------------------//
    if (has_capability('mod/reader:manage', $contextmodule) && $book && is_array($noquizuserid)) {
        foreach ($noquizuserid as $key => $value) {
          if ($value != 0) {
            $lastattemptid = $DB->get_field_sql('SELECT uniqueid FROM {reader_attempts} ORDER BY uniqueid DESC');
            $data               = new object;
            $data->uniqueid     = $lastattemptid + 1;
            $data->reader       = $reader->id;
            $data->userid       = $value;
            $data->attempt      = 1;
            $data->sumgrades    = 1;
            $data->passed       = "true";
            $data->persent      = 100;
            $data->timestart    = time();
            $data->timefinish   = time();
            $data->timemodified = time();
            $data->layout       = "0,";
            $data->preview      = 1;
            $data->quizid       = $book;
            $data->bookrating   = 1;
            $data->ip           = $_SERVER['REMOTE_ADDR'];
            
            $DB->insert_record("reader_attempts", $data);
          }
        }
            
        $noquizreport = 'Done';
            
        unset($book);
    }
    
    //---------Set forced time delay -------------------//
    if ((has_capability('mod/reader:downloadquizzesfromthereaderquizdatabase', $contextmodule)) && $numberofsections && $act == "changenumberofsectionsinquiz") {
      if ($reader->usecourse) $DB->set_field("course",  "numsections",  $numberofsections, array( "id" => $reader->usecourse));
    }
    
    
    //--------Adjust scores------------//
    if ($act == "adjustscores" && !empty($adjustscoresaddpoints) && !empty($adjustscoresupbooks)) {
        foreach ($adjustscoresupbooks as $key => $value) {
          $data = $DB->get_record("reader_attempts", array("id" => $value));
          $newpoint = $data->persent + $adjustscoresaddpoints;
          if ($newpoint >= $reader->percentforreading) $passed = 'true'; else $passed = 'false';
          $attempt = new object;
          $attempt->passed  = $passed;
          $attempt->persent = $newpoint;
          $attempt->id      = $value;
          $DB->update_record('reader_attempts', $attempt);
        }
        
        $adjustscorestext = 'Done';
    }
    
    if ($act == "adjustscores" && !empty($adjustscoresupall) && !empty($adjustscorespand) && !empty($adjustscorespby)) {
      $dataarr = $DB->get_records_sql("SELECT * FROM {reader_attempts} WHERE persent < ? AND persent > ? AND quizid = ?", array($adjustscorespand, $adjustscoresupall, $book));
      
	  foreach ($dataarr as $ida) {
          $data = $DB->get_record("reader_attempts", array("id" => $ida->id));
          $newpoint = $data->persent + $adjustscorespby;
          if ($newpoint >= $reader->percentforreading) $passed = 'true'; else $passed = 'false';
          $attempt = new object;
          $attempt->passed  = $passed;
          $attempt->persent = $newpoint;
          $attempt->id      = $ida->id;
          $DB->update_record('reader_attempts', $attempt);
	  }
      $adjustscorestext = 'Done';
    }
    //---------------------------------//
    

/// Print the page header

//---------DEBUG&SPEED TEST------------S-//
reader_debug_speed_check("header functions complite");
//---------DEBUG&SPEED TEST------------E-//

    $navigation = build_navigation('', $cm); 
    
//---------DEBUG&SPEED TEST------------S-//
reader_debug_speed_check("navigation loaded");
//---------DEBUG&SPEED TEST------------E-//
    
    //------ EXCEL--------------//
    if ($excel) {
        $workbook = new MoodleExcelWorkbook("-");
        $myxls =& $workbook->add_worksheet('report');
        
        $format =& $workbook->add_format();
        $format->set_bold(0);
        $formatbc =& $workbook->add_format();
        $formatbc->set_bold(1);
        
        if (!empty($grid)) {
            $grname = groups_get_group_name($grid);
        }
        else
        {
            $grname = "all";
        }
        
        $exceldata['time'] = date("d.M.Y");
        $exceldata['course_shotname'] = str_replace(" ", "-", $course->shortname);
        $exceldata['groupname'] = str_replace(" ", "-", $grname);
        
        if ($act != "exportstudentrecords") {
            $workbook->send('report_'.$exceldata['time'].'_'.$exceldata['course_shotname'].'_'.$exceldata['groupname'].'.xls');
        }
        else
        {
            header("Content-Type: text/plain; filename=\"{$COURSE->shortname}_attempts.txt\"");
            $workbook->send($COURSE->shortname.'_attempts.txt');
        }
        add_to_log($course->id, "reader", "AA-excel", "admin.php?id=$id", "$cm->instance");
    }
    //--------------------------//
    
    
// Initialize $PAGE, compute blocks
    $PAGE->set_url('/mod/reader/admin.php', array('id' => $cm->id));
    
    $title = $course->shortname . ': ' . format_string($reader->name);
    $PAGE->set_title($title);
    $PAGE->set_heading($course->fullname);
    
    
    

    if (!$excel) {
        //print_header_simple(format_string($reader->name), "", $navigation, "", "", true, update_module_button($cm->id, $course->id, get_string('modulename', 'reader')), navmenu($course, $cm));
        echo $OUTPUT->header();
        
        echo '<script type="text/javascript" src="ajax.js"></script><script type="application/x-javascript" src="js/jquery-1.4.2.min.js"></script>';
    }
    
//---------DEBUG&SPEED TEST------------S-//
reader_debug_speed_check("print_header_simple complyte");
//---------DEBUG&SPEED TEST------------E-//
    
    $alreadyansweredbooksid = array();
    
    if (has_capability('mod/reader:manage', $contextmodule)) {
        if (!$excel) {
            require_once ('tabs.php');
        }
    }
    else
    {
        die();
    }
    
    if (!$excel) {
        echo $OUTPUT->box_start('generalbox');
    }
    
    if (isset($message_forteacher)) {
        echo $message_forteacher;
    }
    
    if (!$excel) {
        echo '<h3>'.get_string("menu", "reader").':</h3>';
        
        if (has_capability('mod/reader:readerviewreports', $contextmodule)) 
                         $menu['studentmanagement']["?a=admin&id={$id}&act=reports"]          = get_string("reportquiztoreader", "reader");
        if (has_capability('mod/reader:readerviewreports', $contextmodule)) 
                         $menu['studentmanagement']["?a=admin&id={$id}&act=fullreports"]      = get_string("fullreportquiztoreader", "reader");
        if (has_capability('mod/reader:readerviewreports', $contextmodule)) 
                         $menu['studentmanagement']["?a=admin&id={$id}&act=reportbyclass"]    = get_string("summaryreportbyclassgroup", "reader");
        if (has_capability('mod/reader:viewanddeleteattempts', $contextmodule)) 
                         $menu['studentmanagement']["?a=admin&id={$id}&act=viewattempts"]     = get_string("viewattempts", "reader");
        if (has_capability('mod/reader:changestudentslevelsandpromote', $contextmodule)) 
                         $menu['studentmanagement']["?a=admin&id={$id}&act=studentslevels"]   = get_string("studentslevels", "reader");
        if (has_capability('mod/reader:sendmessage', $contextmodule)) 
                         $menu['studentmanagement']["?a=admin&id={$id}&act=sendmessage"]      = get_string("sendmessage", "reader");
        if (has_capability('mod/reader:awardextrapoints', $contextmodule)) 
                         $menu['studentmanagement']["?a=admin&id={$id}&act=awardextrapoints"] = get_string("awardextrapoints", "reader");
        if (has_capability('mod/reader:changestudentslevelsandpromote', $contextmodule)) 
                         $menu['studentmanagement']["?a=admin&id={$id}&act=assignpointsbookshavenoquizzes"] = get_string("assignpointsbookshavenoquizzes", "reader");
        if (has_capability('mod/reader:manage', $contextmodule)) 
                         $menu['studentmanagement']["?a=admin&id={$id}&act=adjustscores"]     = get_string("adjustscores", "reader");
                         
        if (has_capability('mod/reader:addcoursequizzestoreaderquizzes', $contextmodule)) 
                         $menu['quizmanagement']["?a=admin&id={$id}&act=addquiz"]             = get_string("addquiztoreader", "reader");
        if (has_capability('mod/reader:downloadquizzesfromthereaderquizdatabase', $contextmodule)) 
                         $menu['quizmanagement']["dlquizzes.php?id={$id}"]                    = get_string("uploadquiztoreader", "reader");
        if (has_capability('mod/reader:downloadquizzesfromthereaderquizdatabase', $contextmodule)) 
                         //$menu['quizmanagement']["dlquizzesnoq.php?id={$id}"]                 = get_string("uploaddatanoquizzes", "reader");
        if (has_capability('mod/reader:manage', $contextmodule)) 
                         $menu['quizmanagement']["updatecheck.php?id={$id}&checker=1"]        = get_string("quizmanagement", "reader");
        if (has_capability('mod/reader:setgoal', $contextmodule)) 
                         $menu['quizmanagement']["?a=admin&id={$id}&act=setgoal"]             = get_string("setgoal", "reader");
        if (has_capability('mod/reader:forcedtimedelay', $contextmodule)) 
                         $menu['quizmanagement']["?a=admin&id={$id}&act=forcedtimedelay"]     = get_string("forcedtimedelay", "reader");
        if (has_capability('mod/reader:deletequizzes', $contextmodule)) 
                         $menu['quizmanagement']["?a=admin&id={$id}&act=editquiz"]            = get_string("editquiztoreader", "reader");
        if (has_capability('mod/reader:readerviewreports', $contextmodule)) 
                         $menu['quizmanagement']["?a=admin&id={$id}&act=summarybookreports"]  = get_string("summaryreportbybooktitle", "reader");
        if (has_capability('mod/reader:readerviewreports', $contextmodule)) 
                         $menu['quizmanagement']["?a=admin&id={$id}&act=fullbookreports"]     = get_string("fullreportbybooktitle", "reader");
        if (has_capability('mod/reader:checklogsforsuspiciousactivity', $contextmodule)) 
                         $menu['quizmanagement']["?a=admin&id={$id}&act=checksuspiciousactivity"] = get_string("checksuspiciousactivity", "reader");
        if (has_capability('mod/reader:readerviewreports', $contextmodule)) 
                         $menu['quizmanagement']["?a=admin&id={$id}&act=viewlogsuspiciousactivity"] = get_string("viewlogsuspiciousactivity", "reader");
        if (has_capability('mod/reader:selectquizzestomakeavailabletostudents', $contextmodule)) 
                         $menu['quizmanagement']["?a=admin&id={$id}&act=setindividualbooks"]  = get_string("setindividualbooks", "reader");
        if (has_capability('mod/reader:changereadinglevelorlengthfactor', $contextmodule)) 
                         $menu['booklevelmanagement']["?a=admin&id={$id}&act=changereaderlevel"]   = get_string("changereaderlevel", "reader");
        if (has_capability('mod/reader:downloadquizzesfromthereaderquizdatabase', $contextmodule)) 
                         $menu['booklevelmanagement']["?a=admin&id={$id}&act=changenumberofsectionsinquiz"]   = get_string("changenumberofsectionsinquiz", "reader");
        //if (has_capability('mod/reader:createcoversetsbypublisherlevel', $contextmodule)) 
                         //$menu['booklevelmanagement']["?a=admin&id={$id}&act=makepix_t"]           = get_string("createcoversets_t", "reader");
        //if (has_capability('mod/reader:createcoversetsbypublisherlevel', $contextmodule)) 
                         //$menu['booklevelmanagement']["?a=admin&id={$id}&act=makepix_l"]           = get_string("createcoversets_l", "reader");
        if (has_capability('mod/reader:readerviewreports', $contextmodule)) 
                         $menu['booklevelmanagement']["?a=admin&id={$id}&act=bookratingslevel"]    = get_string("bookratingslevel", "reader");
        if (has_capability('mod/reader:userdbmanagement', $contextmodule)) 
                         $menu['booklevelmanagement']["?a=admin&id={$id}&act=exportstudentrecords&excel=1"]    = get_string("exportstudentrecords", "reader");
        if (has_capability('mod/reader:userdbmanagement', $contextmodule)) 
                         $menu['booklevelmanagement']["?a=admin&id={$id}&act=importstudentrecord"]    = get_string("importstudentrecord", "reader");
        
        
    
        foreach ($menu as $key1 => $value1) {
            echo "<b>";
            print_string($key1, "reader");
            echo '</b><ul>';
            foreach ($value1 as $key => $value) {
                echo '<li>';
                if ("?a=admin&id={$id}&act={$act}" != $key) {
                    echo '<a href="'.$key.'">'.$value.'</a>';
                }
                else
                {
                    echo $value;
                }
                echo '</li>';
            }
            echo '</ul>';
        }
        
        echo '<br /><hr />';
        
        echo ' <form method="get" action="' . $CFG->wwwroot . '/course/mod.php" onsubmit="this.target=\'_top\'; return true"><div><input name="update" value="' . $cm->id . '" type="hidden"><input name="return" value="true" type="hidden"><input name="sesskey" value="'. sesskey() . '" type="hidden"><input value="Change the main Reader settings" type="submit"></div></form>';
        
        
        if ($readercfg->reader_update == 1) {
            if (time() - $readercfg->reader_last_update > $readercfg->reader_update_interval) {
              echo $OUTPUT->box_start('generalbox');
              $days = round((time() - $readercfg->reader_last_update) / (24 * 3600));
              print_string("needtocheckupdates", "reader", $days);
              echo ' <a href="updatecheck.php?id='.$id.'">YES</a> / <a href="admin.php?a=admin&id='.$id.'">NO</a></center>';
              echo $OUTPUT->box_end();
            }
        }
        /*****************/
        
    }
    
//---------DEBUG&SPEED TEST------------S-//
reader_debug_speed_check("menu loaded");
//---------DEBUG&SPEED TEST------------E-//
    
    $options               = array();
    $options["a"]          = $a;
    $options["id"]         = $id;
    $options["act"]        = $act;
    $options["sort"]       = $sort;
    $options['orderby']    = $orderby;
    $options["grid"]       = $grid;
    $options["ct"]         = $ct;
    $options["searchtext"] = $searchtext;
    $options["excel"]      = 1;
    
    //---------------------------------Add NEW quizzes------------------------------//
    if ($act == "addquiz" && has_capability('mod/reader:addcoursequizzestoreaderquizzes', $contextmodule)) {
        if (!$quizesid) {
            if ($quizdata  = get_all_instances_in_course("quiz", $DB->get_record("course", array("id" => $reader->usecourse)), NULL, true)) {
            //if ($quizdata  = get_records("quiz")) {
                $existdata['publisher'][0]  = get_string("selectalreadyexist", "reader");
                $existdata['difficulty'][0] = get_string("selectalreadyexist", "reader");
                $existdata['level'][0]      = get_string("selectalreadyexist", "reader");
            
                if ($publishers = $DB->get_records ("reader_publisher")) {
                    foreach ($publishers as $publishers_) {
                        $existdata['publisher'][$publishers_->publisher] = $publishers_->publisher;
                        $existdata['difficulty'][$publishers_->difficulty] = $publishers_->difficulty;
                        $existdata['level'][$publishers_->level] = $publishers_->level;
                        $existdata['quizid'][$publishers_->quizid] = $publishers_->quizid;
                    }
                }
                // NEW LINES START
                $quizesarray = array();
                // NEW LINES STOP
                foreach ($quizdata as $quizdata_) {
                    if (!in_array ($quizdata_->id, $existdata['quizid'])) {
                        $quizesarray[$quizdata_->id] = $quizdata_->name;
                    }
                }
    
                if ($quizesarray) {
                    //------------Select Form----------------//
                    echo $OUTPUT->box_start('generalbox');
                    
                    echo '<h2>'.get_string("selectquizzes", "reader").'</h2><br />';
                    
                    echo '<form action="admin.php?a=admin&id='.$id.'" method="post" enctype="multipart/form-data">';
                    echo '<table style="width:100%">';
                    echo '<tr><td width="120px">';
                    print_string("publisher", "reader");
                    echo '</td><td width="120px">';
                    echo '<input type="text" name="publisher" value="" />';
                    echo '</td><td width="160px">';
                    if ($existdata['publisher']) {
                        echo '<select name="publisherex">';
                        foreach ($existdata['publisher'] as $key => $value) {
                            echo '<option value="'.$key.'">'.$value.'</option>';
                        }
                        echo '</select>';
                    }
                    echo '</td><td rowspan="5">';
                    echo '<select size="10" multiple="multiple" name="quizesid[]">';
                    foreach ($quizesarray as $key => $value) {
                        echo '<option value="'.$key.'">'.$value.'</option>';
                    }
                    echo '</select>';
                    echo '</td></tr>';
                    echo '<tr><td>';
                    print_string("level", "reader");
                    echo '</td><td>';
                    echo '<input type="text" name="level" value="" />';
                    echo '</td><td>';
                    if ($existdata['level']) {
                        echo '<select name="levelex">';
                        foreach ($existdata['level'] as $key => $value) {
                            echo '<option value="'.$key.'">'.$value.'</option>';
                        }
                        echo '</select>';
                    }
                    echo '</td></tr>';
                    echo '<tr><td>';
                    print_string("readinglevel", "reader");
                    echo '</td><td>';
                    echo '<input type="text" name="difficulty" value="" />';
                    echo '</td><td>';
                    if ($existdata['difficulty']) {
                        echo '<select name="difficultyex">';
                        foreach ($existdata['difficulty'] as $key => $value) {
                            echo '<option value="'.$key.'">'.$value.'</option>';
                        }
                        echo '</select>';
                    }
                    echo '</td></tr>';
                    echo '<tr><td>';
                    print_string("lengthex11", "reader");
                    echo '</td><td>';
                    echo '<input type="text" name="length" value="1" />';
                    echo '</td><td>';
                    echo '</td></tr>';
                    echo '<tr><td>';
                    print_string("image", "reader");
                    echo '</td><td colspan="2">';
                    echo '<input name="userimage" type="file" />';
                    echo '</td></tr>';
                    echo '<tr><td>';
                    print_string("ifimagealreadyexists", "reader");
                    echo '</td><td colspan="2">';
                    echo '<input type="text" name="userimagename" value="" />';
                    echo '</td></tr>';
                    echo '<tr><td>';
                    print_string("wordscount", "reader");
                    echo '</td><td colspan="2">';
                    echo '<input type="text" name="wordscount" value="" />';
                    echo '</td></tr>';
                    echo '<tr><td>';
                    print_string("private", "reader");
                    echo '</td><td colspan="2">';
                    echo '<select name="private">';
                    echo '<option value="0">No</option>';
                    echo '<option value="'.$reader->id.'">Yes</option>';
                    echo '</select>';
                    echo '</td></tr>';
                    echo '<tr align="center"><td colspan="4" height="60px"><input type="submit" name="submit" value="Add" /></td></tr>';
                    echo '</table>';
                    echo '</form>';
                    echo $OUTPUT->box_end();
                    //---------------------------------------//
                }
                else
                {
                    /* OLD LINES START **
                    print_error ('No quizzes found!');
                    ** OLD LINES STOP **/
                    // NEW LINES START
                    notice(get_string('noquizzesfound!', 'reader'));
                    // NEW LINES STOP
                }
            
            }
        }
    //---------------------------------Edit Quiz------------------------------//
    } else if ($act == "editquiz" && has_capability('mod/reader:deletequizzes', $contextmodule)) {
        if ($sort == "username") {
            $sort = "title";
        }
        $table = new html_table();
        
        $titlesarray = Array (''=>'', 'Title'=>'title', 'Publisher'=>'publisher', 'Level'=>'level', 'Reading Level'=>'rlevel', 'Length'=>'length', 'Times Quiz Taken'=>'qtaken', 'Average Points'=>'apoints', 'Options'=>'');
        
        $table->head = reader_make_table_headers ($titlesarray, $orderby, $sort, '?a=admin&id='.$id.'&act='.$act);
        $table->align = array ("center", "left", "left", "center", "center", "center", "center", "center", "center");
        $table->width = "100%";

        $books = $DB->get_records ("reader_publisher");
        
        foreach ($books as $book) {
        
            $totalgrade = 0;
            $totalpointsaverage = 0;
            $correctpoints = 0;
        
            $answersgrade = $DB->get_records ("reader_question_instances", array("quiz" => $book->id)); // —читать значени€ если разные баллы
            foreach ($answersgrade as $answersgrade_) {
                $totalgrade += $answersgrade_->grade;
            }
            
            $i = 0;
            
            $attemptsofbook = $DB->get_records_sql("SELECT * FROM {reader_attempts} WHERE quizid= ?  and reader= ? ", array($book->id, $reader->id));
            foreach ($attemptsofbook as $attemptsofbook_) {
                $i++;
                $totalpoints = round(($attemptsofbook_->sumgrades / $totalgrade) * 100, 2);
                $totalpointsaverage += $totalpoints;
                
                if ($totalpoints >= $reader->percentforreading) {
                    $correctpoints += 1;
                }
            }
            if ($i != 0)
              $averagepoint = round($totalpointsaverage / $i);
            else
              $averagepoint = 0;
        
            $timesoftaken = $i;
            
            if ($book->hidden == 1) {
                $book->name = '<font color="#666666">'.$book->name.' - hidden</font>';
            }
            
            $table->data[] = array ('<input type="checkbox" name="bookid['.$book->id.']">',
                                    $book->name,
                                    $book->publisher,
                                    $book->level,
                                    reader_get_reader_difficulty($reader, $book->id),
                                    reader_get_reader_length($reader, $book->id),
                                    $timesoftaken,
                                    $averagepoint.'%',
                                    '<a href="admin.php?a=admin&id='.$id.'&act=editquiz&deletebook='.$book->id.'" onclick="if(confirm(\'Delete this book?\')) return true; else return false;">Delete</a>');
        }
        
        $table->data = reader_sort_table_data ($table->data, $titlesarray, $orderby, $sort);
        
        
        if (!isset($needdeleteattemptsfirst)) {
            echo '<form action="admin.php?a=admin&id='.$id.'&act=editquiz" method="post">';
        
            if ($table) {
                echo html_writer::table($table);
            }
            /* OLD LINES START **
            echo '<input type="button" value="Select all" onclick="checkall();" /> <input type="button" value="Deselect all" onclick="uncheckall();" /><br /><br />';
            ** OLD LINES STOP **/
            // NEW LINES START
            echo '<input type="button" value="Select all" onclick="checkall();" /> <input type="button" value="Deselect all" onclick="checknone();" /><br /><br />';
            // NEW LINES STOP
            echo '<input type="submit" value="Hide Books" name="hidebooks" /> <input type="submit" value="UnHide Books" name="unhidebooks" />';
            echo '</form>';
        }
        else
        {
            unset($table);
            $options["excel"] = 0;
            
            $table->head = array("Id", "Date", "User", "SumGrades", "Status");
            $table->align = array ("center", "left", "left", "center", "center");
            $table->width = "80%";
            
            foreach ($needdeleteattemptsfirst as $attemptdata) {
                if ($attemptdata->timefinish >= $reader->ignordate) {
                    $status = "active";
                }
                else
                {
                    $status = "inactive";
                } 
            
                $userdata = $DB->get_record("user", array("id" => $attemptdata->userid));
                $table->data[] = array ($attemptdata->id,
                                        date("d M Y", $attemptdata->timefinish),
                                        fullname($userdata),
                                        round($attemptdata->sumgrades, 2),
                                        $status
                );
            }
            
            echo "<center><h3>".get_string("needdeletethisattemptstoo", "reader").":</h3>";
            
            if ($table) {
                echo html_writer::table($table);
            }
            
            echo '<form action="admin.php?a=admin&id='.$id.'&act=editquiz&deletebook='.$deletebook.'" method="post">';
            echo '<input type="hidden" name="deleteallattempts" value="1" />';
            echo '<input type="submit" value="Delete" />';
            echo '</form>';
            echo $OUTPUT->single_button(new moodle_url("admin.php",$options), get_string("cancel"), 'post', $options);
            echo '</center>';
        }
    //---------------------------------View Reports------------------------------//
    } else if ($act == "reports" && has_capability('mod/reader:readerviewreports', $contextmodule)) {
//---------DEBUG&SPEED TEST------------S-//
reader_debug_speed_check("Before Summary Report by Student");
//---------DEBUG&SPEED TEST------------E-//
        $table = new html_table();
        
        $titlesarray = Array ('Image'=>'', 'Username'=>'username', 'Fullname<br />Click to view screen'=>'fullname', 'Start level'=>'startlevel', 'Current level'=>'currentlevel', 'Taken Quizzes'=>'tquizzes', 'Passed<br /> Quizzes'=>'cquizzes', 'Failed<br /> Quizzes'=>'iquizzes', 'Total Points'=>'totalpoints', 'Total words<br /> this term'=>'totalwordsthisterm', 'Total words<br /> all terms'=>'totalwordsallterms');
        
        $table->head = reader_make_table_headers ($titlesarray, $orderby, $sort, '?a=admin&id='.$id.'&act=reports&grid='.$grid.'&searchtext='.$searchtext.'&page='.$page);
        $table->align = array ("center", "left", "left", "center", "center", "center", "center", "center", "center", "center", "center");
        $table->width = "100%";
        
        if ($excel) {
            $myxls->write_string(0, 0, 'Summary Report by Student',$formatbc);
            $myxls->write_string(1, 0, 'Date: '.$exceldata['time'].'; Course name: '.$exceldata['course_shotname'].'; Group: '.$exceldata['groupname']);
            $myxls->set_row(0, 30);
            $myxls->set_column(0,1,20);
            $myxls->set_column(2,10,15);
            
            $myxls->write_string(2, 0, 'Username',$formatbc);
            $myxls->write_string(2, 1, 'Fullname',$formatbc);
            $myxls->write_string(2, 2, 'Groups',$formatbc);
            $myxls->write_string(2, 3, 'Start level',$formatbc);
            $myxls->write_string(2, 4, 'Current level',$formatbc);
            $myxls->write_string(2, 5, 'Taken Quizzes',$formatbc);
            $myxls->write_string(2, 6, 'Passed Quizzes',$formatbc);
            $myxls->write_string(2, 7, 'Failed Quizzes',$formatbc);
            $myxls->write_string(2, 8, 'Total Points',$formatbc);
            $myxls->write_string(2, 9, 'Total words this term',$formatbc);
            $myxls->write_string(2, 10, 'Total words all terms',$formatbc);
        }
        
        if (!$grid) {
            $grid = NULL;
        }

        $coursestudents = get_enrolled_users($context, NULL, $grid);

        foreach ($coursestudents as $coursestudent) {
          if (reader_check_search_text($searchtext, $coursestudent)) {
            $picture = $OUTPUT->user_picture($coursestudent,array($course->id, true, 0, true));
            
            if ($excel) {
                //---------------------Groups for Excel------------//
                if ($usergroups = groups_get_all_groups($course->id, $coursestudent->id)){
                    foreach ($usergroups as $group){
                        $groupsdata[$coursestudent->username] .= $group->name.', ';
                    }
                    $groupsdata[$coursestudent->username] = substr($groupsdata[$coursestudent->username], 0, -2);
                }
                //---------------------------------------------------//
            }
            
            
            unset($data);
            
            if ($attempts = $DB->get_records_sql("SELECT * FROM {reader_attempts} WHERE userid= ?  and reader= ?  and timefinish > ?", array($coursestudent->id, $reader->id, $reader->ignordate))) {
                foreach ($attempts as $attempt) {
                    if (strtolower($attempt->passed) == "true") {
                        if ($attempt->preview == 0) {
                            $bookdata = $DB->get_record("reader_publisher", array("id" => $attempt->quizid));
                        } else {
                            $bookdata = $DB->get_record("reader_noquiz", array("id" => $attempt->quizid));
                        }
                        if (!isset($data['totalwordsthisterm'])) $data['totalwordsthisterm'] = 0;
                        $data['totalwordsthisterm'] += $bookdata->words;
                    }
                }
            }
            
            if ($attempts = $DB->get_records_sql("SELECT * FROM {reader_attempts} WHERE userid= ? ", array($coursestudent->id))) {
                foreach ($attempts as $attempt) {
                    if (strtolower($attempt->passed) == "true") {
                        if ($attempt->preview == 0) {
                            $bookdata = $DB->get_record("reader_publisher", array("id" => $attempt->quizid));
                        } else {
                            $bookdata = $DB->get_record("reader_noquiz", array("id" => $attempt->quizid));
                        }
                        if (!isset($data['totalwordsallterms'])) $data['totalwordsallterms'] = 0;
                        $data['totalwordsallterms'] += $bookdata->words;
                    }
                }
            }
            
            if ($attemptdata = reader_get_student_attempts($coursestudent->id, $reader)) {
                if (has_capability('mod/reader:viewstudentreaderscreens', $contextmodule)) {
                    $link = reader_fullname_link_viewasstudent($coursestudent, "grid={$grid}&searchtext={$searchtext}&page={$page}&sort={$sort}&orderby={$orderby}");
                }
                else
                {
                    $link = reader_fullname_link_t($coursestudent);
                }
                
                $table->data[] = array ($picture,
                                        reader_user_link_t($coursestudent),
                                        $link,
                                        $attemptdata[1]['startlevel'],
                                        $attemptdata[1]['currentlevel'],
                                        $attemptdata[1]['countattempts'],
                                        $attemptdata[1]['correct'], 
                                        $attemptdata[1]['incorrect'], 
                                        $attemptdata[1]['totalpoints'],
                                        $data['totalwordsthisterm'],
                                        $data['totalwordsallterms']
                                        );
            }
            else
            {
                if (has_capability('mod/reader:viewstudentreaderscreens', $contextmodule)) {
                    $link = reader_fullname_link_viewasstudent($coursestudent);
                }
                else
                {
                    $link = reader_fullname_link_t($coursestudent);
                }
                
                $table->data[] = array ($picture,
                                        reader_user_link_t($coursestudent),
                                        $link,
                                        $attemptdata[1]['startlevel'],
                                        $attemptdata[1]['currentlevel'],
                                        0,0,0,0,0,0);
            }
          }
        }
        
        $table->data = reader_sort_table_data ($table->data, $titlesarray, $orderby, $sort);

        if ($excel) {
          foreach ($table->data as $tabledataarray) {
            $myxls->write_string($row, 0, strip_tags($tabledataarray[1]));
            $myxls->write_string($row, 1, strip_tags($tabledataarray[2]));
            $myxls->write_string($row, 2, (string) $groupsdata[strip_tags($tabledataarray[1])]);
            $myxls->write_number($row, 3, (int) $tabledataarray[3]);
            $myxls->write_number($row, 4, (int) $tabledataarray[4]);
            $myxls->write_number($row, 5, (int) $tabledataarray[5]);
            $myxls->write_number($row, 6, (int) $tabledataarray[6]);
            $myxls->write_number($row, 7, (int) $tabledataarray[7]);
            $myxls->write_string($row, 8, $tabledataarray[8]);
            $myxls->write_string($row, 9, (int) $tabledataarray[9]);
            $myxls->write_string($row, 10, (int) $tabledataarray[10]);
            $row++;
          }
        }

        if ($excel) {
            $workbook->close();
            die();
        }
        
        echo '<table style="width:100%"><tr><td align="right">';
        echo $OUTPUT->single_button(new moodle_url("admin.php",$options), get_string("downloadexcel", "reader"), 'post', $options);
        echo '</td></tr></table>';
        
        reader_print_search_form ($id, $act);
        
        $groups = groups_get_all_groups ($course->id);
        
        if ($groups) {
            reader_print_group_select_box($course->id, 'admin.php?a=admin&id='.$id.'&act=reports&sort='.$sort.'&orderby='.$orderby);
        }
        
        reader_select_perpage($id, $act, $sort, $orderby, $grid);
        list($totalcount, $table->data, $startrec, $finishrec, $options["page"]) = reader_get_pages($table->data, $page, $perpage);
        //print_paging_bar($totalcount, $page, $perpage, "admin.php?a=admin&id={$id}&act={$act}&sort={$sort}&orderby={$orderby}&grid={$grid}&amp;");
        $pagingbar = new paging_bar($totalcount, $page, $perpage, "admin.php?a=admin&id={$id}&act={$act}&sort={$sort}&orderby={$orderby}&grid={$grid}&amp;");
        echo $OUTPUT->render($pagingbar); 
        
//---------DEBUG&SPEED TEST------------S-//
reader_debug_speed_check("      before print_table");
//---------DEBUG&SPEED TEST------------E-//
        
        if ($table) {
            echo html_writer::table($table);
        }
        
        //print_paging_bar($totalcount, $page, $perpage, "admin.php?a=admin&id={$id}&act={$act}&sort={$sort}&orderby={$orderby}&grid={$grid}&amp;");
        $pagingbar = new paging_bar($totalcount, $page, $perpage, "admin.php?a=admin&id={$id}&act={$act}&sort={$sort}&orderby={$orderby}&grid={$grid}&amp;");
        echo $OUTPUT->render($pagingbar); 
        
//---------DEBUG&SPEED TEST------------S-//
reader_debug_speed_check("      after print_table");
//---------DEBUG&SPEED TEST------------E-//
    //---------------------------------View Full Student Reports------------------------------//
    } else if ($act == "fullreports" && has_capability('mod/reader:readerviewreports', $contextmodule)) {
//---------DEBUG&SPEED TEST------------S-//
reader_debug_speed_check("Before Full Report by Student");
//---------DEBUG&SPEED TEST------------E-//
        $table = new html_table();
        if ($reader->wordsorpoints == "words") {
            if ($reader->checkbox == 1) {
                $titlesarray = Array ('Image'=>'', 'Username'=>'username', 'Fullname<br />Click to view screen'=>'fullname', 'Check'=>'', 'Date'=>'date', 'S-Level'=>'slevel', 'B-Level'=>'blevel', 'Title'=>'title', 'Score'=>'', 'P/F/C'=>'', 'Words'=>'', 'Total Words'=>'');
                $table->align = array ("center", "left", "left", "center", "center", "center", "center", "left", "center", "center", "center", "center");
            }
            else
            {
                $titlesarray = Array ('Image'=>'', 'Username'=>'username', 'Fullname<br />Click to view screen'=>'fullname', 'Date'=>'date', 'S-Level'=>'slevel', 'B-Level'=>'blevel', 'Title'=>'title', 'Score'=>'', 'P/F/C'=>'', 'Words'=>'', 'Total Words'=>'');
                $table->align = array ("center", "left", "left", "center", "center", "center", "left", "center", "center",  "center");
            }
        }
        else
        {
            if ($reader->checkbox == 1) {
                $titlesarray = Array ('Image'=>'', 'Username'=>'username', 'Fullname<br />Click to view screen'=>'fullname', 'Check'=>'', 'Date'=>'date', 'S-Level'=>'slevel', 'B-Level'=>'blevel', 'Title'=>'title', 'Score'=>'', 'P/F/C'=>'', 'Points'=>'', 'Length'=>'', 'Total Points'=>'');
                $table->align = array ("center", "left", "left", "center", "center", "center", "center", "left", "center", "center", "center", "center", "center");
            }
            else
            {
                $titlesarray = Array ('Image'=>'', 'Username'=>'username', 'Fullname<br />Click to view screen'=>'fullname', 'Date'=>'date', 'S-Level'=>'slevel', 'B-Level'=>'blevel', 'Title'=>'title', 'Score'=>'', 'P/F/C'=>'', 'Points'=>'', 'Length'=>'', 'Total Points'=>'');
                $table->align = array ("center", "left", "left", "center", "center", "center", "left", "center", "center", "center", "center", "center");
            }
        }
        
        $table->head = reader_make_table_headers ($titlesarray, $orderby, $sort, '?a=admin&id='.$id.'&act=fullreports&grid='.$grid.'&searchtext='.$searchtext.'&page='.$page.'&ct='.$ct);
        $table->width = "100%";
        
        if ($excel) {
            $myxls->write_string(0, 0, 'Full Report by Student',$formatbc);
            $myxls->write_string(1, 0, 'Date: '.$exceldata['time'].'; Course name: '.$exceldata['course_shotname'].'; Group: '.$exceldata['groupname']);
            $myxls->set_row(0, 30);
            $myxls->set_column(0,1,20);
            $myxls->set_column(2,10,15);
            
            $myxls->write_string(2, 0, 'Username',$formatbc);
            $myxls->write_string(2, 1, 'Fullname',$formatbc);
            $myxls->write_string(2, 2, 'Groups',$formatbc);
            if ($reader->wordsorpoints == "words") {
                if ($reader->checkbox == 1) {
                    $myxls->write_string(2, 3, 'Check',$formatbc);
                    $myxls->write_string(2, 4, 'Date',$formatbc);
                    $myxls->write_string(2, 5, 'S-Level',$formatbc);
                    $myxls->write_string(2, 6, 'B-Level',$formatbc);
                    $myxls->write_string(2, 7, 'Title',$formatbc);
                    $myxls->write_string(2, 8, 'Score',$formatbc);
                    $myxls->write_string(2, 9, 'P/F/C',$formatbc);
                    $myxls->write_string(2, 10, 'Words',$formatbc);
                    $myxls->write_string(2, 11, 'Total Words',$formatbc);
                }
                else
                {
                    $myxls->write_string(2, 3, 'Date',$formatbc);
                    $myxls->write_string(2, 4, 'S-Level',$formatbc);
                    $myxls->write_string(2, 5, 'B-Level',$formatbc);
                    $myxls->write_string(2, 6, 'Title',$formatbc);
                    $myxls->write_string(2, 7, 'Score',$formatbc);
                    $myxls->write_string(2, 8, 'P/F/C',$formatbc);
                    $myxls->write_string(2, 9, 'Words',$formatbc);
                    $myxls->write_string(2, 10, 'Total Words',$formatbc);
                }
            }
            else
            {
                if ($reader->checkbox == 1) {
                    $myxls->write_string(2, 3, 'Check',$formatbc);
                    $myxls->write_string(2, 4, 'Date',$formatbc);
                    $myxls->write_string(2, 5, 'S-Level',$formatbc);
                    $myxls->write_string(2, 6, 'B-Level',$formatbc);
                    $myxls->write_string(2, 7, 'Title',$formatbc);
                    $myxls->write_string(2, 8, 'Score',$formatbc);
                    $myxls->write_string(2, 9, 'P/F/C',$formatbc);
                    $myxls->write_string(2, 10, 'Points',$formatbc);
                    $myxls->write_string(2, 11, 'Length',$formatbc);
                    $myxls->write_string(2, 12, 'Total Points',$formatbc);
                }
                else
                {
                    $myxls->write_string(2, 3, 'Date',$formatbc);
                    $myxls->write_string(2, 4, 'S-Level',$formatbc);
                    $myxls->write_string(2, 5, 'B-Level',$formatbc);
                    $myxls->write_string(2, 6, 'Title',$formatbc);
                    $myxls->write_string(2, 7, 'Score',$formatbc);
                    $myxls->write_string(2, 8, 'P/F/C',$formatbc);
                    $myxls->write_string(2, 9, 'Points',$formatbc);
                    $myxls->write_string(2, 10, 'Length',$formatbc);
                    $myxls->write_string(2, 11, 'Total Points',$formatbc);
                }
            }
        }
        
        if (!$grid) {
            $grid = NULL;
        }
        
        if ($sort != 'username' && $sort != 'firstname') {
            $coursestudents = get_enrolled_users($context, NULL, $grid);
        }
        else
        {
            $coursestudents = get_enrolled_users($context, NULL, $grid);
        }

        foreach ($coursestudents as $coursestudent) {
            $picture = $OUTPUT->user_picture($coursestudent,array($course->id, true, 0, true));
            $totable['first'] = true;
            
            if ($excel) {
                //---------------------Groups for Excel------------//
                if ($usergroups = groups_get_all_groups($course->id, $coursestudent->id)){
                    foreach ($usergroups as $group){
                        $groupsdata[$coursestudent->username] .= $group->name.', ';
                    }
                    $groupsdata[$coursestudent->username] = substr($groupsdata[$coursestudent->username], 0, -2);
                }
                //---------------------------------------------------//
            }
            
            //----------------Count quizzes------------//
            if (list($attemptdata, $summaryattemptdata) = reader_get_student_attempts($coursestudent->id, $reader)) {
                unset($totalwords);
                foreach ($attemptdata as $attemptdata_) {
                
                  if (($attemptdata_['timefinish']>=$reader->ignordate && $ct == 1) || empty($ct)) {
                    if ($reader->wordsorpoints == "words") {
                      $totalwords = 0;
                      if (reader_check_search_text($searchtext, $coursestudent, $attemptdata_)) {
                        $showwords = 0;
                        switch (strtolower($attemptdata_['passed'])) { 
                         case "true": 
                             $passedstatus = "P";
                             $totalwords +=  $attemptdata_['words'];
                             $showwords   =  $attemptdata_['words'];
                             break; 
                         case "false": 
                             $passedstatus = "F"; 
                             break; 
                         case "cheated": 
                             $passedstatus = "C";
                             break; 
                        }
                        //$totalwords +=  $attemptdata_['words'];
                        if (!$excel && $sort == 'date')
                        {
                            $attemptbooktime = array(date("d-M-Y", $attemptdata_['timefinish']), $attemptdata_['timefinish']);
                        }
                        else if (!$excel)
                        {
                            $attemptbooktime = date("d-M-Y", $attemptdata_['timefinish']);
                        }
                        else
                        {
                            $attemptbooktime = date("Y/m/d", $attemptdata_['timefinish']);
                        }
                        if (($totable['first'] || $sort == 'slevel' || $sort == 'blevel' || $sort == 'title' || $sort == 'date') || $excel) {
                            list($linkusername, $username) = reader_user_link_t($coursestudent);
                            if ($excel) $linkusername = $username;
                            list($linkfullname, $username) = reader_fullname_link_t($coursestudent);
                            //print_r ($linkfullname);
                            if (has_capability('mod/reader:viewstudentreaderscreens', $contextmodule)) {
                                list($linkfullname) = reader_fullname_link_viewasstudent($coursestudent, "grid={$grid}&searchtext={$searchtext}&page={$page}&sort={$sort}&orderby={$orderby}");
                            }
                            
                            if ($reader->checkbox == 1) {
                                $table->data[] = array ($picture,
                                                    $linkusername,
                                                    $linkfullname,
                                                    reader_ra_checkbox($attemptdata_),
                                                    $attemptbooktime,
                                                    $attemptdata_['userlevel'],
                                                    $attemptdata_['bookdiff'],
                                                    $attemptdata_['booktitle'],
                                                    $attemptdata_['persent']."%",
                                                    $passedstatus,
                                                    $attemptdata_['words'], 
                                                    $totalwords);

                            }
                            else
                            {
                                $table->data[] = array ($picture,
                                                    $linkusername,
                                                    $linkfullname,
                                                    $attemptbooktime,
                                                    $attemptdata_['userlevel'],
                                                    $attemptdata_['bookdiff'],
                                                    $attemptdata_['booktitle'],
                                                    $attemptdata_['persent']."%",
                                                    $passedstatus,
                                                    $attemptdata_['words'], 
                                                    $totalwords);
                            }
                            $totable['first'] = false;
                        }
                        else
                        {
                            if ($reader->checkbox == 1) {
                                $table->data[] = array ('','','',
                                                   reader_ra_checkbox($attemptdata_),
                                                   $attemptbooktime,
                                                   $attemptdata_['userlevel'],
                                                   $attemptdata_['bookdiff'],
                                                   $attemptdata_['booktitle'],
                                                   $attemptdata_['persent']."%",
                                                   $passedstatus,
                                                   $attemptdata_['words'], 
                                                   $totalwords);
                            }
                            else
                            {
                                $table->data[] = array ('','','',
                                                   $attemptbooktime,
                                                   $attemptdata_['userlevel'],
                                                   $attemptdata_['bookdiff'],
                                                   $attemptdata_['booktitle'],
                                                   $attemptdata_['persent']."%",
                                                   $passedstatus,
                                                   //$attemptdata_['bookpoints'],
                                                   $attemptdata_['words'], 
                                                   $totalwords);
                            }
                        }
                      }
                    } else {    
                      if (reader_check_search_text($searchtext, $coursestudent, $attemptdata_)) {
                        switch (strtolower($attemptdata_['passed'])) { 
                        case "true": 
                            $passedstatus = "P";
                            break; 
                        case "false": 
                            $passedstatus = "F"; 
                            break; 
                        case "cheated": 
                            $passedstatus = "C";
                            break; 
                        } 
                        if (!$excel && $sort == 'date')
                        {
                            $attemptbooktime = array(date("d-M-Y", $attemptdata_['timefinish']), $attemptdata_['timefinish']);
                        }
                        else if (!$excel)
                        {
                            $attemptbooktime = date("d-M-Y", $attemptdata_['timefinish']);
                        }
                        else
                        {
                            $attemptbooktime = date("Y/m/d", $attemptdata_['timefinish']);
                        }
                        if (($totable['first'] || $sort == 'slevel' || $sort == 'blevel' || $sort == 'title' || $sort == 'date') || $excel) {
                            list($linkusername, $username) = reader_user_link_t($coursestudent);
                            if ($excel) $linkusername = $username;
                            list($linkfullname, $username) = reader_fullname_link_t($coursestudent);
                            if (has_capability('mod/reader:viewstudentreaderscreens', $contextmodule)) {
                                list($linkfullname) = reader_fullname_link_viewasstudent($coursestudent, "grid={$grid}&searchtext={$searchtext}&page={$page}&sort={$sort}&orderby={$orderby}");
                            }
                            if ($reader->checkbox == 1) {
                                $table->data[] = array ($picture,
                                                    $linkusername,
                                                    $linkfullname,
                                                    reader_ra_checkbox($attemptdata_),
                                                    $attemptbooktime,
                                                    $attemptdata_['userlevel'],
                                                    $attemptdata_['bookdiff'],
                                                    $attemptdata_['booktitle'],
                                                    $attemptdata_['persent']."%",
                                                    $passedstatus,
                                                    $attemptdata_['bookpoints'],
                                                    $attemptdata_['booklength'], 
                                                    $attemptdata_['totalpoints']);
                            }
                            else
                            {
                                $table->data[] = array ($picture,
                                                    $linkusername,
                                                    $linkfullname,
                                                    $attemptbooktime,
                                                    $attemptdata_['userlevel'],
                                                    $attemptdata_['bookdiff'],
                                                    $attemptdata_['booktitle'],
                                                    $attemptdata_['persent']."%",
                                                    $passedstatus,
                                                    $attemptdata_['bookpoints'],
                                                    $attemptdata_['booklength'], 
                                                    $attemptdata_['totalpoints']);
                            }
                            $totable['first'] = false;
                        }
                        else
                        {
                            if ($reader->checkbox == 1) {
                                $table->data[] = array ('','','',
                                                   reader_ra_checkbox($attemptdata_),
                                                   $attemptbooktime,
                                                   $attemptdata_['userlevel'],
                                                   $attemptdata_['bookdiff'],
                                                   $attemptdata_['booktitle'],
                                                   $attemptdata_['persent']."%",
                                                   $passedstatus,
                                                   $attemptdata_['bookpoints'],
                                                   $attemptdata_['booklength'], 
                                                   $attemptdata_['totalpoints']);
                            }
                            else
                            {
                                $table->data[] = array ('','','',
                                                   $attemptbooktime,
                                                   $attemptdata_['userlevel'],
                                                   $attemptdata_['bookdiff'],
                                                   $attemptdata_['booktitle'],
                                                   $attemptdata_['persent']."%",
                                                   $passedstatus,
                                                   $attemptdata_['bookpoints'],
                                                   $attemptdata_['booklength'], 
                                                   $attemptdata_['totalpoints']);
                            }
                        }
                      }
                    }
                  }
                }
            }
        }

        if ($sort == 'slevel' || $sort == 'blevel' || $sort == 'title' || $sort == 'date') {
            $table->data = reader_sort_table_data ($table->data, $titlesarray, $orderby, $sort);
        }
        
        if ($excel) {
          $formatDate =& $workbook->add_format();
          $formatDate->set_num_format(get_string('log_excel_date_format'));
          foreach ($table->data as $tabledataarray) {
            $myxls->write_string($row, 0, $tabledataarray[1]);
            $myxls->write_string($row, 1, strip_tags($tabledataarray[2]));
            $myxls->write_string($row, 2, $groupsdata[$tabledataarray[1]]);
            if ($reader->checkbox == 1) {
                $myxls->write_string($row, 3, $tabledataarray[3]);
                $myxls->write_string($row, 4, strip_tags($tabledataarray[4]), $formatDate);
                $myxls->write_string($row, 5, $tabledataarray[5]);
                $myxls->write_string($row, 6, $tabledataarray[6]);
                $myxls->write_string($row, 7, $tabledataarray[7]);
                $myxls->write_string($row, 8, $tabledataarray[8]);
                $myxls->write_string($row, 9, $tabledataarray[9]);
                $myxls->write_string($row, 10, $tabledataarray[10]);
                $myxls->write_string($row, 11, $tabledataarray[11]);
                $myxls->write_string($row, 12, $tabledataarray[12]);
            }
            else
            {
                $myxls->write_string($row, 3, strip_tags($tabledataarray[3]), $formatDate);
                $myxls->write_string($row, 4, $tabledataarray[4]);
                $myxls->write_string($row, 5, $tabledataarray[5]);
                $myxls->write_string($row, 6, $tabledataarray[6]);
                $myxls->write_string($row, 7, $tabledataarray[7]);
                $myxls->write_string($row, 8, $tabledataarray[8]);
                $myxls->write_string($row, 9, $tabledataarray[9]);
                $myxls->write_string($row, 10, $tabledataarray[10]);
                $myxls->write_string($row, 11, $tabledataarray[11]);
            }
            $row++;
          }
        }
        
        if ($excel) {
            $workbook->close();
            die();
        }
        
        echo '<table style="width:100%"><tr><td align="right">';
        echo $OUTPUT->single_button(new moodle_url("admin.php",$options), get_string("downloadexcel", "reader"), 'post', $options);
        echo '</td></tr></table>';

        reader_print_search_form ($id, $act);
        
        $groups = groups_get_all_groups ($course->id);
        
        if ($groups) {
            reader_print_group_select_box($course->id, 'admin.php?a=admin&id='.$id.'&act=fullreports&sort='.$sort.'&orderby='.$orderby.'&ct='.$ct);
        }
        
        echo '<div style="text-align:right"><form action="" method="post" id="mform_ct"><select onchange="document.getElementById(\'mform_ct\').action = document.getElementById(\'mform_ct\').level.options[document.getElementById(\'mform_ct\').level.selectedIndex].value;document.getElementById(\'mform_ct\').submit(); return true;" name="level" id="id_level"><option value="admin.php?a=admin&id='.$id.'&act='.$act.'&sort='.$sort.'&grid='.$grid.'&orderby='.$orderby.'&ct=0" ';
        if (empty($ct)) echo ' selected="selected" ';
        echo ' >All terms</option><option value="admin.php?a=admin&id='.$id.'&act='.$act.'&sort='.$sort.'&grid='.$grid.'&orderby='.$orderby.'&ct=1" ';
        if (!empty($ct)) echo ' selected="selected" ';
        echo ' >Current term</option></select></form></div>';
        
        reader_select_perpage($id, $act, $sort, $orderby, $grid);
        list($totalcount, $table->data, $startrec, $finishrec, $options["page"]) = reader_get_pages($table->data, $page, $perpage);
        //print_paging_bar($totalcount, $page, $perpage, "admin.php?a=admin&id={$id}&act={$act}&sort={$sort}&orderby={$orderby}&grid={$grid}&ct={$ct}&amp;");
        $pagingbar = new paging_bar($totalcount, $page, $perpage, "admin.php?a=admin&id={$id}&act={$act}&sort={$sort}&orderby={$orderby}&grid={$grid}&ct={$ct}&amp;");
        echo $OUTPUT->render($pagingbar); 
            
//---------DEBUG&SPEED TEST------------S-//
reader_debug_speed_check("before Full print_table");
//---------DEBUG&SPEED TEST------------E-//
            
        if ($table) {
            echo html_writer::table($table);
        }
        
        $pagingbar = new paging_bar($totalcount, $page, $perpage, "admin.php?a=admin&id={$id}&act={$act}&sort={$sort}&orderby={$orderby}&grid={$grid}&ct={$ct}&amp;");
        echo $OUTPUT->render($pagingbar); 
//---------DEBUG&SPEED TEST------------S-//
reader_debug_speed_check("after Full print_table");
//---------DEBUG&SPEED TEST------------E-//
    //---------------------------------View Summary Books Reports------------------------------//
    } else if ($act == "summarybookreports" && has_capability('mod/reader:readerviewreports', $contextmodule)) {
        if ($sort == "username") {
            $sort = "title";
        }
        $table = new html_table();
        $titlesarray = Array ('Title'=>'title', 'Publisher'=>'publisher', 'Level'=>'level', 'Reading Level'=>'rlevel', 'Length'=>'length', 'Times Quiz Taken'=>'qtaken', 'Average Points'=>'apoints', 'Passed'=>'passed', 'Failed'=>'failed', 'Pass Rate'=>'prate');
        
        $table->head = reader_make_table_headers ($titlesarray, $orderby, $sort, '?a=admin&id='.$id.'&act=summarybookreports&grid='.$grid.'&searchtext='.$searchtext.'&page='.$page);
        $table->align = array ("left", "left", "center", "center", "center", "center", "center", "center", "center", "center");
        $table->width = "100%";
        
        if ($excel) {
            $myxls->write_string(0, 0, 'Summary Report by Book Title',$formatbc);
            $myxls->write_string(1, 0, 'Date: '.$exceldata['time'].'; Course name: '.$exceldata['course_shotname'].'; Group: '.$exceldata['groupname']);
            $myxls->set_row(0, 30);
            $myxls->set_column(0,1,20);
            $myxls->set_column(2,10,15);
            
            $myxls->write_string(2, 0, 'Title',$formatbc);
            $myxls->write_string(2, 1, 'Publisher',$formatbc);
            $myxls->write_string(2, 2, 'Level',$formatbc);
            $myxls->write_string(2, 3, 'Reading Level',$formatbc);
            $myxls->write_string(2, 4, 'Length',$formatbc);
            $myxls->write_string(2, 5, 'Times Quiz Taken',$formatbc);
            $myxls->write_string(2, 6, 'Average Points',$formatbc);
            $myxls->write_string(2, 7, 'Passed',$formatbc);
            $myxls->write_string(2, 8, 'Failed',$formatbc);
            $myxls->write_string(2, 8, 'Pass Rate',$formatbc);
        }
        
        $books = $DB->get_records_sql ("SELECT * FROM {reader_publisher} WHERE hidden=? and private IN(0,?)", array(0, $reader->id));
        while(list($bookkey, $book) = each($books)) {
          if (reader_check_search_text($searchtext, '', $book)) {
            $totalgrade = 0;
            $totalpointsaverage = 0;
            $correctpoints = 0;

            $i = 0;
            
            $attemptsofbook = $DB->get_records_sql("SELECT * FROM {reader_attempts} WHERE quizid= ? ", array($book->id));
            while(list($attemptsofbookkey, $attemptsofbook_) = each($attemptsofbook)) {
                $i++;
                $totalpointsaverage += $attemptsofbook_->persent;
                
                if (strtolower($attemptsofbook_->passed) == "true") {
                    $correctpoints += 1;
                }
            }
            if ($i != 0) {
              $averagepoint = round($totalpointsaverage / $i);
              $prate = round(($correctpoints/$i) * 100);
            } else {
              $averagepoint = 0;
              $prate        = 0;
            }
            
            $timesoftaken = $i;
            //$table->data[] = array (array('<a href="report.php?idh='.$id.'&q='.$book->quizid.'&mode=analysis&b='.$book->id.'">'.$book->name.'</a>', $book->name),
            $table->data[] = array (array('<a href="report.php?b='.$book->id.'">'.$book->name.'</a>', $book->name),
                                    $book->publisher,
                                    $book->level,
                                    reader_get_reader_difficulty($reader, $book->id),
                                    reader_get_reader_length($reader, $book->id),
                                    $timesoftaken,
                                    $averagepoint.'%',
                                    $correctpoints,
                                    ($timesoftaken - $correctpoints),
                                    $prate.'%');
          }
        }
        //reset($books);

        $table->data = reader_sort_table_data ($table->data, $titlesarray, $orderby, $sort);

        if ($excel) {
          foreach ($table->data as $tabledataarray) {
            $myxls->write_string($row, 0, strip_tags($tabledataarray[0]));
            $myxls->write_string($row, 1, $tabledataarray[1]);
            $myxls->write_string($row, 2, $tabledataarray[2]);
            $myxls->write_string($row, 3, $tabledataarray[3]);
            $myxls->write_string($row, 4, $tabledataarray[4]);
            $myxls->write_string($row, 5, $tabledataarray[5]);
            $myxls->write_string($row, 6, $tabledataarray[6]);
            $myxls->write_string($row, 7, $tabledataarray[7]);
            $myxls->write_string($row, 8, $tabledataarray[8]);
            $myxls->write_string($row, 9, $tabledataarray[9]);
            $row++;
          }
        }
        
        if ($excel) {
            $workbook->close();
            die();
        }
        
        echo '<table style="width:100%"><tr><td align="right">';
        echo $OUTPUT->single_button(new moodle_url("admin.php",$options), get_string("downloadexcel", "reader"), 'post', $options);
        echo '</td></tr></table>';
        
        reader_print_search_form ($id, $act);

        reader_select_perpage($id, $act, $sort, $orderby, $grid);
        list($totalcount, $table->data, $startrec, $finishrec, $options["page"]) = reader_get_pages($table->data, $page, $perpage);
        $pagingbar = new paging_bar($totalcount, $page, $perpage, "admin.php?a=admin&id={$id}&act={$act}&sort={$sort}&orderby={$orderby}&grid={$grid}&amp;");
        echo $OUTPUT->render($pagingbar); 
        
        if ($table) {
            echo html_writer::table($table);
        }
        
        $pagingbar = new paging_bar($totalcount, $page, $perpage, "admin.php?a=admin&id={$id}&act={$act}&sort={$sort}&orderby={$orderby}&grid={$grid}&amp;");
        echo $OUTPUT->render($pagingbar); 
    //---------------------------------View Full Books Reports------------------------------//
    } else if ($act == "fullbookreports" && has_capability('mod/reader:readerviewreports', $contextmodule)) {
//---------DEBUG&SPEED TEST------------S-//
reader_debug_speed_check("Before fullbookreports");
//---------DEBUG&SPEED TEST------------E-//
        if ($sort == "username") {
            $sort = "title";
        }
        $table = new html_table();
        
        $titlesarray = Array ('Title'=>'title', 'Publisher'=>'publisher', 'Level'=>'level', 'Reading Level'=>'rlevel', 'Student Name'=>'sname', 'Student ID'=>'studentid', 'Passed/Failed'=>'');
        
        if ($excel) {
            $myxls->write_string(0, 0, 'Full Report by Book Title',$formatbc);
            $myxls->write_string(1, 0, 'Date: '.$exceldata['time'].'; Course name: '.$exceldata['course_shotname'].'; Group: '.$exceldata['groupname']);
            $myxls->set_row(0, 30);
            $myxls->set_column(0,1,20);
            $myxls->set_column(2,10,15);
            
            $myxls->write_string(2, 0, 'Title',$formatbc);
            $myxls->write_string(2, 1, 'Publisher',$formatbc);
            $myxls->write_string(2, 2, 'Level',$formatbc);
            $myxls->write_string(2, 3, 'Reading Level',$formatbc);
            $myxls->write_string(2, 4, 'Student Name',$formatbc);
            $myxls->write_string(2, 5, 'Student ID',$formatbc);
            $myxls->write_string(2, 6, 'Passed/Failed',$formatbc);
        }
        
        $table->head = reader_make_table_headers ($titlesarray, $orderby, $sort, '?a=admin&id='.$id.'&act=fullbookreports&grid='.$grid.'&searchtext='.$searchtext.'&page='.$page);
        $table->align = array ("left", "left", "center", "center", "left", "left", "center");
        $table->width = "100%";
        
        $books = $DB->get_records ("reader_publisher");
        
//---------DEBUG&SPEED TEST------------S-//
reader_debug_speed_check("Before foreach");
//---------DEBUG&SPEED TEST------------E-//

        $usegroupidsql = "";
        if ($grid) {
            $groupstudents = groups_get_members($grid);
            foreach ($groupstudents as $groupstudents_) {
                $allids .= $groupstudents_->id.",";
            }
            $allids = substr($allids, 0, -1);
            $usegroupidsql = "and ra.userid IN ({$allids})";
        }
        
        while(list($bookkey, $book) = each($books)) {
        //foreach ($books as $book) {
          if (reader_check_search_text($searchtext, '', $book)) {
            $totalgrade = 0;
            
            $attemptsofbook = $DB->get_records_sql("SELECT *,u.username,u.firstname,u.lastname FROM {reader_attempts} ra INNER JOIN {user} u ON u.id = ra.userid WHERE ra.quizid= ?  and ra.reader= ?  {$usegroupidsql}", array($book->id, $reader->id));
            while(list($attemptsofbookkey, $attemptsofbook_) = each($attemptsofbook)) {
            //foreach ($attemptsofbook as $attemptsofbook_) {
                $table->data[] = array (array('<a href="report.php?idh='.$id.'&q='.$book->quizid.'&mode=analysis&b='.$book->id.'">'.$book->name.'</a>', $book->name),
                                            $book->publisher,
                                            $book->level,
                                            reader_get_reader_difficulty($reader, $book->id),
                                            reader_fullname_link_t($attemptsofbook_),
                                            reader_user_link_t($attemptsofbook_),
                                            $attemptsofbook_->passed);
            }
          }
        }
        
//---------DEBUG&SPEED TEST------------S-//
reader_debug_speed_check("after foreach");
//---------DEBUG&SPEED TEST------------E-//

        $table->data = reader_sort_table_data ($table->data, $titlesarray, $orderby, $sort);
        
        if ($excel) {
          foreach ($table->data as $tabledataarray) {
            $myxls->write_string($row, 0, strip_tags($tabledataarray[0]));
            $myxls->write_string($row, 1, $tabledataarray[1]);
            $myxls->write_string($row, 2, $tabledataarray[2]);
            $myxls->write_string($row, 3, $tabledataarray[3]);
            $myxls->write_string($row, 4, strip_tags($tabledataarray[4]));
            $myxls->write_string($row, 5, strip_tags($tabledataarray[5]));
            $myxls->write_string($row, 6, $tabledataarray[6]);
            $row++;
          }
        }
        
        if ($excel) {
            $workbook->close();
            die();
        }
        
        echo '<table style="width:100%"><tr><td align="right">';
        echo $OUTPUT->single_button(new moodle_url("admin.php",$options), get_string("downloadexcel", "reader"), 'post', $options);
        echo '</td></tr></table>';
        
        reader_print_search_form ($id, $act);

        $groups = groups_get_all_groups ($course->id);
        
        if ($groups) {
            reader_print_group_select_box($course->id, 'admin.php?a=admin&id='.$id.'&act='.$act.'&sort='.$sort.'&orderby='.$orderby);
        }
        
        reader_select_perpage($id, $act, $sort, $orderby, $grid);
        list($totalcount, $table->data, $startrec, $finishrec, $options["page"]) = reader_get_pages($table->data, $page, $perpage);
        $pagingbar = new paging_bar($totalcount, $page, $perpage, "admin.php?a=admin&id={$id}&act={$act}&sort={$sort}&orderby={$orderby}&grid={$grid}&amp;");
        echo $OUTPUT->render($pagingbar); 
        
        if ($table) {
            echo html_writer::table($table);
        }
        
        $pagingbar = new paging_bar($totalcount, $page, $perpage, "admin.php?a=admin&id={$id}&act={$act}&sort={$sort}&orderby={$orderby}&grid={$grid}&amp;");
        echo $OUTPUT->render($pagingbar); 
    //---------------------------------View Attempts------------------------------//
    } else if ($act == "viewattempts" && has_capability('mod/reader:viewanddeleteattempts', $contextmodule)) {
    
//---------DEBUG&SPEED TEST------------S-//
reader_debug_speed_check("Before viewattempts");
//---------DEBUG&SPEED TEST------------E-//
        $table = new html_table();
        
        if (!$searchtext && !$grid) {
          echo "<center><h2><font color=\"red\">".get_string("pleasespecifyyourclassgroup", "reader")."</font></h2></center>";
        }
        else
        {
            if (has_capability('mod/reader:deletereaderattempts', $contextmodule)) {
                $titlesarray = Array ('Username'=>'username', 'Fullname'=>'fullname', 'Book Name'=>'bname', 'AttemptID'=>'attemptid', 'Score'=>'score', 'P/F/C'=>'', 'Finishtime'=>'timefinish', 'Option' => '');
            }
            else
            {
                $titlesarray = Array ('Username'=>'username', 'Fullname'=>'fullname', 'Book Name'=>'bname', 'AttemptID'=>'attemptid', 'Score'=>'score', 'P/F/C'=>'', 'Finishtime'=>'timefinish');
            }
            
            $table->head = reader_make_table_headers ($titlesarray, $orderby, $sort, '?a=admin&id='.$id.'&act=viewattempts&page='.$page.'&grid='.$grid.'&searchtext='.$searchtext);
            $table->align = array ("left", "left", "left", "center", "center", "center", "center", "center");
            $table->width = "100%";
            
            if ($excel) {
                $myxls->write_string(0, 0, 'View and Delete Attempts',$formatbc);
                //$myxls->write_string(1, 0, 'Date: '.$exceldata['time'].'; Course name: '.$exceldata['course_shotname'].'; Group: '.$exceldata['groupname']);
                $myxls->set_row(0, 30);
                $myxls->set_column(0,1,20);
                $myxls->set_column(2,10,15);
                
                $myxls->write_string(2, 0, 'Username',$formatbc);
                $myxls->write_string(2, 1, 'Fullname',$formatbc);
                $myxls->write_string(2, 2, 'Book Name',$formatbc);
                $myxls->write_string(2, 3, 'AttemptID',$formatbc);
                $myxls->write_string(2, 4, 'Score',$formatbc);
                $myxls->write_string(2, 5, 'Finishtime',$formatbc);
                $myxls->write_string(2, 6, 'P/F/C',$formatbc);
            }

            if (!$searchtext && $grid)
            {
                $groupstudents = groups_get_members($grid);
                $allids = "";
                foreach ($groupstudents as $groupstudents_) {
                    $allids .= $groupstudents_->id.",";
                }
                $allids = substr($allids, 0, -1);
                
                if ($CFG->dbtype == "mysql") {
                    $attemptsdata = $DB->get_records_sql("SELECT ra.timefinish,ra.userid,ra.attempt,ra.persent,ra.id,ra.passed,rp.name,rp.publisher,rp.level,u.username,u.firstname,u.lastname FROM {reader_attempts} ra LEFT JOIN ({reader_publisher} rp, {user} u) ON (rp.id = ra.quizid AND u.id = ra.userid) WHERE ra.userid IN ({$allids})");
                }
                else
                {
                    /*  VARIANT 2 */
                    
                    $attemptsdata = $DB->get_records_sql("SELECT ra.timefinish,ra.userid,ra.attempt,ra.persent,ra.id,ra.quizid,ra.sumgrades,ra.passed,rp.name,rp.publisher,rp.level,rp.length,rp.image,rp.difficulty,rp.words FROM {reader_attempts} ra LEFT JOIN {reader_publisher} rp ON rp.id = ra.quizid WHERE ra.userid IN ({$allids}) ORDER BY ra.timefinish");
                     foreach ($attemptsdata as $key => $value) {
                         $userdata = $DB->get_record("user", array("id" => $value->userid));
                         $attemptsdata[$key]->username  = $userdata->username;
                         $attemptsdata[$key]->firstname = $userdata->firstname;
                         $attemptsdata[$key]->lastname  = $userdata->lastname;
                     }
                 }
            }
            else if ($searchtext)
            {
                if (strstr($searchtext, '"')) {
                    $searchtextforsql = str_replace('\"', '"', $searchtext);
                    $searchtextforsql = explode('"', $searchtextforsql);
                }
                else
                {
                    $searchtextforsql = explode(" ", $searchtext);
                }
                if ($CFG->dbtype == "mysql") {
                    foreach ($searchtextforsql as $searchtext_) {
                      if ($searchtext_ && strlen($searchtext_) > 3) {
                        $searchsql .= " u.username LIKE '%{$searchtext_}%' OR u.firstname LIKE '%{$searchtext_}%' OR u.lastname LIKE '%{$searchtext_}%' OR rp.name LIKE '%{$searchtext_}%' OR rp.level LIKE '%{$searchtext_}%' OR rp.publisher LIKE '%{$searchtext_}%' OR";
                      }
                    }
                    $searchsql = substr($searchsql, 0, -2);
                    $attemptsdata = $DB->get_records_sql("SELECT ra.timefinish,ra.userid,ra.attempt,ra.persent,ra.id,ra.passed,rp.name,rp.publisher,rp.level,u.username,u.firstname,u.lastname FROM {reader_attempts} ra LEFT JOIN ({reader_publisher} rp, {user} u) ON (rp.id = ra.quizid AND u.id = ra.userid) WHERE {$searchsql}");
                }
                else
                {
                    $attemptsdata = $DB->get_records_sql("SELECT ra.timefinish,ra.userid,ra.attempt,ra.persent,ra.id,ra.passed,rp.name,rp.publisher,rp.level FROM {reader_attempts} ra LEFT JOIN {reader_publisher} rp ON rp.id = ra.quizid");

                    foreach ($attemptsdata as $key => $value) {
                        $userdata = $DB->get_record("user", array("id" => $value->userid));
                        $needmark = false;
                        foreach ($searchtextforsql as $searchtext_) {
                            if (strstr(strtolower($userdata->username),strtolower($searchtext_)) || strstr(strtolower($userdata->firstname),strtolower($searchtext_)) || strstr(strtolower($userdata->lastname),strtolower($searchtext_))) {
                              $needmark = true;
                            }
                        }
                        if ($needmark) {
                            $attemptsdata[$key]->username  = $userdata->username;
                            $attemptsdata[$key]->firstname = $userdata->firstname;
                            $attemptsdata[$key]->lastname  = $userdata->lastname;
                        }
                        else
                        {
                            unset($attemptsdata[$key]);
                        }
                    }
                }
            }
            
            foreach ($attemptsdata as $attemptdata) {
                if (!$excel)
                {
                    $attemptbooktime = array(date("d-M-Y", $attemptdata->timefinish), $attemptdata->timefinish);
                }
                else
                {
                    $attemptbooktime = date("Y/m/d", $attemptdata->timefinish);
                }
                
                switch (strtolower($attemptdata->passed)) { 
                case "true": 
                    $passedstatus = "P";
                    break; 
                case "false": 
                    $passedstatus = "F"; 
                    break; 
                case "cheated": 
                    $passedstatus = "C";
                    break; 
                } 
                
                if (has_capability('mod/reader:deletereaderattempts', $contextmodule)) {
                    $table->data[] = array (reader_user_link_t($attemptdata),
                                            reader_fullname_link_t($attemptdata), 
                                            $attemptdata->name, 
                                            $attemptdata->attempt, 
                                            $attemptdata->persent."%", 
                                            $passedstatus,
                                            $attemptbooktime, 
                                            '<a href="admin.php?a=admin&id='.$id.'&act=viewattempts&page='.$page.'&sort='.$sort.'&orderby='.$orderby.'&attemptid='.$attemptdata->id.'" onclick="alert(\'`'.$attemptdata->name.'` quiz  for `'.$attemptdata->username.' ('.$attemptdata->firstname.' '.$attemptdata->lastname.')` has been deleted\');">Delete</a>');
                }
                else
                {
                    $table->data[] = array (reader_user_link_t($attemptdata),
                                            reader_fullname_link_t($attemptdata), 
                                            $attemptdata->name, 
                                            $attemptdata->attempt, 
                                            $attemptdata->persent."%", 
                                            $passedstatus,
                                            $attemptbooktime);
                }
            }

            $table->data = reader_sort_table_data ($table->data, $titlesarray, $orderby, $sort);
            
            if ($excel) {
              foreach ($table->data as $tabledataarray) {
                $myxls->write_string($row, 0, strip_tags($tabledataarray[0]));
                $myxls->write_string($row, 1, strip_tags($tabledataarray[1]));
                $myxls->write_string($row, 2, $tabledataarray[2]);
                $myxls->write_string($row, 3, $tabledataarray[3]);
                $myxls->write_string($row, 4, $tabledataarray[4]);
                $myxls->write_string($row, 5, $tabledataarray[5]);
                $myxls->write_string($row, 6, $tabledataarray[6]);
                $row++;
              }
            }
            
            if ($excel) {
                $workbook->close();
                die();
            }
            
            echo '<table style="width:100%"><tr><td align="right">';
            echo $OUTPUT->single_button(new moodle_url("admin.php",$options), get_string("downloadexcel", "reader"), 'post', $options);
            echo '</td></tr></table>';
        }
        reader_print_search_form ($id, $act);
        
        $groups = groups_get_all_groups ($course->id);
        
        if ($groups) {
            reader_print_group_select_box($course->id, 'admin.php?a=admin&id='.$id.'&act=viewattempts&sort='.$sort.'&orderby='.$orderby);
        }
        
        reader_select_perpage($id, $act, $sort, $orderby, $grid);
        list($totalcount, $table->data, $startrec, $finishrec, $options["page"]) = reader_get_pages($table->data, $page, $perpage);
        $pagingbar = new paging_bar($totalcount, $page, $perpage, "admin.php?a=admin&id={$id}&act={$act}&sort={$sort}&orderby={$orderby}&grid={$grid}&amp;");
        echo $OUTPUT->render($pagingbar); 
        
//---------DEBUG&SPEED TEST------------S-//
reader_debug_speed_check(' &nbsp;&nbsp;&nbsp;&nbsp; viewattempts; before print_table & print_paging_bar : ');
//---------DEBUG&SPEED TEST------------E-//
        
        if ($table) {
            echo html_writer::table($table);
        }
        
        $pagingbar = new paging_bar($totalcount, $page, $perpage, "admin.php?a=admin&id={$id}&act={$act}&sort={$sort}&orderby={$orderby}&grid={$grid}&amp;");
        echo $OUTPUT->render($pagingbar); 
        
        if (has_capability('mod/reader:deletequizzes', $contextmodule)) {
          echo '<form action="?a=admin&id='.$id.'&act='.$act.'&sort='.$sort.'&orderby='.$orderby.'&grid='.$grid.'" method="post"><div> ';
          echo ' <div style="margin:20px 0;font-size:16px;">'.get_string("restoredeletedattempt", "reader").'</div>';
          echo '<div style="float:left;width:200px;">'.get_string("studentuserid", "reader").'</div>';
          echo '<div style="float:left;width:200px;"><input type="text" name="studentuserid" value="" style="width:120px;" /></div><div style="clear:both;"></div>';
          echo '<div>or</div>';
          echo '<div style="float:left;width:200px;">'.get_string("studentusername", "reader").'</div>';
          echo '<div style="float:left;width:200px;"><input type="text" name="studentusername" value="" style="width:120px;" /></div><div style="clear:both;"></div>';
          echo '<div style="float:left;width:200px;">'.get_string("bookquiznumber", "reader").'</div>';
          echo '<div style="float:left;width:200px;"><input type="text" name="bookquiznumber" value="" style="width:120px;" /></div><div style="clear:both;"></div>';
          //echo ' <input type="hidden" name="" value="" />';
          echo ' <input type="submit" name="submit" value="Restore" />';
          echo '</div></form>';
        }
//---------DEBUG&SPEED TEST------------S-//
reader_debug_speed_check(' &nbsp;&nbsp;&nbsp;&nbsp; viewattempts; after print_table & print_paging_bar : ');
//---------DEBUG&SPEED TEST------------E-//
        
      //}
    //---------------------------------View Students Levels------------------------------//
    } else if ($act == "studentslevels" && has_capability('mod/reader:changestudentslevelsandpromote', $contextmodule)) {
    
//---------DEBUG&SPEED TEST------------S-//
reader_debug_speed_check('Before studentslevels : ');
//---------DEBUG&SPEED TEST------------E-//
    
        $table = new html_table();
    
        $titlesarray = Array ('Image'=>'', 'Username'=>'username', 'Fullname<br />Click to view screen'=>'fullname', 'Start level'=>'startlevel', 'Current level'=>'currentlevel', 'NoPromote'=>'nopromote', 'Stop Promo At'=>'promotionstops', 'Goal'=>'goal');
        
        //----------If need to set individual strict users acsess-----//
        if ($reader->individualstrictip == 1) {
            $titlesarray['Restrict IP'] = '';
        }
        
        $table->head = reader_make_table_headers ($titlesarray, $orderby, $sort, '?a=admin&id='.$id.'&act='.$act.'&page='.$page.'&grid='.$grid.'&searchtext='.$searchtext);
        $table->align = array ("center", "left", "left", "center", "center", "center", "center", "center", "center");
        $table->width = "100%";
        
        if (!$grid) {
            $grid = NULL;
        }
        
        $coursestudents = get_enrolled_users($context, NULL, $grid);
        
        foreach ($coursestudents as $coursestudent) {
          if (reader_check_search_text($searchtext, $coursestudent)) {
            $studentlevel = $DB->get_record("reader_levels", array("userid" => $coursestudent->id,  "readerid" => $reader->id));
            $picture = $OUTPUT->user_picture($coursestudent,array($course->id, true, 0, true));
            
            if ($studentlevel->startlevel >= 0) {
                $startlevelt = reader_selectlevel_form ($coursestudent->id, $studentlevel, 'startlevel');
            }
            else
            {
                $startlevelt = $studentlevel->startlevel;
            }
            
            if ($studentlevel->currentlevel >= 0) {
                $currentlevelt = reader_selectlevel_form ($coursestudent->id, $studentlevel, 'currentlevel');
            }
            else
            {
                $currentlevelt = $studentlevel->currentlevel;
            }
            
            $nopromote     = reader_yes_no_box ($coursestudent->id, $studentlevel, 'nopromote', 1);
            $promotionstop = reader_promotion_stop_box ($coursestudent->id, $studentlevel, 'promotionstop', 2);
            $goalbox       = reader_goal_box ($coursestudent->id, $studentlevel, 'goal', 3, $reader);
            
            //----------If need to set individual strict users acsess-----//
            list($linkusername, $username) = reader_user_link_t($coursestudent);
            list($linkfullname, $username) = reader_fullname_link_t($coursestudent);
            
            if (has_capability('mod/reader:viewstudentreaderscreens', $contextmodule)) {
                $linkfullname = reader_fullname_link_viewasstudent($coursestudent, "grid={$grid}&searchtext={$searchtext}&page={$page}&sort={$sort}&orderby={$orderby}");
            }
            
            if ($reader->individualstrictip == 1) {
                $table->data[] = array ($picture,
                                        $linkusername,
                                        $linkfullname,
                                        array($startlevelt, $studentlevel->startlevel),
                                        array($currentlevelt, $studentlevel->currentlevel),
                                        array($nopromote, $studentlevel->nopromote),
                                        array($promotionstop, $studentlevel->promotionstop),
                                        array($goalbox, $studentlevel->goal),
                                        reader_selectip_form ($coursestudent->id, $reader));
            }
            else
            {
                $table->data[] = array ($picture,
                                        $linkusername,
                                        $linkfullname,
                                        array($startlevelt, $studentlevel->startlevel),
                                        array($currentlevelt, $studentlevel->currentlevel),
                                        array($nopromote, $studentlevel->nopromote),
                                        array($promotionstop, $studentlevel->promotionstop),
                                        array($goalbox, $studentlevel->goal));
            }
          }
        }
        
        $table->data = reader_sort_table_data ($table->data, $titlesarray, $orderby, $sort);
        
        
        if ($grid) {
            $levels = array(0,1,2,3,4,5,6,7,8,9,10);
            echo '<form action="?a=admin&id='.$id.'&act='.$act.'&sort='.$sort.'&orderby='.$orderby.'&grid='.$grid.'" method="post"><div> ';
            print_string("changestartlevel", "reader");
            echo ' <select name="changeallstartlevel">';
            foreach ($levels as $value) {
                echo '<option value="'.$value.'">'.$value.'</option>';
            }
            echo '</select> ';
            //echo ' <input type="hidden" name="" value="" />';
            echo ' <input type="submit" name="submit" value="Change" />';
            echo '</div></form>';
            
            $levels = array(0,1,2,3,4,5,6,7,8,9,10,11,12,13,14);
            echo '<form action="?a=admin&id='.$id.'&act='.$act.'&sort='.$sort.'&orderby='.$orderby.'&grid='.$grid.'" method="post"><div> ';
            print_string("changecurrentlevel", "reader");
            echo ' <select name="changeallcurrentlevel">';
            foreach ($levels as $value) {
                echo '<option value="'.$value.'">'.$value.'</option>';
            }
            echo '</select> ';
            //echo ' <input type="hidden" name="" value="" />';
            echo ' <input type="submit" name="submit" value="Change" />';
            echo '</div></form>';
            
            //Points goal for all students
            $levels = array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15);
            echo '<form action="?a=admin&id='.$id.'&act='.$act.'&sort='.$sort.'&orderby='.$orderby.'&grid='.$grid.'" method="post"><div> ';
            print_string("setuniformgoalinpoints", "reader");
            echo ' <select name="changeallcurrentgoal">';
            foreach ($levels as $value) {
                echo '<option value="'.$value.'">'.$value.'</option>';
            }
            echo '</select> ';
            //echo ' <input type="hidden" name="" value="" />';
            echo ' <input type="submit" name="submit" value="Change" />';
            echo '</div></form>';
            
            //Words goal for all students
            $levels = array(0,5000,6000,7000,8000,9000,10000,12500,15000,20000,25000,30000,35000,40000,45000,50000,55000,60000,65000,70000,75000,80000,85000,90000,95000,100000,125000,150000,175000,200000,250000,300000,350000,400000,450000,500000);
            if (!in_array($reader->goal, $levels) && !empty($reader->goal)) {
                for ($i=0; $i<count($levels); $i++) {
                    if ($reader->goal < $levels[$i+1] && $reader->goal > $levels[$i]) {
                        $levels2[] = $reader->goal;
                        $levels2[] = $levels[$i];
                    }
                    else
                    {
                        $levels2[] = $levels[$i];
                    }
                }
                $levels = $levels2;
            }
            echo '<form action="?a=admin&id='.$id.'&act='.$act.'&sort='.$sort.'&orderby='.$orderby.'&grid='.$grid.'" method="post"><div> ';
            print_string("setuniformgoalinwords", "reader");
            echo ' <select name="changeallcurrentgoal">';
            foreach ($levels as $value) {
                echo '<option value="'.$value.'">'.$value.'</option>';
            }
            echo '</select> ';
            //echo ' <input type="hidden" name="" value="" />';
            echo ' <input type="submit" name="submit" value="Change" />';
            echo '</div></form>';
            
            
            //"NoPromo"/"Promo" for all students
            $levels = array("Promo", "NoPromo");
            echo '<form action="?a=admin&id='.$id.'&act='.$act.'&sort='.$sort.'&orderby='.$orderby.'&grid='.$grid.'" method="post"><div> ';
            print_string("changeallto", "reader");
            echo ' <select name="changeallpromo">';
            foreach ($levels as $value) {
                echo '<option value="'.$value.'">'.$value.'</option>';
            }
            echo '</select> ';
            //echo ' <input type="hidden" name="" value="" />';
            echo ' <input type="submit" name="submit" value="Change" />';
            echo '</div></form>';
            
            
            //Stop Promo for all students
            $levels = array(0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15);
            echo '<form action="?a=admin&id='.$id.'&act='.$act.'&sort='.$sort.'&orderby='.$orderby.'&grid='.$grid.'" method="post"><div> ';
            print_string("changeallstoppromoto", "reader");
            echo ' <select name="changeallstoppromo">';
            foreach ($levels as $value) {
                echo '<option value="'.$value.'">'.$value.'</option>';
            }
            echo '</select> ';
            //echo ' <input type="hidden" name="" value="" />';
            echo ' <input type="submit" name="submit" value="Change" />';
            echo '</div></form>';
        }
        
        
        reader_print_search_form ($id, $act);
        
        $groups = groups_get_all_groups ($course->id);
        
        if ($groups) {
            reader_print_group_select_box($course->id, 'admin.php?a=admin&id='.$id.'&act='.$act.'&sort='.$sort.'&orderby='.$orderby);
        }
        
        reader_select_perpage($id, $act, $sort, $orderby, $grid);
        list($totalcount, $table->data, $startrec, $finishrec, $options["page"]) = reader_get_pages($table->data, $page, $perpage);
        $pagingbar = new paging_bar($totalcount, $page, $perpage, "admin.php?a=admin&id={$id}&act={$act}&sort={$sort}&orderby={$orderby}&grid={$grid}&amp;");
        echo $OUTPUT->render($pagingbar); 
        
//---------DEBUG&SPEED TEST------------S-//
reader_debug_speed_check(' &nbsp;&nbsp;&nbsp;&nbsp; viewattempts; before print_table & print_paging_bar : ');
//---------DEBUG&SPEED TEST------------E-//
        
        if ($table) {
            echo html_writer::table($table);
        }
        
        $pagingbar = new paging_bar($totalcount, $page, $perpage, "admin.php?a=admin&id={$id}&act={$act}&sort={$sort}&orderby={$orderby}&grid={$grid}&amp;");
        echo $OUTPUT->render($pagingbar); 
//---------DEBUG&SPEED TEST------------S-//
reader_debug_speed_check(' &nbsp;&nbsp;&nbsp;&nbsp; viewattempts; after print_table & print_paging_bar : ');
//---------DEBUG&SPEED TEST------------E-//
    //---------------------------------Change Reading Level or Length Factor------------------------------//
    } else if ($act == "changereaderlevel" && has_capability('mod/reader:changereadinglevelorlengthfactor', $contextmodule)) {
        //$reader->individualbooks = 1;
        $table = new html_table();
        
        if ($reader->individualbooks == 1) {
          $titlesarray = Array ('Title'=>'title', 'Publisher'=>'publisher', 'Level'=>'level', 'Reading Level'=>'readinglevel', 'Length'=>'length');
        } else {
          $titlesarray = Array ('Title'=>'title', 'Publisher'=>'publisher', 'Level'=>'level', 'Words'=>'words', 'Reading Level'=>'readinglevel', 'Length'=>'length');
        }
        
        //----------If need to set individual strict users acsess-----//
        $table->head = reader_make_table_headers ($titlesarray, $orderby, $sort, '?a=admin&id='.$id.'&act='.$act.'&grid='.$grid.'&publisher='.$publisher.'&page='.$page.'&searchtext='.$searchtext);
        if ($reader->individualbooks == 1) {
          $table->align = array ("left", "left", "left", "center", "center");
        } else {
          $table->align = array ("left", "left", "left", "center", "center", "center", "center");
        }
        $table->width = "100%";
        
        if ($publisher && $level) {
            $perpage = 1000;
        }
        
        if ($reader->individualbooks == 0) {
            $books = $DB->get_records("reader_publisher", array('hidden' => 0));
        }
        else
        {
            $books = $DB->get_records_sql("SELECT * FROM {reader_individual_books} ib INNER JOIN {reader_publisher} rp ON rp.id = ib.bookid AND ib.readerid= ? ", array($reader->id));
        }
        
        $totalgrade = 0;
        $totalpointsaverage = 0;
        $correctpoints = 0;
        
        foreach ($books as $book) {
          if (reader_check_search_text_quiz($searchtext, $book)) {
            if ((empty($publisher) || $publisher == $book->publisher) && (empty($level) || $level == $book->level)) {
                
                if (has_capability('mod/reader:manage', $contextmodule)) {
                    $wordstitle = "<div id=\"wordstitle_{$book->id}\"><input type=\"text\" id=\"wordstitle_input_{$book->id}\" name=\"wordstitle\" value=\"{$book->words}\" onkeyup=\"if(event.keyCode=='13') { request('admin.php?ajax=true&id={$id}&act={$act}&wordstitleid={$book->id}&wordstitlekey=' + document.getElementById('wordstitle_input_{$book->id}').value,'wordstitle_{$book->id}'); return false; }\" /></div>";
                } else {
                    $wordstitle = $book->words;
                }
                
                if (has_capability('mod/reader:manage', $contextmodule)) {
                    $leveltitle = "<div id=\"leveltitle_{$book->id}\"><input type=\"text\" id=\"leveltitle_input_{$book->id}\" name=\"leveltitle\" value=\"{$book->level}\" onkeyup=\"if(event.keyCode=='13') { request('admin.php?ajax=true&id={$id}&act={$act}&leveltitleid={$book->id}&leveltitlekey=' + document.getElementById('leveltitle_input_{$book->id}').value,'leveltitle_{$book->id}'); return false; }\" /></div>";
                } else {
                    $leveltitle = $book->level;
                }
                
                if (has_capability('mod/reader:manage', $contextmodule)) {
                    $publishertitle = "<div id=\"publishertitle_{$book->id}\"><input type=\"text\" id=\"publishertitle_input_{$book->id}\" name=\"publishertitle\" value=\"{$book->publisher}\" onkeyup=\"if(event.keyCode=='13') { request('admin.php?ajax=true&id={$id}&act={$act}&publishertitleid={$book->id}&publishertitlekey=' + document.getElementById('publishertitle_input_{$book->id}').value,'publishertitle_{$book->id}'); return false; }\" /></div>";
                } else {
                    $publishertitle = $book->publisher;
                }

                if ($reader->individualbooks == 1) {
                  $table->data[] = array ($book->name,
                                          $publishertitle,
                                          $leveltitle,
                                          //$sametitle,
                                          //$wordstitle,
                                          trim(reader_select_difficulty_form (reader_get_reader_difficulty($reader, $book->id), $book->id, $reader)),
                                          trim(reader_select_length_form (reader_get_reader_length($reader, $book->id), $book->id, $reader)));
                } else {
                  $table->data[] = array ($book->name,
                                          $publishertitle,
                                          $leveltitle,
                                          $wordstitle,
                                          trim(reader_select_difficulty_form (reader_get_reader_difficulty($reader, $book->id), $book->id, $reader)),
                                          trim(reader_select_length_form (reader_get_reader_length($reader, $book->id), $book->id, $reader)));
                }
            }
          }
        }
        
        if ($sort == "username") { $sort = "title"; }
        
        $table->data = reader_sort_table_data ($table->data, $titlesarray, $orderby, $sort);
        
        //---------------Select publisher and level form--------------//

        $publishers = $DB->get_records ("reader_publisher", NULL, 'publisher');

        $publisherform[$CFG->wwwroot.'/mod/reader/admin.php?a=admin&id='.$id.'&act='.$act.'&sort='.$sort.'&orderby='.$orderby] = "Select Publisher";
        foreach ($publishers as $publisher_) {
            $publisherform[$CFG->wwwroot.'/mod/reader/admin.php?a=admin&id='.$id.'&act='.$act.'&sort='.$sort.'&orderby='.$orderby.'&publisher='.$publisher_->publisher] = $publisher_->publisher;
        }
        
        if ($publisher) {
            print_string("massrename", "reader");
            echo ":";
        }
        
        echo '<form action="" method="get"  id="publisherselect"><div>';
        
        echo '<select id="publisher_select" name="publisher" onchange="self.location=document.getElementById(\'publisherselect\').publisher.options[document.getElementById(\'publisherselect\').publisher.selectedIndex].value;">';
        foreach ($publisherform as $key => $value) {
            echo '<option value="'.$key.'" ';
            if ($publisher == $value) { echo ' selected="selected" '; }
            echo '>'.$value.'</option>';
        }
        echo '</select>';
        
        if ($publisher) {
            $levelform[$CFG->wwwroot.'/mod/reader/admin.php?a=admin&id='.$id.'&act='.$act.'&sort='.$sort.'&orderby='.$orderby.'&publisher='.$publisher] = "Select Level";
            $levels = $DB->get_records ("reader_publisher", array('publisher' => $publisher), 'level');
            foreach ($levels as $levels_) {
                if (!$level || $levels_->level == $level) {
                    $level_[$levels_->level] = $levels_->id;
                    $difficulty_[$levels_->difficulty] = $levels_->id;
                    $length_[$levels_->length] = $levels_->id;
                }
                $levelform[$CFG->wwwroot.'/mod/reader/admin.php?a=admin&id='.$id.'&act='.$act.'&sort='.$sort.'&orderby='.$orderby.'&publisher='.$publisher.'&level='.$levels_->level] = $levels_->level;
            }
            
            echo '<select id="level_select" name="level" onchange="self.location=document.getElementById(\'publisherselect\').level.options[document.getElementById(\'publisherselect\').level.selectedIndex].value;">';
            foreach ($levelform as $key => $value) {
                echo '<option value="'.$key.'" ';
                if ($level == $value) { echo ' selected="selected" '; }
                echo '>'.$value.'</option>';
            }
            echo '</select>';
        }
        
        echo '</div></form>';

        if ($publisher) {
            if ($reader->individualbooks == 1) {
                unset($difficulty_,$length_);
                $data = $DB->get_records_sql("SELECT ib.difficulty as ibdifficulty,ib.length as iblength FROM {reader_publisher} rp INNER JOIN {reader_individual_books} ib ON ib.bookid = rp.id WHERE ib.readerid= ?  and rp.publisher = ? ", array($reader->id, $publisher));
                foreach ($data as $data_) {
                    $difficulty_[$data_->ibdifficulty] = $data_->bookid;
                    $length_[$data_->iblength] = $data_->bookid;
                }
            }
            
            echo '<form action="" method="post"><div> ';
            print_string("changepublisherfrom", "reader");
            echo ' <select name="publisher">';
            $pubto = array();
            foreach ($publishers as $key => $value) {
              if (!in_array($value->publisher, $pubto)) {
                $pubto[] = $value->publisher;
                echo '<option value="'.$value->publisher.'" ';
                if ($publisher == $value->publisher) { echo ' selected="selected" '; }
                echo '>'.$value->publisher.'</option>';
              }
            }
            echo '</select> ';
            print_string("to", "reader");
            echo ' <input type="text" name="topublisher" />';
            echo ' <input type="submit" name="submit" value="Change" />';
            
            echo '</div></form>';
        
            echo '<form action="" method="post"><div> ';
            print_string("changelevelfrom", "reader");
            echo ' <select name="level">';
            foreach ($level_ as $key => $value) {
                echo '<option value="'.$key.'" ';
                if ($level == $key) { echo ' selected="selected" '; }
                echo '>'.$key.'</option>';
            }
            echo '</select> ';
            print_string("to", "reader");
            echo ' <input type="text" name="tolevel" />';
            echo ' <input type="submit" name="submit" value="Change" />';
            
            echo '</div></form>';
            
            echo '<form action="" method="post"><div> ';
            //$lengtharray = array(0.50,0.60,0.70,0.80,0.90,1.00,1.10,1.20,1.30,1.40,1.50,1.60,1.70,1.80,1.90,2.00);
            $lengtharray = array(0.50,0.60,0.70,0.80,0.90,1.00,1.10,1.20,1.30,1.40,1.50,1.60,1.70,1.80,1.90,2.00,3.00,4.00,5.00,6.00,7.00,8.00,9.00,10.00,15,20,25,30,35,40,45,50,55,60,65,70,75,80,85,90,95,100,110,120,130,140,150,160,170,175,180,190,200,225,250,275,300,350,400);
            print_string("changelengthfrom", "reader");
            echo ' <select name="length">';
            ksort($length_); 
            reset($length_); 
            foreach ($length_ as $key => $value) {
                echo '<option value="'.$key.'" ';
                if ($length == $key) { echo ' selected="selected" '; }
                echo '>'.$key.'</option>';
            }
            echo '</select> ';
            print_string("to", "reader");
            echo ' <select name="tolength">';
            foreach ($lengtharray as $value) {
                echo '<option value="'.$value.'">'.$value.'</option>';
            }
            echo '</select>';
            echo ' <input type="submit" name="submit" value="Change" />';
            
            echo '</div></form>';
            
            echo '<form action="" method="post"><div> ';
            $difficultyarray = array(0,1,2,3,4,5,6,7,8,9,10,11,12);
            print_string("changedifficultyfrom", "reader");
            echo ' <select name="difficulty">';
            ksort($difficulty_); 
            reset($difficulty_); 
            foreach ($difficulty_ as $key => $value) {
                echo '<option value="'.$key.'" ';
                if ($difficulty == $key) { echo ' selected="selected" '; }
                echo '>'.$key.'</option>';
            }
            echo '</select> ';
            print_string("to", "reader");
            echo ' <select name="todifficulty">';
            foreach ($difficultyarray as $value) {
                echo '<option value="'.$value.'">'.$value.'</option>';
            }
            echo '</select>';
            echo ' <input type="submit" name="submit" value="Change" />';
            echo ' <input type="hidden" name="sctionoption" value="massdifficultychange" />';
            
            echo '</div></form>';
        }
        //-------------------------------------------------//

        list($totalcount, $table->data, $startrec, $finishrec, $options["page"]) = reader_get_pages($table->data, $page, $perpage);
        $pagingbar = new paging_bar($totalcount, $page, $perpage, "admin.php?a=admin&id={$id}&act={$act}&sort={$sort}&orderby={$orderby}&grid={$grid}&publisher={$publisher}&amp;");
        echo $OUTPUT->render($pagingbar); 
        if ($table) {
            echo html_writer::table($table);
        }
        
        $pagingbar = new paging_bar($totalcount, $page, $perpage, "admin.php?a=admin&id={$id}&act={$act}&sort={$sort}&orderby={$orderby}&grid={$grid}&publisher={$publisher}&amp;");
        echo $OUTPUT->render($pagingbar); 
    //---------------------------------Send Message------------------------------//
    } else if ($act == "sendmessage" && has_capability('mod/reader:sendmessage', $contextmodule)) {
        class mod_reader_message_form extends moodleform {
            function definition() {
                global $CFG, $course, $editmessage;
        
                $mform    =& $this->_form;
            
                $groups = groups_get_all_groups ($course->id);
                $grouparray = array ("0" => "All Course students");
            
                foreach ($groups as $group) {
                    $grouparray[$group->id] = $group->name;
                }
                
                $timearray = array("168" => "1 Week", "240" => "10 Days", "336" => "2 Weeks", "504" => "3 Weeks", "1000000" => "Indefinite");
            
                $mform->addElement('select', 'groupid', 'Group', $grouparray, 'size="5" multiple');
                $mform->addElement('select', 'activehours', 'Active Time (Hours)', $timearray);
                $mform->addElement('textarea', 'text', 'Text', 'wrap="virtual" rows="10" cols="70"');
                
                if ($editmessage) {
                    $message = $DB->get_record("reader_messages", array( "id" => $editmessage));
                    $mform->setDefault('text', $message->text);
                    $mform->addElement('hidden', 'editmessage', $editmessage);
                }
                $this->add_action_buttons(false, $submitlabel="Send");
            }
        }
        $mform = new mod_reader_message_form("admin.php?a=admin&id={$id}&act=sendmessage");
        $mform->display();
        
        echo "Current Messages:";
        
        $textmessages = $DB->get_records_sql("SELECT * FROM {reader_messages} where teacherid = ? and instance = ? ORDER BY timemodified DESC", array($USER->id, $cm->instance));
        
        foreach ($textmessages as $textmessage) {
            $before = $textmessage->timebefore - time();
            
            $forgroupsarray = explode (',', $textmessage->users);
            
            $forgroup = "";
            $bgcolor  = '';
            
            foreach ($forgroupsarray as $forgroupsarray_) {
                if ($forgroupsarray_ == 0) {
                    $forgroup .= 'All, ';
                }
                else
                {
                    $forgroup .= groups_get_group_name($forgroupsarray_).', ';
                }
            }
            
            $forgroup = substr($forgroup, 0, -2);
            
            if ($textmessage->timemodified > (time() - ( 48 * 60 * 60))) {
                $bgcolor = 'bgcolor="#CCFFCC"';
            }
            
            echo '<table width="100%"><tr><td align="right"><table cellspacing="0" cellpadding="0" class="forumpost blogpost blog" '.$bgcolor.' width="90%">';
            echo '<tr><td align="left"><div style="margin-left: 10px;margin-right: 10px;">'."\n";
            echo format_text($textmessage->text);
            echo '<div style="text-align:right"><small>';
            echo round($before/(60 * 60 * 24), 2).' Days; ';
            echo 'Added: '.date("d M Y H:i", $textmessage->timemodified)."; ";
            echo 'Group: '. $forgroup.'; ';
            echo '<a href="admin.php?a=admin&id='.$id.'&act=sendmessage&editmessage='.$textmessage->id.'">Edit</a> / <a href="admin.php?a=admin&id='.$id.'&act=sendmessage&deletemessage='.$textmessage->id.'">Delete</a>';
            echo '</small></div>';
            echo '</div></td></tr></table></td></tr></table>'."\n\n"; 
        }
    //---------------------------------Pix by Publisher------------------------------//
    } else if ($act == "makepix_t" && has_capability('mod/reader:createcoversetsbypublisherlevel', $contextmodule)) {
        $allbooks = $DB->get_records_sql("SELECT * FROM {reader_publisher}  where hidden = 0 ORDER BY publisher ASC, level ASC");
        $prehtml = "<img width='110'  height='160'  border='0' src='{$CFG->wwwroot}/file.php/{$reader->usecourse}/images/";
        $posthtml = '/> ';
        $nowpub = "";
        $nowlevel = "";
        $cellcount = -1;
        echo("<table border='0'");
        foreach ($allbooks as $thisbook) {
            if (($thisbook->publisher != $nowpub) || ($thisbook->level != $nowlevel) ){
                //close the row unless the row was closed because the last row was full
                if ($cellcount > 0) { 
                    echo ("</td></tr>");
                }
                $nowpub = $thisbook->publisher;
                $nowlevel = $thisbook->level ;
                echo("<tr ><td colspan='6' align='left'><font size=+2>$nowpub &nbsp; $nowlevel</font></td> </tr>");
                //let's make the cpu rest for 1 sec between sets so that the server isn't overloaded with requests
                echo("<tr valign='top' align='center'>");
                sleep(1);
                $cellcount = 0;
            }
            $thistitle = $thisbook->name;
            if ($cellcount > 5) {
                $cellcount = 1;
                echo("</td></tr> <tr valign='top' align='center'><td $prehtml" . "$thisbook->image" . "'$posthtml" . "  <br />  $thistitle  </td>");
            } else {
                $cellcount++;
                echo("<td>" . "$prehtml" . "$thisbook->image". "'$posthtml". "<br />  $thistitle </td>");
            }
       
        }
        echo("</tr></table>");
    //---------------------------------Pix by level------------------------------//
    } else if ($act == "makepix_l" && has_capability('mod/reader:createcoversetsbypublisherlevel', $contextmodule)) {
        $allbooks = $DB->get_records_sql("SELECT * FROM {reader_publisher}  where hidden = 0 ORDER BY difficulty, publisher, level, name");
        $prehtml = "<img width='110'  height='160'  border='0' src='{$CFG->wwwroot}/file.php/{$reader->usecourse}/images/";
        $posthtml = '/> ';
        $nowpub = "";
        $nowlevel = "";
        $nowdiff = "999";
        $cellcount = -1;
        echo("<table border='0'");
        foreach ($allbooks as $thisbook) {
            
            if (($thisbook->publisher != $nowpub) || ($thisbook->level != $nowlevel) ){
                if($nowdiff != $thisbook->difficulty) {
                    echo("<tr><td bgcolor='#999999' colspan ='6'><font size='5'>Reading Level: " . "$thisbook->difficulty" . "</font></td></tr>");
                    $nowdiff = $thisbook->difficulty;
                }
                //close the row unless the row was closed because the last row was full
                if ($cellcount > 0) { 
                    echo ("</td></tr>");
                }
                $nowpub = $thisbook->publisher;
                $nowlevel = $thisbook->level ;
                echo("<tr ><td colspan='6' align='left'><font size=+2>$nowpub &nbsp; $nowlevel</font></td> </tr>");
                //let's make the cpu rest for 1 sec between sets so that the server isn't overloaded with requests
                echo("<tr valign='top' align='center'>");
                sleep(1);
                $cellcount = 0;
            }
            $thistitle = $thisbook->name;
            if ($cellcount > 5) {
                $cellcount = 1;
                echo("</td></tr> <tr valign='top' align='center'><td $prehtml" . "$thisbook->image" . "'$posthtml" . "  <br />  $thistitle  </td>");
            } else {
                $cellcount++;
                echo("<td>" . "$prehtml" . "$thisbook->image". "'$posthtml". "<br />  $thistitle </td>");
            }
       
        }
        echo("</tr></table>");
    //---------------------------------Award Extra Points-------------------------------//
    } else if ($act == "awardextrapoints" && has_capability('mod/reader:awardextrapoints', $contextmodule)) {
        $table = new html_table();
        
        $groups = groups_get_all_groups ($course->id);
        
        $_SESSION['SESSION']->reader_perpage = 1000;
        
        if ($groups) {
            reader_print_group_select_box($course->id, 'admin.php?a=admin&id='.$id.'&act=awardextrapoints&sort='.$sort.'&orderby='.$orderby);
        }
        
        if ($grid) {
          if ($award && $student) {
            echo "<center><h2><font color=\"red\">Done</font></h2></center>";
          }
          else
          {
            echo '<form action="" method="post"><table width="100%"><tr><td align="right"><input type="button" value="Select all" onclick="checkall();" /> <input type="button" value="Deselect all" onclick="uncheckall();" /></td></tr></table>';
            $titlesarray = Array ('Image'=>'', 'Username'=>'username', 'Fullname'=>'fullname', 'Select Students'=>'');
        
            $table->head = reader_make_table_headers ($titlesarray, $orderby, $sort, '?a=admin&id='.$id.'&act=awardextrapoints&grid='.$grid);
            $table->align = array ("center", "left", "left", "center");
            $table->width = "100%";
            
            $coursestudents = get_enrolled_users($context, NULL, $grid);
            
            foreach ($coursestudents as $coursestudent) {
                $picture = $OUTPUT->user_picture($coursestudent,array($course->id, true, 0, true));
                $table->data[] = array ($picture,
                                        reader_user_link_t($coursestudent),
                                        reader_fullname_link_t($coursestudent),
                                        '<input type="checkbox" name="student[]" value="'.$coursestudent->id.'" />');
            }
            
            $table->data = reader_sort_table_data ($table->data, $titlesarray, $orderby, $sort);

            if ($table) {
                echo html_writer::table($table);
            }
 //fixed by Tom 4 July 2010           
            $awardpoints = array("0.5 pt/500 Words" => "0.5 points","1 pt/1000 Words" => "One point", "2 pts/2000 Words" => "Two points", "3 pts/4000 Words" => "Three points", "4 Pts/8000 Words" => "Four points", "5 Pts/16000 Words" => "Five points");            
            echo '<center><select id="Award_point" name="award">';
            foreach ($awardpoints as $key => $value) {
                echo '<option value="'.$value.'">'.$key.'</option>';
            }
            echo '</select>';
            
            echo '<input type="submit" name="submit" value="GO!" /></center></form>';
          }
        }
        else
        {
            echo "<center><h2><font color=\"red\">".get_string("pleasespecifyyourclassgroup", "reader")."</font></h2></center>";
        }
    //--------------------------------Check logs for suspicious activity----------------------------//
    } else if ($act == "checksuspiciousactivity" && has_capability('mod/reader:checklogsforsuspiciousactivity', $contextmodule)) {
//---------DEBUG&SPEED TEST------------S-//
reader_debug_speed_check(' checksuspiciousactivity before search : ');
//---------DEBUG&SPEED TEST------------E-//

        $table = new html_table();
        
        echo '<form action="admin.php?a='.$a.'&id='.$id.'&act='.$act.'" method="post">';
        echo get_string("checkonlythiscourse", "reader").' <input type="checkbox" name="useonlythiscourse" value="yes" checked /><br />';
        echo get_string("withoutdayfilter", "reader").' <input type="checkbox" name="withoutdayfilter" value="yes" /><br />';
        echo get_string("selectipmask", "reader").' <select id="ip_mask" name="ipmask"><br />';
        $ipmaskselect = array("2" => "xxx.xxx.", "3" => "xxx.xxx.xxx.");
        foreach ($ipmaskselect as $key => $value) {
            echo '<option value="'.$key.'"';
            if ($key == $ipmask) echo ' selected="selected" ';
            echo '>'.$value.'</option>';
        }
        echo '</select><br />';
        //echo 'Other ip mask <input type="text" name="ipmaskother" value="" />';
        echo get_string("fromthistime", "reader").' <select id="from_time" name="fromtime">';
    //change by Tom 28 June 2010
        $fromtimeselect = array("86400" => "Day", "604800" => "Week", "2419200" => "Month", "5270400" => "2 Months", "7862400" => "3 Months");
        foreach ($fromtimeselect as $key => $value) {
            echo '<option value="'.$key.'"';
            if ($key == $fromtime) echo ' selected="selected" ';
            echo '>'.$value.'</option>';
        }
        echo '</select><br />';
        echo get_string("maxtimebetweenquizzes", "reader").' <select id="max_time" name="maxtime">';
        $fromtimeselect = array("900" => "15 Minutes", "1800" => "30 Minutes", "2700" => "45 Minutes", "3600" => "Hour", "10800" => "3 Hours", "21600" => "6 Hours", "43200" => "12 Hours", "86400" => "Day");
        foreach ($fromtimeselect as $key => $value) {
            echo '<option value="'.$key.'"';
            if ($key == $maxtime) echo ' selected="selected" ';
            echo '>'.$value.'</option>';
        }
        echo '</select><br />';
        echo '<input type="submit" name="findcheated" value="Go" /><br />';
        echo '</form>';

        if ($findcheated) {
            $order='l.time DESC';
            
            if ($useonlythiscourse) $usecoursesql = "course = '{$course->id}' and";
            if ($fromtime) $fromtimesql = "time > '".(time() - $fromtime)."' and";
            
            $select = " {$usecoursesql} {$fromtimesql} module = 'reader' and info LIKE 'readerID%; reader quiz%; %/%' ";
            $countsql = (strlen($select) > 0) ? ' WHERE '. $select : '';
            $totalcount = $DB->count_records_sql("SELECT COUNT(*) FROM {log} l {$countsql}");
            $logtext = $DB->get_records_sql("SELECT * FROM {log} l $countsql") ;
            foreach ($logtext as $logtext_) {
                if (preg_match("!reader quiz (.*?); !si",$logtext_->info,$quizid)) $quizid=$quizid[1]; 
                if ($quizid) {
                    $allips[$quizid][$logtext_->id] = $logtext_->ip;
                }
            }
            
            
            //print_r ($allips);
    //---------DEBUG&SPEED TEST------------S-//
    reader_debug_speed_check(' checksuspiciousactivity after search : ');
    //---------DEBUG&SPEED TEST------------E-//
    
            $comparearr   = array();
            $checkerarray =  array();
            $compare      =  array();
            
            foreach ($allips as $quize => $val) {
                foreach ($val as $resultid => $resultip) {
                    list($ip1,$ip2,$ip3,$ip4) = explode(".",$resultip);
                    if ($ipmask == 2) {
                        $ipmaskcheck = "$ip1.$ip2";
                    }
                    else
                    {
                        $ipmaskcheck = "$ip1.$ip2.$ip3";
                    } 
                    
                    $checkerarray[$quize][$resultid] = $ipmaskcheck;
                }
            }
            
            foreach ($checkerarray as $quize => $val) {
                foreach ($val as $resultid => $resultip) {
                    $comparearr[$quize][$resultip][] = $resultid;
                }
            }

            foreach ($comparearr as $key => $value) {
              foreach ($value as $key2 => $value2) {
                if (count($value2) <= 1) unset($comparearr[$key][$key2]);
              }
            }

            
            $titlesarray = Array ('Book'=>'book', 'Username 1'=>'username1', 'Username 2'=>'username2', 'IP 1'=>'', 'IP 2'=>'', 'Time 1'=>'', 'Time 2'=>'', 'Time period'=>'', 'Log text'=>'');
            
            $table->head  = reader_make_table_headers ($titlesarray, $orderby, $sort, '?a=admin&id='.$id.'&act='.$act);
            $table->align = array ("left", "left", "left", "center", "center", "center", "center", "center", "left");
            $table->width = "100%";
            
            foreach ($comparearr as $quizid => $result) {
              if (count($result) >= 1) {
                foreach ($result as $key => $logid) {
                  if ($logtext[$logid[0]]->userid != $logtext[$logid[1]]->userid) {
                    $diff = $logtext[$logid[0]]->time - $logtext[$logid[1]]->time;
                    if ($diff < 0) $diff = (int)substr($diff, 1);
                    if ($maxtime > $diff || $withoutdayfilter == "yes") {
                      $bookdata  = $DB->get_record("reader_publisher", array("id" => $quizid));
                      $user1dta  = $DB->get_record("user", array("id" => $logtext[$logid[0]]->userid));
                      $user2data = $DB->get_record("user", array("id" => $logtext[$logid[1]]->userid));
                      if ($diff < 3600) {
                          $diffstring = round($diff/60)." minutes";
                      }
                      else
                      {
                          $diffstring = round($diff/3600)." hours";
                      }
                      
                      $raid1 = (int)str_replace("view.php?id=", "", $logtext[$logid[0]]->url);
                      $raid2 = (int)str_replace("view.php?id=", "", $logtext[$logid[1]]->url);
                      
                      $readerattempt = array();
                      
                      $readerattempt[1] = $DB->get_record("reader_attempts", array("id" => $raid1));
                      $readerattempt[2] = $DB->get_record("reader_attempts", array("id" => $raid2));
                      
                      
                      
                      if ($readerattempt[1]->id && $readerattempt[2]->id) {
                          $cheatedstring = '<script>$(document).ready(function() {
                          $("#cheated-link-'.$readerattempt[1]->id.'_'.$readerattempt[2]->id.'").click(function() {
                            if(confirm(\'Cheated ?\')) {
                              $.post("admin.php", { a: "'.$a.'", id: "'.$id.'", act: "'.$act.'", useonlythiscourse: "'.$useonlythiscourse.'",ipmask: "'.$ipmask.'", fromtime: "'.$fromtime.'", maxtime: "'.$maxtime.'", cheated: "'.$readerattempt[1]->id.'_'.$readerattempt[2]->id.'" } );
                              $("#cheated-div-'.$readerattempt[1]->id.'_'.$readerattempt[2]->id.'").html("done");
                              return false;
                            } else { return false; }
                          }
                          );
                          });</script><div id="cheated-div-'.$readerattempt[1]->id.'_'.$readerattempt[2]->id.'"><a href="#" id="cheated-link-'.$readerattempt[1]->id.'_'.$readerattempt[2]->id.'">cheated</a></div>';
                          
                          //echo $cheatedstring ;
                          
                          if ($readerattempt[1]->passed != "cheated") {
                              if (strstr(strtolower($logtext[$logid[0]]->info), "passed")) {
                                  $logstatus[1] = 'passed';
                              }
                              else
                              {
         //change by Tom 28 June 2010
                                  $logstatus[1] = 'failed';
                              }
                          }
                          else
                          {
                              $logstatus[1] = '<font color="red">cheated</font>';
                              
                              $cheatedstring = '<script>$(document).ready(function() {
                          $("#cheated-link-'.$readerattempt[1]->id.'_'.$readerattempt[2]->id.'").click(function() {
                            if(confirm(\'Set passed ?\')) {
                              $.post("admin.php", { a: "'.$a.'", id: "'.$id.'", act: "'.$act.'", useonlythiscourse: "'.$useonlythiscourse.'",ipmask: "'.$ipmask.'", fromtime: "'.$fromtime.'", maxtime: "'.$maxtime.'", uncheated: "'.$readerattempt[1]->id.'_'.$readerattempt[2]->id.'" } );
                              $("#cheated-div-'.$readerattempt[1]->id.'_'.$readerattempt[2]->id.'").html("done");
                              return false;
                            } else { return false; }
                          }
                          );
                          });</script><div id="cheated-div-'.$readerattempt[1]->id.'_'.$readerattempt[2]->id.'"><a href="#" id="cheated-link-'.$readerattempt[1]->id.'_'.$readerattempt[2]->id.'">Set passed</a></div>';
                          }
                          
                          if ($readerattempt[1]->passed != "cheated") {
                              if (strstr(strtolower($logtext[$logid[1]]->info), "passed")) {
                                  $logstatus[2] = 'passed';
                              }
                              else
                              {
          //change by Tom 28 June 2010
                                  $logstatus[2] = 'failed';
                              }
                          }
                          else
                          {
                              $logstatus[2] = '<font color="red">cheated</font>';
                          }
                          if (!has_capability('mod/reader:checklogsforsuspiciousactivity', $contextmodule)) $cheatedstring = '';
                          
                          
                          $usergroups  = reader_groups_get_user_groups($user1dta->id);
                          $groupsuser1 = groups_get_group_name($usergroups[0][0]);
                          
                          $usergroups  = reader_groups_get_user_groups($user2data->id);
                          $groupsuser2 = groups_get_group_name($usergroups[0][0]);
                          
                          $table->data[] = array ($bookdata->name."<br />".$cheatedstring,
                                                                  "<a href=\"{$CFG->wwwroot}/user/view.php?id={$logtext[$logid[0]]->userid}&course={$course->id}\">{$user1dta->username} ({$user1dta->firstname} {$user1dta->lastname}; group: {$groupsuser1})</a><br />".$logstatus[1],
                          "<a href=\"{$CFG->wwwroot}/user/view.php?id={$logtext[$logid[1]]->userid}&course={$course->id}\">{$user2data->username} ({$user2data->firstname} {$user2data->lastname}; group: {$groupsuser2})</a><br />".$logstatus[2],
                          link_to_popup_window("{$CFG->wwwroot}/iplookup/index.php?ip={$logtext[$logid[0]]->ip}&amp;user={$logtext[$logid[0]]->userid}", $logtext[$logid[0]]->ip, 440, 700, null, null, true),
                          link_to_popup_window("{$CFG->wwwroot}/iplookup/index.php?ip={$logtext[$logid[1]]->ip}&amp;user={$logtext[$logid[1]]->userid}", $logtext[$logid[1]]->ip, 440, 700, null, null, true), 
                          date("D d F H:i", $logtext[$logid[0]]->time),
                          date("D d F H:i", $logtext[$logid[1]]->time),
                          $diffstring,
                          $logtext[$logid[0]]->info."<br />".$logtext[$logid[1]]->info);
                      }
                    }
                  }
                }
              }
            }
            
    //---------DEBUG&SPEED TEST------------S-//
    reader_debug_speed_check(' find same ip : ');
    //---------DEBUG&SPEED TEST------------E-//
            
            $table->data = reader_sort_table_data ($table->data, $titlesarray, $orderby, $sort);
            
            if ($table) {
                echo html_writer::table($table);
            }
    
            //echo $totalcount;
            //print_r ($quizzes);
        }
        
    //--------------------------------Summary Report by Class Group-------------------------------//
   } else if ($act == "reportbyclass" && has_capability('mod/reader:readerviewreports', $contextmodule)) { 
        $groups = groups_get_all_groups ($course->id);
        
        $table = new html_table();
        
        $titlesarray = Array ('Group name'=>'groupname', 'Students with<br /> no quizzes'=>'noquizzes', 'Students with<br /> quizzes'=>'quizzes', 'Percent with<br /> quizzes'=>'quizzes', 'Average Taken<br /> Quizzes'=>'takenquizzes', 'Average Passed<br /> Quizzes'=>'passedquizzes', 'Average Failed<br /> Quizzes'=>'failedquizzes', 'Average total<br /> points'=>'totalpoints', 'Average words<br /> this term'=>'averagewordsthisterm', 'Average words<br /> all terms'=>'averagewordsallterms');
        
        $table->head = reader_make_table_headers ($titlesarray, $orderby, $sort, '?a=admin&id='.$id.'&act='.$act.'&grid='.$grid.'&searchtext='.$searchtext.'&page='.$page.'&fromtime='.$fromtime);
        $table->align = array ("left", "center", "center", "center", "center", "center", "center", "center", "center");
        $table->width = "100%";
        
        
        if ($excel) {
            $myxls->write_string(0, 0, 'Summary Report by Class Group',$formatbc);
            $myxls->write_string(1, 0, 'Date: '.$exceldata['time'].'; Course name: '.$exceldata['course_shotname']);
            $myxls->set_row(0, 30);
            $myxls->set_column(0,1,20);
            $myxls->set_column(2,10,15);
            
            $myxls->write_string(2, 0, 'Group name',$formatbc);
            $myxls->write_string(2, 1, 'Students with no quizzes',$formatbc);
            $myxls->write_string(2, 2, 'Students with quizzes',$formatbc);
            $myxls->write_string(2, 3, 'Percent with Quizzes',$formatbc);
            $myxls->write_string(2, 4, 'Average Taken Quizzes',$formatbc);
            $myxls->write_string(2, 5, 'Average Passed Quizzes',$formatbc);
            $myxls->write_string(2, 6, 'Average Failed Quizzes',$formatbc);
            $myxls->write_string(2, 7, 'Average total points',$formatbc);
            $myxls->write_string(2, 8, 'Average words this term',$formatbc);
            $myxls->write_string(2, 9, 'Average words all terms',$formatbc);
        }
        
        foreach ($groups as $group) {
            unset($data);
            $data['averagetaken']         = 0;
            $data['averagepassed']        = 0;
            $data['averagepoints']        = 0;
            $data['averagefailed']        = 0;
            $data['withquizzes']          = 0;
            $data['withoutquizzes']       = 0;
            $data['averagewordsthisterm'] = 0;
            $data['averagewordsallterms'] = 0;
            
            $coursestudents = get_enrolled_users($context, NULL, $group->id);
            foreach ($coursestudents as $coursestudent) {
                if ($attempts = $DB->get_records_sql("SELECT * FROM {reader_attempts} WHERE userid= ?  and reader= ?  and timestart > ?", array($coursestudent->id, $reader->id, $reader->ignordate))) {
                    $data['averagetaken'] += count($attempts);
                    foreach ($attempts as $attempt) {
                        if (strtolower($attempt->passed) == "true") {
                            $data['averagepassed']++;
                            $bookdata = $DB->get_record("reader_publisher", array("id" => $attempt->quizid));
                            $data['averagepoints'] += reader_get_reader_length($reader, $bookdata->id);
                            $data['averagewordsthisterm'] += $bookdata->words;
                        }
                        else
                        {
                            $data['averagefailed']++; 
                        }
                    }
                    $data['withquizzes'] ++;
                }
                else
                {
                    $data['withoutquizzes'] ++;
                }
                
                if ($attempts = $DB->get_records_sql("SELECT * FROM {reader_attempts} WHERE userid= ? ", array($coursestudent->id))) {
                    foreach ($attempts as $attempt) {
                        if (strtolower($attempt->passed) == "true") {
                            $bookdata = $DB->get_record("reader_publisher", array("id" => $attempt->quizid));
                            $data['averagewordsallterms'] += $bookdata->words;
                        }
                    }
                }
            }
            $table->data[] = array ($group->name,
                                    $data['withoutquizzes'],
                                    $data['withquizzes'],
                                    round(($data['withquizzes']/($data['withquizzes']+$data['withoutquizzes']))*100 ,1) ."%",  
                                    round($data['averagetaken']/count($coursestudents),1),
                                    round($data['averagepassed']/count($coursestudents),1),
                                    round($data['averagefailed']/count($coursestudents),1),
                                    round($data['averagepoints']/count($coursestudents),1),
                                    round($data['averagewordsthisterm']/count($coursestudents)),
                                    round($data['averagewordsallterms']/count($coursestudents))
                                    );
        }
        
        $table->data = reader_sort_table_data ($table->data, $titlesarray, $orderby, $sort);
        
        if ($excel) {
          foreach ($table->data as $tabledataarray) {
            $myxls->write_string($row, 0, $tabledataarray[0]);
            $myxls->write_number($row, 1, (int) $tabledataarray[1]);
            $myxls->write_number($row, 2, (int) $tabledataarray[2]);
            $myxls->write_number($row, 3, (int) $tabledataarray[3]);
            $myxls->write_number($row, 4, (int) $tabledataarray[4]);
            $myxls->write_number($row, 5, (int) $tabledataarray[5]);
            $myxls->write_string($row, 6, (int) $tabledataarray[6]);
            $myxls->write_string($row, 7, (int) $tabledataarray[7]);
            $myxls->write_string($row, 8, (int) $tabledataarray[8]);
            $myxls->write_string($row, 9, (int) $tabledataarray[9]);
            $row++;
          }
        }

        if ($excel) {
            $workbook->close();
            die();
        }
                
        echo '<table style="width:100%"><tr><td align="right">';
        echo $OUTPUT->single_button(new moodle_url("admin.php",$options), get_string("downloadexcel", "reader"), 'post', $options);
        echo '</td></tr></table>';

        echo '<table style="width:100%"><tr><td align="right">';
        echo '<form action="#" id="getfromdate" class="popupform"><select name="fromtime" onchange="self.location=document.getElementById(\'getfromdate\').fromtime.options[document.getElementById(\'getfromdate\').fromtime.selectedIndex].value;"><option value="admin.php?a=admin&id='.$id.'&act='.$act.'&sort='.$sort.'&orderby='.$orderby.'&grid='.$grid.'&perpage='.$page.'&fromtime=0"';
        if ($fromtime == 86400 || !$fromtime) echo ' selected="selected" ';
        echo '>All time</option><option value="admin.php?a=admin&id='.$id.'&act='.$act.'&sort='.$sort.'&orderby='.$orderby.'&grid='.$grid.'&perpage='.$page.'&fromtime='.$reader->ignordate.'"';
        if ($fromtime > 86400) echo ' selected="selected" ';
        echo '>Current Term</option></select></form>';
        echo '</td></tr></table>';
       
        reader_select_perpage($id, $act, $sort, $orderby, $grid);
        list($totalcount, $table->data, $startrec, $finishrec, $options["page"]) = reader_get_pages($table->data, $page, $perpage);
        $pagingbar = new paging_bar($totalcount, $page, $perpage, "admin.php?a=admin&id={$id}&act={$act}&sort={$sort}&orderby={$orderby}&grid={$grid}&fromtime={$fromtime}&amp;");
        echo $OUTPUT->render($pagingbar); 
        
       
        if ($table) {
            echo html_writer::table($table);
        }
        
        $pagingbar = new paging_bar($totalcount, $page, $perpage, "admin.php?a=admin&id={$id}&act={$act}&sort={$sort}&orderby={$orderby}&grid={$grid}&fromtime={$fromtime}&amp;");
        echo $OUTPUT->render($pagingbar); 
    //--------------------------------Summary Report by Class Group-------------------------------//
    } else if ($act == "setgoal" && has_capability('mod/reader:setgoal', $contextmodule)) {
        class reader_setgoal_form extends moodleform {
            function definition() {
                global $COURSE, $CFG, $reader, $course, $DB;
                
                $mform    =& $this->_form;
                $mform->addElement('header', 'setgoal', get_string('setgoal', 'reader')); 
                $mform->addElement('select', 'wordsorpoints', get_string('wordsorpoints', 'reader'), array("points"=>get_string('points', 'reader'), "words"=>get_string('words', 'reader')));
                $groups = array("0"=>get_string('allparticipants', 'reader'));
                if ($usergroups = groups_get_all_groups($course->id)){
                    foreach ($usergroups as $group){
                        $groups[$group->id] = $group->name;
                    }
                    $mform->addElement('select', 'separategroups', get_string('separategroups', 'reader'), $groups);
                }
                $mform->addElement('text', 'levelall', get_string('all', 'reader'), array('size'=>'10'));
                for($i=1; $i<=10; $i++) {
                    $mform->addElement('text', 'levelc['.$i.']', $i, array('size'=>'10'));
                }
                
                if ($data = $DB->get_records("reader_goal", array("readerid" => $reader->id))) {
                    foreach ($data as $data_) {
                        if (empty($data_->level)){
                            $mform->setDefault('levelall', $data_->goal);
                        }
                        else
                        {
                            $mform->setDefault('levelc['.$data_->level.']', $data_->goal);
                        }
                        if ($data_->groupid) $mform->setDefault('separategroups', $data_->groupid);
                        if ($data_->goal < 100) {
                            $mform->setDefault('wordsorpoints', 'points');
                        } else {
                            $mform->setDefault('wordsorpoints', 'words');
                        }
                    }
                }
                else if ($reader->goal) {
                    $mform->setDefault('levelall', $reader->goal);
                    if ($reader->goal < 100) {
                        $mform->setDefault('wordsorpoints', 'points');
                    } else {
                        $mform->setDefault('wordsorpoints', 'words');
                    }
                }
                $this->add_action_buttons(false, $submitlabel="Save");
            }
        }
        $mform = new reader_setgoal_form('admin.php?a='.$a.'&id='.$id.'&act='.$act);
        $mform->display();
    //--------------------------------Set forced time delay-------------------------------//
    } else if ($act == "forcedtimedelay" && has_capability('mod/reader:forcedtimedelay', $contextmodule)) {
        class reader_forcedtimedelay_form extends moodleform {
            function definition() {
                global $COURSE, $CFG, $reader, $course,$DB;
                
                if ($default = $DB->get_record("reader_forcedtimedelay", array( "readerid" => $reader->id,  'level' => 99))) {
                  if ($default->delay) {
                    $defdelaytime = round($default->delay / 3600);
                  }
                }
                else
                {
                    $defdelaytime = $reader->attemptsofday * 24;
                }
                
                $dtimes = array(0=>'Default ('.$defdelaytime.')', 1=>'Without delay', 14400=>4, 28800=>8, 43200=>12, 57600=>16, 86400=>24, 129600=>36, 172800=>48, 259200=>72, 345600=>96, 432000=>120);
                
                $mform    =& $this->_form;
                $mform->addElement('header', 'forcedtimedelay', get_string("forcedtimedelay", "reader")." (hours)"); 
                $groups = array("0"=>get_string('allparticipants', 'reader'));
                if ($usergroups = groups_get_all_groups($course->id)){
                    foreach ($usergroups as $group){
                        $groups[$group->id] = $group->name;
                    }
                    $mform->addElement('select', 'separategroups', get_string('separategroups', 'reader'), $groups);
                }
                $mform->addElement('select', 'levelc[99]', get_string('all', 'reader'), $dtimes);
                for($i=1; $i<=10; $i++) {
                    $mform->addElement('select', 'levelc['.$i.']', $i, $dtimes);
                }
                
                /* SET default */
                $data = $DB->get_records ("reader_forcedtimedelay", array("readerid" => $reader->id));
                foreach ($data as $data_) {
                    if ($data_->level == 99) 
                      $mform->setDefault('levelall', $data_->delay);
                    else
                      $mform->setDefault('levelc['.$data_->level.']', $data_->delay);
                }
                
                $this->add_action_buttons(false, $submitlabel="Save");
            }
        }
        $mform = new reader_forcedtimedelay_form('admin.php?a='.$a.'&id='.$id.'&act='.$act);
        $mform->display();
    //--------------------------------Display student book ratings for each book level-------------------------------//
    } else if ($act == "bookratingslevel" && has_capability('mod/reader:readerviewreports', $contextmodule)) {
        $table = new html_table();

        echo '<form action="admin.php?a='.$a.'&id='.$id.'&act='.$act.'" method="post">';
        echo get_string("best", "reader").' <select id="booksratingbest" name="booksratingbest">';
        $fromselect = array("5" => "5", "10" => "10", "25" => "25", "50" => "50", "0" => "All");
        foreach ($fromselect as $key => $value) {
            echo '<option value="'.$key.'"';
            if ($key == $booksratingbest) echo ' selected="selected" ';
            echo '>'.$value.'</option>';
        }
        echo '</select><br />';

        echo get_string("showlevel", "reader").' <select id="booksratinglevel" name="booksratinglevel"><br />';
        $fromselect = array("0" => "0", "1" => "1", "2" => "2", "3" => "3", "4" => "4", "5" => "5", "6" => "6", "7" => "7", "8" => "8", "9" => "9", "10" => "10", "11" => "11", "12" => "12", "13" => "13", "14" => "14", "15" => "15", "99" => "All");
        foreach ($fromselect as $key => $value) {
            echo '<option value="'.$key.'"';
            if ($key == $booksratinglevel) echo ' selected="selected" ';
            echo '>'.$value.'</option>';
        }
        echo '</select><br />';
        //echo 'Other ip mask <input type="text" name="ipmaskother" value="" />';
        echo get_string("term", "reader").' <select id="booksratingterm" name="booksratingterm">';
        $fromselect = array("0" => "All terms", $reader->ignordate => "Current");
        foreach ($fromselect as $key => $value) {
            echo '<option value="'.$key.'"';
            if ($key == $booksratingterm) echo ' selected="selected" ';
            echo '>'.$value.'</option>';
        }
        echo '</select><br />';
        echo get_string("onlybookswithmorethan", "reader").' <select id="booksratingwithratings" name="booksratingwithratings">';
        $fromselect = array("0" => "0", "5" => "5", "10" => "10", "25" => "25", "50" => "50");
        foreach ($fromselect as $key => $value) {
            echo '<option value="'.$key.'"';
            if ($key == $booksratingwithratings) echo ' selected="selected" ';
            echo '>'.$value.'</option>';
        }
        echo '</select> '.get_string("ratings", "reader").':<br />';
        echo '<input type="submit" name="booksratingshow" value="Go" /><br />';
        echo '</form>';

        if ($booksratingshow) {
            if ($booksratinglevel == 99) {
                $findallbooks = $DB->get_records("reader_publisher");
            }
            else
            {
                $findallbooks = $DB->get_records_sql("SELECT * FROM {reader_publisher} WHERE difficulty = ?", array($booksratinglevel));
            }
            
            foreach ($findallbooks as $findallbook) {
                $findallattempts = $DB->get_records_sql("SELECT * FROM {reader_attempts} WHERE timefinish >= ? and quizid = ?", array($booksratingterm, $findallbook->id));
                $ratingsummary = "";
                $contof = 0;
                foreach ($findallattempts as $findallattempt) {
                    $ratingsummary += $findallattempt->bookrating;
                    $contof++;
                }
                $findallbook->ratingsummary = $ratingsummary;
                $findallbook->ratingcount = $contof;
                $findallbook->ratingaverage = round((($ratingsummary/$contof)*3.3)+0.1,1);
                if ($contof >= $booksratingwithratings) $data[] = $findallbook;
            }
            $data = reader_order_object($data, "ratingaverage");
            
            $titlesarray = Array ('Book Title'=>'booktitle', 'Publisher'=>'publisher', 'R. Level'=>'level', 'Avg Rating'=>'avrating', 'No. of Ratings'=>'nrating');
            
            $table->head = reader_make_table_headers ($titlesarray, $orderby, $sort, '?a=admin&id='.$id.'&act='.$act.'&booksratingbest='.$booksratingbest.'&booksratinglevel='.$booksratinglevel.'&booksratingterm='.$booksratingterm.'&booksratingwithratings='.$booksratingwithratings."&booksratingshow=Go");
            $table->align = array ("left", "left", "center", "center", "center");
            $table->width = "100%";
            
            //echo $booksratingbest;
            
            if ($booksratingbest) {
                foreach ($data as $data_) {
                    $datares[$data_->id][0] = $data_->id;
                    $datares[$data_->id][1] = $data_->name;
                    $datares[$data_->id][2] = $data_->publisher;
                    $datares[$data_->id][3] = $data_->ratingaverage;
                    $datares[$data_->id][4] = $data_->ratingcount;
                }
                $i=0;
                unset($data);
                foreach ($datares as $datares_) {
                  $i++;
                  if ($i<=$booksratingbest) {
                    $data[$datares_[0]]->id = $datares_[0];
                    $data[$datares_[0]]->name = $datares_[1];
                    $data[$datares_[0]]->publisher = $datares_[2];
                    $data[$datares_[0]]->ratingaverage = $datares_[3];
                    $data[$datares_[0]]->ratingcount = $datares_[4];
                  }
                }
            }
            
            foreach ($data as $data_) {
                $table->data[] = array ($data_->name,
                                    $data_->publisher,
                                    reader_get_reader_difficulty($reader, $data_->id),
                                    $data_->ratingaverage,
                                    $data_->ratingcount);
            }
            $table->data = reader_sort_table_data ($table->data, $titlesarray, $orderby, $sort);
            
            if ($table) {
                echo html_writer::table($table);
            }
        }
    //--------------------------------Individual books listing-------------------------------//
    } else if ($act == "setindividualbooks" && has_capability('mod/reader:selectquizzestomakeavailabletostudents', $contextmodule)) {
        
        if ($reader->individualbooks == 0) echo '<div>'.get_string('coursespecificquizselection', 'reader').'</div>';
        
    //---------DEBUG&SPEED TEST------------S-//
    reader_debug_speed_check(' start : ');
    //---------DEBUG&SPEED TEST------------E-//

        // NEW LINES STARYT
        $quizzes = array();
        // NEW LINES STOP
        $data = $DB->get_records_sql ("SELECT * FROM {reader_publisher} where hidden=? order by publisher, name", array(0));
        foreach ($data as $data_) {
            $quizzes[$data_->publisher][$data_->level][$data_->id] = $data_->name;
        }
        
    //---------DEBUG&SPEED TEST------------S-//
    reader_debug_speed_check(' mark1 : ');
    //---------DEBUG&SPEED TEST------------E-//
        $allquestionscount = 0;
        foreach ($quizzes as $key =>$value) {
            foreach ($value as $key2 => $value2) {
                foreach ($value2 as $key3 => $value3) {
                    $allquestionscount++;
                    
                    $checkboxdatapublishers[$key][] = $allquestionscount;
                    $checkboxdatalevels[$key][$key2][] = $allquestionscount;
                    $quizzescountid[$key3] = $allquestionscount;
                }
            }
        }
        
    //---------DEBUG&SPEED TEST------------S-//
    reader_debug_speed_check(' mark2 : ');
    //---------DEBUG&SPEED TEST------------E-//
        
        $currentbooksarray = array();
        
        $currentbooks = $DB->get_records_sql ("SELECT * FROM {reader_individual_books} WHERE readerid = ?", array($reader->id)); 
        if (is_array($currentbooks)) {
            foreach ($currentbooks as $currentbook) {
                $currentbooksarray[$currentbook->bookid] = $currentbook->readerid;
            }
        }
        
    //---------DEBUG&SPEED TEST------------S-//
    reader_debug_speed_check(' before print : ');
    //---------DEBUG&SPEED TEST------------E-//
        
        echo $OUTPUT->box_start('generalbox');
        require_once('hide.js');
        
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

        echo '<form action="admin.php?a=admin&id='.$id.'&act=setindividualbooks" method="post" id="mform1">';
        echo '<div style="width:600px"><a href="#" onclick="expandall();return false;">Show All</a> / <a href="#" onclick="collapseall();return false;">Hide All</a><br />';
        
        //vivod 
        $cp = 0;

        /* OLD LINES START **
        if ($quizzes) {
        ** OLD LINES STOP **/
        // NEW LINES START
        if (! empty($quizzes)) {
        // NEW LINES STOP
            foreach ($quizzes as $publiher => $datas) {
                $cp++;
                echo '<br /><a href="#" onclick="toggle(\'comments_'.$cp.'\');return false">
                      <span id="comments_'.$cp.'indicator"><img src="'.$CFG->wwwroot.'/mod/reader/open.gif" alt="Opened folder" /></span></a> ';
                echo ' <b>'.$publiher.' &nbsp;</b>';

                echo '<span id="comments_'.$cp.'"><input type="checkbox" name="installall['.$cp.']" onclick="setChecked(this,'.$checkboxdatapublishers[$publiher][0].','.end($checkboxdatapublishers[$publiher]).')" value="" /><span id="seltext_'.$cp.'">Select All</span>';
                $topsubmitonclick = $cp;
                foreach ($datas as $level => $quizzesdata) {
                    $cp++;

                    echo '<div style="padding-left:40px;padding-top:10px;padding-bottom:10px;"><a href="#" onclick="toggle(\'comments_'.$cp.'\');return false">
                          <span id="comments_'.$cp.'indicator"><img src="'.$CFG->wwwroot.'/mod/reader/open.gif" alt="Opened folder" /></span></a> ';

                    echo '<b>'.$level.' &nbsp;</b>';
                    echo '<span id="comments_'.$cp.'"><input type="checkbox" name="installall['.$cp.']" onclick="setChecked(this,'.$checkboxdatalevels[$publiher][$level][0].','.end($checkboxdatalevels[$publiher][$level]).')" value="" /><span id="seltext_'.$cp.'">Select All</span>';
                    foreach ($quizzesdata as $quizid => $quiztitle) {
                        echo '<div style="padding-left:20px;"><input type="checkbox" name="quiz[]" id="quiz_'.$quizzescountid[$quizid].'" value="'.$quizid.'"';
                        if (isset($currentbooksarray[$quizid])) {
                            echo " checked=\"checked\" ";
                            $submitonclick[$cp] = 1; 
                            $submitonclicktop[$topsubmitonclick] = 1; 
                        }
                        echo ' /> &nbsp; '.$quiztitle.'</div>';
                    }
                    echo '</span></div>';
                }
                echo '</span>';
            }

            echo '<div style="margin-top:40px;margin-left:200px;"><input type="submit" name="showquizzes" value="Show Students Selected Quizzes" /></div>';
        }
        
        echo '<input type="hidden" name="step" value="1" />';

        echo '</div>';
        echo '</form>';
        
        echo '<script type="text/javascript">
//<![CDATA[
var vh_numspans = '.$cp.';
collapseall();
//]]>

</script>'; //
        
        echo '<script type="text/javascript">
';
        foreach ((array) $submitonclicktop as $key => $value) {
            echo 'expand("comments_'.$key.'");
';
        }
        foreach ((array) $submitonclick as $key => $value) {
            echo 'expand("comments_'.$key.'");
';
        }
        echo '</script>';
        
    //---------DEBUG&SPEED TEST------------S-//
    reader_debug_speed_check(' after print : ');
    //---------DEBUG&SPEED TEST------------E-//
        
        echo $OUTPUT->box_end();
    //-----------------------------View log of suspicious activity-------------------------//
    } else if ($act == "viewlogsuspiciousactivity" && has_capability('mod/reader:readerviewreports', $contextmodule)) {
        $table = new html_table();
        
        $titlesarray = Array ('Image'=>'', 'By Username'=>'byusername', 'Student 1'=>'student1', 'Student 2'=>'student2', 'Quiz'=>'quiz', 'Status'=>'status', 'Date'=>'date');
        
        $table->head = reader_make_table_headers ($titlesarray, $orderby, $sort, '?a=admin&id='.$id.'&act='.$act.'&grid='.$grid.'&page='.$page);
        $table->align = array ("center", "left", "left", "left", "left", "center", "center");
        $table->width = "100%";
        
        if ($excel) {
            $myxls->write_string(0, 0, 'View log of suspicious activity',$formatbc);
            $myxls->write_string(1, 0, 'Date: '.$exceldata['time'].'; Course name: '.$exceldata['course_shotname'].'; ');
            $myxls->set_row(0, 30);
            $myxls->set_column(0,1,20);
            $myxls->set_column(2,10,15);
            
            $myxls->write_string(2, 0, 'By Username',$formatbc);
            $myxls->write_string(2, 1, 'Student 1',$formatbc);
            $myxls->write_string(2, 2, 'Student 2',$formatbc);
            $myxls->write_string(2, 3, 'Quiz',$formatbc);
            $myxls->write_string(2, 4, 'Status',$formatbc);
            $myxls->write_string(2, 5, 'Date',$formatbc);
        }
        
        if (!$grid) {
            $grid = NULL;
        }
        
        $cheatedlogs = $DB->get_records("reader_cheated_log", array("readerid" => $reader->id));
        
        foreach ($cheatedlogs as $cheatedlog) {
            $cheatedstring = '';
            if ($cheatedlog->status == "cheated") 
                $cheatedstring = ' <a href="admin.php?a='.$a.'&id='.$id.'&act='.$act.'&grid='.$grid.'&page='.$page.'&sort='.$sort.'&orderby='.$orderby.'&uncheated='.$cheatedlog->attempt1.'_'.$cheatedlog->attempt2.'" onclick="if(confirm(\'Set passed ?\')) return true; else return false;">Set passed</a>';
            
        
            $byuser  = $DB->get_record("user", array("id" => $cheatedlog->byuserid));
            $user1   = $DB->get_record("user", array("id" => $cheatedlog->userid1));
            $user2   = $DB->get_record("user", array("id" => $cheatedlog->userid2));
            $quiz    = $DB->get_record("reader_publisher", array("id" => $cheatedlog->quizid));
            
            $picture = $OUTPUT->user_picture($byuser,array($course->id, true, 0, true));
            $table->data[] = array ($picture,
                                    reader_fullname_link_t($byuser),
                                    reader_fullname_link_t($user1),
                                    reader_fullname_link_t($user2),
                                    $quiz->name,
                                    $cheatedlog->status.$cheatedstring,
                                    date("d M Y", $cheatedlog->date)
                                    );
        }
        
        $table->data = reader_sort_table_data ($table->data, $titlesarray, $orderby, $sort);

        if ($excel) {
          foreach ($table->data as $tabledataarray) {
            $myxls->write_string($row, 0, (string) $tabledataarray[1]);
            $myxls->write_string($row, 1, (string) $tabledataarray[2]);
            $myxls->write_string($row, 2, (string) $tabledataarray[3]);
            $myxls->write_number($row, 3, (string) $tabledataarray[4]);
            $myxls->write_number($row, 4, (string) $tabledataarray[5]);
            $myxls->write_number($row, 5, (string) $tabledataarray[6]);
            $row++;
          }
        }

        if ($excel) {
            $workbook->close();
            die();
        }
        
        echo '<table style="width:100%"><tr><td align="right">';
        echo $OUTPUT->single_button(new moodle_url("admin.php",$options), get_string("downloadexcel", "reader"), 'post', $options);
        echo '</td></tr></table>';
        
        reader_select_perpage($id, $act, $sort, $orderby, $grid);
        list($totalcount, $table->data, $startrec, $finishrec, $options["page"]) = reader_get_pages($table->data, $page, $perpage);
        $pagingbar = new paging_bar($totalcount, $page, $perpage, "admin.php?a=admin&id={$id}&act={$act}&sort={$sort}&orderby={$orderby}&grid={$grid}&amp;");
        echo $OUTPUT->render($pagingbar); 
        
        if ($table) {
            echo html_writer::table($table);
        }
        
        $pagingbar = new paging_bar($totalcount, $page, $perpage, "admin.php?a=admin&id={$id}&act={$act}&sort={$sort}&orderby={$orderby}&grid={$grid}&amp;");
        echo $OUTPUT->render($pagingbar); 
    //-----------------------------Export student records-------------------------//
    } else if ($act == "exportstudentrecords" && has_capability('mod/reader:userdbmanagement', $contextmodule)) {
        $data = $DB->get_records("reader_attempts", array("reader" => $reader->id));
        
        foreach($data as $data_) {
            if (!$userdata[$data_->userid]) $userdata[$data_->userid] = $DB->get_record("user", array("id" => $data_->userid));
            if (!$levelsdata[$data_->userid]) $levelsdata[$data_->userid] = $DB->get_record("reader_levels", array("userid" => $data_->userid));
            if (!$bookdata[$data_->quizid]) $bookdata[$data_->quizid] = $DB->get_record("reader_publisher", array("id" => $data_->quizid));
            header("Content-Type: text/plain; filename=\"{$COURSE->shortname}_attempts.txt\"");
            echo "{$userdata[$data_->userid]->username},{$data_->uniqueid},{$data_->attempt},{$data_->sumgrades},{$data_->persent},{$data_->bookrating},{$data_->ip},{$bookdata[$data_->quizid]->image},{$data_->timefinish},{$data_->passed},{$data_->persent},{$levelsdata[$data_->userid]->currentlevel}\n";
        }
        die();
        
        //----------------------------Import student record-------------------------//
    } else if ($act == "importstudentrecord" && has_capability('mod/reader:userdbmanagement', $contextmodule)) {
        // NEW LINES START
        class mod_reader_importstudentrecord_form extends moodleform {
            function definition() {
                $this->_form->addElement('filepicker', 'importstudentrecorddata', get_string('file'));
                $this->add_action_buttons(false, get_string('upload'));
            }
        }
        $mform = new mod_reader_importstudentrecord_form("admin.php?a=admin&id={$id}&act=importstudentrecord");
        if ($contents = $mform->get_file_content('importstudentrecorddata')) {
        // NEW LINES STOP
        /* OLD LINES START **
        if ($_FILES) {
            $filename = $_FILES['importstudentrecorddata']['tmp_name'];
            $zd = fopen ($filename, "r");
            $contents = fread ($zd, filesize($filename));
            fclose ($zd);
        ** OLD LINES STOP **/
            
            $contents = preg_replace('/\r\n|\r/', "\n", $contents); 

            $contents = explode("\n", $contents);
            
            if ($contents) echo "File was uploaded <br />\n";
            
            $lastattemptid = $DB->get_field_sql('SELECT uniqueid FROM {reader_attempts} ORDER BY uniqueid DESC');
            
            foreach ($contents as $content_) {
                $lastattemptid++;
                list($data['username'],$data['uniqueid'],$data['attempt'],$data['sumgrades'],$data['persent'],$data['bookrating'],$data['ip'],$data['image'],$data['timefinish'],$data['passed'],$data['persent'],$data['currentlevel']) = explode(",", $content_);
                if (!$userdata[$data['username']]) $userdata[$data['username']] = $DB->get_record("user", array("username" => $data['username']));
                if (!$bookdata[$data['image']]) $bookdata[$data['image']] = $DB->get_record("reader_publisher", array("image" => $data['image']));
                
                echo "User name: {$data['username']} (id:{$userdata[$data['username']]->id}) Book id: {$bookdata[$data['image']]->id} <br />\n";
                
                $newdata = new object;
                $newdata->uniqueid       = $lastattemptid;
                //$newdata->uniqueid       = 0;
                $newdata->reader         = $reader->id;
                $newdata->userid         = $userdata[$data['username']]->id;
                $newdata->attempt        = $data['attempt'];
                //$newdata->attempt        = 0;
                $newdata->sumgrades      = $data['sumgrades'];
                $newdata->persent        = $data['persent'];
                $newdata->passed         = $data['passed'];
                $newdata->checkbox       = 0;
                $newdata->timestart      = $data['timefinish'];
                $newdata->timefinish     = $data['timefinish'];
                $newdata->timemodified   = $data['timefinish'];
                //$newdata->layout         = $data['layout'];
                $newdata->layout         = 0;
                $newdata->preview        = 0;
                $newdata->quizid         = $bookdata[$data['image']]->id;
                $newdata->bookrating     = $data['bookrating'];
                $newdata->ip             = $data['ip'];
                
                
                
                if (!$DB->get_record("reader_attempts", array("userid" => $newdata->userid, "quizid" => $newdata->quizid)) && !empty($newdata->userid) && !empty($newdata->quizid)) {
                    
                    if ($idat = $DB->insert_record("reader_attempts", $newdata)) {
                        echo " &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Attempt added ({$idat})<br />\n";
                    }
                    else
                    {
                        echo " &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Can't add attempt <br />\n";
                        echo "<pre>";
                        print_r ($newdata);
                        echo "</pre>";
                    }
                }
                else if (empty($newdata->userid)) {
                    echo " &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; User not found<br />\n";
                }
                else if (empty($newdata->quizid)) {
                    echo " &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Book not found<br />\n";
                }
                else
                {
                    echo " &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Attempt already exist<br />\n";
                }
            }
            echo "Done";
        }
        else
        {
            /* OLD LINES START **
            class mod_reader_importstudentrecord_form extends moodleform {
                function definition() {
                    global $CFG, $course,$DB;
            
                    $mform    =& $this->_form;
                
                    $mform->addElement('file', 'importstudentrecorddata', 'File');
                    
                    $this->add_action_buttons(false, $submitlabel="Upload");
                }
            }
            ** OLD LINES STOP **/
            $mform->display();
        }
        //----------------------------Change number of sections-------------------------//
    } else if ($act == "changenumberofsectionsinquiz" && has_capability('mod/reader:manage', $contextmodule)) {
        if ($numberofsections) echo "<h2>Done</h2>";
        class mod_reader_changenumberofsectionsinquiz_form extends moodleform {
            function definition() {
                global $CFG, $course,$DB;
            
                $mform    =& $this->_form;
              
                $mform->addElement('header', 'setgoal', get_string('changenumberofsectionsinquiz', 'reader')); 
                $mform->addElement('text', 'numberofsections', '', array('size'=>'10'));
                  
                $this->add_action_buttons(false, $submitlabel="Save");
            }
        }
        $mform = new mod_reader_changenumberofsectionsinquiz_form("admin.php?a=admin&id={$id}&act=changenumberofsectionsinquiz");
        $mform->display();
        
       //----------------------------Book no quizzes record-------------------------//
    } else if ($act == "assignpointsbookshavenoquizzes" && has_capability('mod/reader:changestudentslevelsandpromote', $contextmodule)) {
        $table = new html_table();
        
        $titlesarray = Array ('<input type="button" value="Select all" onclick="checkall();" />'=>'', 'Image'=>'', 'Username'=>'username', 'Fullname<br />Click to view screen'=>'fullname', 'Current level'=>'currentlevel', 'Total words<br /> this term'=>'totalwordsthisterm', 'Total words<br /> all terms'=>'totalwordsallterms');
        
        $table->head = reader_make_table_headers ($titlesarray, $orderby, $sort, '?a=admin&id='.$id.'&act='.$act.'&book='.$book.'&grid='.$grid.'&searchtext='.$searchtext.'&page='.$page);
        $table->align = array ("center", "center", "left", "left", "center", "center", "center");
        $table->width = "100%";
        
        if (!$grid) {
            $grid = NULL;
        }
        
        $coursestudents = get_enrolled_users($context, NULL, $grid);
        
        foreach ($coursestudents as $coursestudent) {
          if (reader_check_search_text($searchtext, $coursestudent)) {
            $picture = $OUTPUT->user_picture($coursestudent,array($course->id, true, 0, true));
            
            if ($excel) {
                //---------------------Groups for Excel------------//
                if ($usergroups = groups_get_all_groups($course->id, $coursestudent->id)){
                    foreach ($usergroups as $group){
                        $groupsdata[$coursestudent->username] .= $group->name.', ';
                    }
                    $groupsdata[$coursestudent->username] = substr($groupsdata[$coursestudent->username], 0, -2);
                }
                //---------------------------------------------------//
            }
            
            unset($data);
            $data['totalwordsthisterm'] = 0;
            $data['totalwordsallterms'] = 0;
            
            if ($attempts = $DB->get_records_sql("SELECT * FROM {reader_attempts} WHERE userid= ?  and reader= ?  and timefinish > ? ", array($coursestudent->id, $reader->id, $reader->ignordate))) {
                foreach ($attempts as $attempt) {
                    if (strtolower($attempt->passed) == "true") {
                        $bookdata = $DB->get_record("reader_publisher", array("id" => $attempt->quizid));
                        $data['totalwordsthisterm'] += $bookdata->words;
                    }
                }
            }
            
            if ($attempts = $DB->get_records_sql("SELECT * FROM {reader_attempts} WHERE userid= ? ", array($coursestudent->id))) {
                foreach ($attempts as $attempt) {
                    if (strtolower($attempt->passed) == "true") {
                        $bookdata = $DB->get_record("reader_publisher", array("id" => $attempt->quizid));
                        $data['totalwordsallterms'] += $bookdata->words;
                    }
                }
            }
            
            if ($attemptdata = reader_get_student_attempts($coursestudent->id, $reader)) {
                if (has_capability('mod/reader:viewstudentreaderscreens', $contextmodule)) {
                    $link = reader_fullname_link_viewasstudent($coursestudent, "grid={$grid}&book={$book}&searchtext={$searchtext}&page={$page}&sort={$sort}&orderby={$orderby}");
                }
                else
                {
                    $link = reader_fullname_link_t($coursestudent);
                }
                
                $table->data[] = array ('<input type="checkbox" name="noquizuserid[]" value="'.$coursestudent->id.'" />',
                                        $picture,
                                        reader_user_link_t($coursestudent),
                                        $link,
                                        $attemptdata[1]['currentlevel'],
                                        $data['totalwordsthisterm'],
                                        $data['totalwordsallterms']
                                        );
            }
            else
            {
                if (has_capability('mod/reader:viewstudentreaderscreens', $contextmodule)) {
                    $link = reader_fullname_link_viewasstudent($coursestudent);
                }
                else
                {
                    $link = reader_fullname_link_t($coursestudent);
                }
                
                $table->data[] = array ('<input type="checkbox" name="noquizuserid[]" value="'.$coursestudent->id.'" />',
                                        $picture,
                                        reader_user_link_t($coursestudent),
                                        $link,
                                        $attemptdata[1]['currentlevel'],
                                        0,0);
            }
          }
        }
        
        $table->data = reader_sort_table_data ($table->data, $titlesarray, $orderby, $sort);
        
        reader_print_search_form ($id, $act);
        
        $groups = groups_get_all_groups ($course->id);
        
        if ($groups) {
            reader_print_group_select_box($course->id, 'admin.php?a=admin&id='.$id.'&act='.$act.'&book='.$book.'&sort='.$sort.'&orderby='.$orderby);
        }
        
        echo '<form method="post" action="?a=admin&id='.$id.'&act='.$act.'&book='.$book.'"> <input type="submit" name="setnoquiz" value="Submit" />';
        
        //--------------Book Selector ----------------//
        $publisherform = array("id=".$id."&publisher=Select Publisher" => get_string("selectpublisher", "reader"));
        
        if (isset($noquizreport)) echo '<center><h3>'.$noquizreport.'</h3></center>';
        
        $publishers = $DB->get_records ("reader_noquiz",NULL,NULL,"publisher");
        foreach ($publishers as $publisher_) {
          $publisherform["id=".$id."&publisher=".$publisher_->publisher] = $publisher_->publisher;
        }     
        echo "<script type=\"text/javascript\">
function validateForm(form) {
    if (isChosen(form.book)) {
        return true;
    }
    return false;
}
function isChosen(select) {
   if (select.selectedIndex == -1) {
       alert(\"Please choose book!\");
       return false;
   } else {
       return true;
   }
}


</script>";


        echo '<center><table width="600px">';
        echo '<tr><td width="200px">'.get_string("publisherseries", "reader").'</td><td width="10px"></td><td width="200px"></td></tr>';
        echo '<tr><td valign="top">';
        echo '<select name="publisher" id="id_publisher" onchange="request(\'view_get_bookslist_noquiz.php?ajax=true&\' + this.options[this.selectedIndex].value,\'selectthebook\'); return false;">';
        foreach ($publisherform as $publisherformkey => $publisherformvalue) {
            echo '<option value="'.$publisherformkey.'" ';
            if ($publisherformvalue == $publisher) { echo 'selected="selected"'; }
            echo ' >'.$publisherformvalue.'</option>';
        }
        echo '</select>';
        echo '</td><td valign="top">';

        echo '</td><td valign="top"><div id="selectthebook">';
        
        echo '</div></td></tr>';
        echo '<tr><td colspan="3" align="center">';

        echo '</td></tr>';
        echo '</table>';
        echo '</center>';
        //echo '</form></center>';
        
        //--------------------------------------------//
        
        reader_select_perpage($id, $act, $sort, $orderby, $grid);
        list($totalcount, $table->data, $startrec, $finishrec, $options["page"]) = reader_get_pages($table->data, $page, $perpage);
        $pagingbar = new paging_bar($totalcount, $page, $perpage, "admin.php?a=admin&id={$id}&act={$act}&book={$book}&sort={$sort}&orderby={$orderby}&grid={$grid}&amp;");
        echo $OUTPUT->render($pagingbar); 
        
        if ($table) {
            echo html_writer::table($table);
        }
        
        echo '</form>';
        
        $pagingbar = new paging_bar($totalcount, $page, $perpage, "admin.php?a=admin&id={$id}&act={$act}&book={$book}&sort={$sort}&orderby={$orderby}&grid={$grid}&amp;");
        echo $OUTPUT->render($pagingbar); 
      //----------Adjust scores-----------
    } else if ($act == "adjustscores" && has_capability('mod/reader:manage', $contextmodule)) {
        $table = new html_table();
        
        if ($sort == "username") {
            $sort = "title";
        }
        $titlesarray = Array (''=>'', 'Full Name'=>'username', 'Title'=>'title', 'Publisher'=>'publisher', 'Level'=>'level', 'Reading Level'=>'rlevel', 'Reading level'=>'rlevel', 'Score'=>'score', 'P/F/C'=>'', 'Finishtime'=>'finishtime', 'Option'=>'');
        
        $table->head = reader_make_table_headers ($titlesarray, $orderby, $sort, '?a=admin&id='.$id.'&act='.$act.'&searchtext='.$searchtext);
        $table->align = array ("left", "left", "left", "left", "center", "center", "center", "center", "center", "center", "center");
        $table->width = "100%";
        
        if ($book >= 1) 
          $bookdata = $DB->get_record("reader_publisher", array( "id" => $book));
        $attemptsofbook = $DB->get_records_sql("SELECT * FROM {reader_attempts} WHERE quizid= ?  and reader= ? ", array($book, $reader->id));
        
        
        while(list($attemptsofbookkey, $attemptsofbook_) = each($attemptsofbook)) {
			if (strtolower($attemptsofbook_->passed) == 'true') {
			  $pfcmark = 'P';
			} else if (strtolower($attemptsofbook_->passed) == 'false') {
			  $pfcmark = 'F';
			} else { $pfcmark = 'C'; }
			$userdata = $DB->get_record("user", array("id" => $attemptsofbook_->userid));
            $table->data[] = array ('<input type="checkbox" name="adjustscoresupbooks[]" value="'.$attemptsofbook_->id.'" />',
                                    fullname($userdata),
                                    array('<a href="report.php?idh='.$id.'&q='.$bookdata->quizid.'&mode=analysis&b='.$bookdata->id.'">'.$bookdata->name.'</a>', $bookdata->name),
                                    $bookdata->publisher,
                                    $bookdata->level,
                                    reader_get_reader_difficulty($reader, $bookdata->id),
                                    $bookdata->difficulty,
                                    round($attemptsofbook_->persent)."%",
                                    $pfcmark,
                                    date("d-M-Y", $attemptsofbook_->timemodified),
                                    'deleted');
        }

        $table->data = reader_sort_table_data ($table->data, $titlesarray, $orderby, $sort);
        
        if (isset($adjustscorestext)) echo '<div style="padding:20px 0;">'.$adjustscorestext.'</div>';
        
        echo '<table style="width:100%"><tr><td align="right">';
		$publisherform = Array("id=".$id."&publisher=Select Publisher" => get_string("selectpublisher", "reader"));
		$publisherform2 = Array("id=".$id."&publisher=Select Publisher" => get_string("selectpublisher", "reader"));
		$seriesform    = Array(get_string("selectseries", "reader"));
		$levelsform    = Array(get_string("selectlevel", "reader"));
		$booksform     = Array();
        /* OLD LINES START **
		$publishers = $DB->get_records ("reader_publisher", NULL, NULL, "publisher");
        ** OLD LINES STOP **/
        // NEW LINES START
		$publishers = $DB->get_records ("reader_publisher", NULL, NULL, "id,publisher");
        // NEW LINES STOP
		foreach ($publishers as $publisher_) {
			$publisherform["id=".$id."&publisher=".$publisher_->publisher] = $publisher_->publisher;
		}
        echo "<script type=\"text/javascript\">
function validateForm(form) {
    if (isChosen(form.book)) {
        return true;
    }
    return false;
}
function isChosen(select) {
   if (select.selectedIndex == -1) {
       alert(\"Please choose book!\");
       return false;
   } else {
       return true;
   }
}


</script>";
        echo '<form action="admin.php?a=admin&id='.$id.'&act='.$act.'" method="post" id="mform1">';
        echo '<center><table width="600px">';
        echo '<tr><td width="200px">'.get_string("publisherseries", "reader").'</td><td width="10px"></td><td width="200px"></td></tr>';
        echo '<tr><td valign="top">';
        echo '<select name="publisher" id="id_publisher" onchange="request(\'view_get_bookslist.php?onlypub=1&ajax=true&\' + this.options[this.selectedIndex].value,\'selectthebook\'); return false;">';
        foreach ($publisherform as $publisherformkey => $publisherformvalue) {
            echo '<option value="'.$publisherformkey.'" ';
            if ($publisherformvalue == $publisher) { echo 'selected="selected"'; }
            echo ' >'.$publisherformvalue.'</option>';
        }
        echo '</select>';
        echo '</td><td valign="top">';

        echo '</td><td valign="top"><div id="selectthebook">';
        
        echo '</div></td></tr>';
        echo '<tr><td colspan="3" align="center">';

        echo '</td></tr>';
        echo '<tr><td colspan="3" align="center"><input type="button" value="Select quiz" onclick="if (validateForm(this.form)) {this.form.submit() };" /> </td></tr>';
        echo '</table>';
        echo '</form></center>';
        
        echo '</td></tr></table>';
        
        echo '<form action="admin.php?a=admin&id='.$id.'&act='.$act.'&book='.$book.'" method="post"><div style="20px 0;">';
        echo '<table><tr><td width="180px;">Update selected adding</td><td width="60px;"><input type="text" name="adjustscoresaddpoints" value="" style="width:60px;" /></td><td width="70px;">points</td><td><input type="submit" name="submit" value="Add" /></td></tr></table>';
        
        echo '<table><tr><td width="100px;">Update all > </td>

        <td width="60px;"><input type="text" name="adjustscoresupall" value="" style="width:50px;" /></td>
        <td width="90px;">points and < </td>
        <td width="60px;"><input type="text" name="adjustscorespand" value="" style="width:50px;" /></td>
        <td width="90px;">points by </td>
        <td width="60px;"><input type="text" name="adjustscorespby" value="" style="width:50px;" /></td>
        
        <td width="70px;">points</td><td><input type="submit" name="submit" value="Add" /></td></tr></table>';
        
        echo '</div>';

        if ($table) {
            echo html_writer::table($table);
        }
        
        echo '</form>';
    }
    
    echo $OUTPUT->box_end();

    

    echo $OUTPUT->footer();
    
//---------DEBUG&SPEED TEST------------S-//
    /*
    if (has_capability('mod/reader:manage', $contextmodule)) {
        echo $debugandspeedforadminreport."<br /><pre>";
        echo microtime(true);
    }
    */
//---------DEBUG&SPEED TEST------------E-//
