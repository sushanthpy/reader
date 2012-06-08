<?php  // $Id:,v 1.0 2008/01/20 16:10:00 Serafim Panov

    require_once("../../config.php");
    require_once("lib.php");
    require_once($CFG->dirroot.'/course/moodleform_mod.php');
    require_once($CFG->dirroot . '/question/editlib.php');
    
    $id        = optional_param('id', 0, PARAM_INT); 
    $a         = optional_param('a', NULL, PARAM_CLEAN); 
    $v         = optional_param('v', NULL, PARAM_CLEAN); 
    $publisher = optional_param('publisher', NULL, PARAM_CLEAN);
    $level     = optional_param('level', NULL, PARAM_CLEAN);
    $series    = optional_param('series', NULL, PARAM_CLEAN);
    $likebook  = optional_param('likebook', NULL, PARAM_CLEAN);

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

    add_to_log($course->id, "reader", "view personal page", "view.php?id=$id", "$cm->instance");
    
    
    $contextmodule = get_context_instance(CONTEXT_MODULE, $cm->id);

    //----check admin by user view-----//
    if (isset($_SESSION['SESSION']->reader_page) || isset($_SESSION['SESSION']->reader_lasttime))
      if ($_SESSION['SESSION']->reader_page == 'view' || $_SESSION['SESSION']->reader_lasttime < (time() - 300)) {
        unset ($_SESSION['SESSION']->reader_page);
        unset ($_SESSION['SESSION']->reader_lasttime);
        unset ($_SESSION['SESSION']->reader_lastuser);
        unset ($_SESSION['SESSION']->reader_lastuserfrom);
      }
    //----check admin by user view--END//
    
    //----------Small patch delete later-------------//
    if ($reader->shuffleanswers == 0) {
        $DB->set_field("reader",  "shuffleanswers",  1, array( "id" => $reader->id));
        $reader->shuffleanswers = 1;
    }
    //-----------------------------------------------//
// Initialize $PAGE, compute blocks
    $PAGE->set_url('/mod/reader/view.php', array('id' => $cm->id));
    
    $title = $course->shortname . ': ' . format_string($reader->name);
    $PAGE->set_title($title);
    $PAGE->set_heading($course->fullname);
                  
    echo $OUTPUT->header();
    
    echo '<script type="text/javascript" src="ajax.js"></script>';
                  
//Check time [open/close]
    $timenow = time();
    if (!empty($reader->timeopen) && $reader->timeopen > $timenow) {
        error (get_string('quizopens', 'quiz').': '.userdate($reader->timeopen));
    }
    if (!empty($reader->timeclose) && $reader->timeclose < $timenow) {
        error (get_string('quizcloses', 'quiz').': '.userdate($reader->timeclose));
    }
    
    $alreadyansweredbooksid = array();
    
    
    if (has_capability('mod/reader:manage', $contextmodule)) {
        require_once ('tabs.php');
    } else {
/// Check subnet access
        if ($reader->subnet && !address_in_subnet(getremoteaddr(), $reader->subnet)) {
            error(get_string("subneterror", "quiz"), "view.php?id=".$id);
        }
    }
    
    $leveldata = reader_get_stlevel_data($reader);
    
    
    echo $OUTPUT->box_start('generalbox');

    $table = new html_table();

        if ($reader->pointreport == 1) {
          if ($reader->reportwordspoints != 1) {  //1 - only points
            $table->head = array ("Date", "Book Title", "Level", "Words", "Percent Correct", "Total Points");
            $table->align = array ("left", "left", "left", "center", "center", "center");
          }
          else
          {
            $table->head = array ("Date", "Book Title", "Level", "Percent Correct", "Total Points");
            $table->align = array ("left", "left", "left", "center", "center");
          }
        }
        else
        {
          if ($reader->reportwordspoints == 2) {  //points and words
            $table->head = array ("Date", "Book Title", "Level", "Status", "Words", "Points This Book", "Total Points");
            $table->align = array ("left", "left", "left", "center", "center", "center", "center");
          } else if ($reader->reportwordspoints == 1) {  //points only
            $table->head = array ("Date", "Book Title", "Level", "Status", "Points This Book", "Total Points");
            $table->align = array ("left", "left", "left", "center", "center", "center");
          } else if ($reader->reportwordspoints == 0) {  //words only
            $table->head = array ("Date", "Book Title", "Level", "Status", "Words", "Total words");
            $table->align = array ("left", "left", "left", "center", "center", "center");
          }
        }
        
        $table->width = "800";
        
        $totalpoints         = 0;
        $correctpoints       = 0;
        $totalwords          = 0;
        $totalwordscount     = 0;
        $totalwordsall       = 0;
        $totalwordscountall  = 0;
        
        $bookcovers = "";
        
        if ($oldattempts = $DB->get_records_sql("SELECT * FROM {reader_attempts} WHERE userid= ? and timefinish <= ? ORDER BY timefinish", array($USER->id, $reader->ignordate))) {
            foreach ($oldattempts as $oldattempt) {
                //-----------------Book old covers------------------//
                if ($reader->bookcovers == 1 && strtolower($oldattempt->passed) == 'true') {
                    $bookdata = $DB->get_record("reader_publisher", array( "id" => $oldattempt->quizid));
                    $bookcoversinprevterm .= '<img src="'.$CFG->wwwroot.'/mod/reader/images.php/'.$reader->usecourse.'/images/'.$bookdata->image.'" border="0" alt="'.$bookdata->name.'" height="150" width="100" /> ';
                }
                //----------------------------------------------//
            }
        }
        
        $bookcoversinthisterm = '';
        
        if (list($attemptdata, $summaryattemptdata) = reader_get_student_attempts($USER->id, $reader)) {
            foreach ($attemptdata as $attemptdata_) {
                
                $lastattemptdate = $attemptdata_['timefinish']; // fixing postgress problem
                
                $alreadyansweredbooksid[] = $attemptdata_['quizid'];
                
                //-----------------Book covers------------------//
                if ($reader->bookcovers == 1 && $attemptdata_['status'] == 'correct') {
                    $bookcoversinthisterm .= '<img src="'.$CFG->wwwroot.'/mod/reader/images.php/'.$reader->usecourse.'/images/'.$attemptdata_['image'].'" border="0" alt="'.$attemptdata_['booktitle'].'" height="150" width="100" /> ';
                }
                //----------------------------------------------//
                
                if ($attemptdata_['statustext'] == "Passed" || $attemptdata_['statustext'] == "Credit"){
                    $totalwords += $attemptdata_['words'];
                    $totalwordscount++;
                    $showwords = $attemptdata_['words'];
                } else {
                    $showwords = "";
                }
                
                if ($reader->pointreport == 1) {
                  if ($reader->reportwordspoints != 1) {
                    $table->data[] = array (date ("d M Y", $attemptdata_['timefinish']), 
                                            $attemptdata_['booktitle'], 
                                            $attemptdata_['booklevel']."[RL ".$attemptdata_['bookdiff']."]", 
                                            //$attemptdata_['words'],
                                            $showwords,
                                            $attemptdata_['bookpersent'], 
                                            $attemptdata_['totalpoints']);
                  } else {  //without words
                    $table->data[] = array (date ("d M Y", $attemptdata_['timefinish']), 
                                            $attemptdata_['booktitle'], 
                                            $attemptdata_['booklevel']."[RL ".$attemptdata_['bookdiff']."]",
                                            $attemptdata_['bookpersent'], 
                                            $attemptdata_['totalpoints']);
                  }
                }
                else
                {
                  if ($reader->reportwordspoints == 2) {  //points and words
                    $table->data[] = array (date ("d M Y", $attemptdata_['timefinish']), 
                                            $attemptdata_['booktitle'], 
                                            $attemptdata_['booklevel']."[RL ".$attemptdata_['bookdiff']."]", 
                                            $attemptdata_['statustext'], 
                                            //$attemptdata_['words'],
                                            $showwords,
                                            $attemptdata_['bookpoints'], 
                                            $attemptdata_['totalpoints']);
                  } else if ($reader->reportwordspoints == 1) {  //points only
                    $table->data[] = array (date ("d M Y", $attemptdata_['timefinish']), 
                                            $attemptdata_['booktitle'], 
                                            $attemptdata_['booklevel']."[RL ".$attemptdata_['bookdiff']."]", 
                                            $attemptdata_['statustext'], 
                                            $attemptdata_['bookpoints'], 
                                            $attemptdata_['totalpoints']);
                  } else if ($reader->reportwordspoints == 0) {  //words only
                    $table->data[] = array (date ("d M Y", $attemptdata_['timefinish']), 
                                            $attemptdata_['booktitle'], 
                                            $attemptdata_['booklevel']."[RL ".$attemptdata_['bookdiff']."]", 
                                            $attemptdata_['statustext'], 
                                            //$attemptdata_['words'], 
                                            $showwords,
                                            $totalwords);
                  }
                }
            }
        }

        //--------SELECT ALL words------//
		$studentattempts_p = $DB->get_records_sql("SELECT ra.timefinish,ra.userid,ra.attempt,ra.persent,ra.id,ra.quizid,ra.sumgrades,ra.passed,rp.name,rp.publisher,rp.level,rp.length,rp.image,rp.difficulty,rp.words,rp.id as rpid FROM {reader_attempts} ra LEFT JOIN {reader_publisher} rp ON rp.id = ra.quizid WHERE ra.preview != 1 and ra.userid= ?", array($USER->id));
	
		$studentattempts_n = $DB->get_records_sql("SELECT ra.timefinish,ra.userid,ra.attempt,ra.persent,ra.id,ra.quizid,ra.sumgrades,ra.passed,rp.name,rp.publisher,rp.level,rp.length,rp.image,rp.difficulty,rp.words,rp.id as rpid FROM {reader_attempts} ra LEFT JOIN {reader_noquiz} rp ON rp.id = ra.quizid WHERE ra.preview = 1 and ra.userid= ?", array($USER->id));
	
		if (is_array($studentattempts_n ) && is_array($studentattempts_p)) {
			$studentattempts = array_merge($studentattempts_p,$studentattempts_n);
		} elseif ($studentattempts_n) {
			$studentattempts = $studentattempts_n;
		} else { 
			$studentattempts = $studentattempts_p;
		}
    
        foreach ($studentattempts as $studentattempt) {
          if (strtolower($studentattempt->passed) == "true"){
            $totalwordsall      += $studentattempt->words;
            $totalwordscountall++;
          }
        }
        
        if (!empty($bookcoversinprevterm)) {
            echo "<br /><h2>".get_string("booksreadinpreviousterms", "reader")."</h2><br />".$bookcoversinprevterm."<br />".link_to_popup_window("{$CFG->wwwroot}/mod/reader/showincorrectquizzes.php?id={$id}&uid={$USER->id}", null, get_string("incorrectbooksreadinpreviousterms", "reader"), 440, 700, null, null, true)."<br /><hr />";
        }
        
        if (!empty($bookcoversinthisterm)) {
            echo "<br /><h2>".get_string("booksreadthisterm", "reader")."</h2><br />".$bookcoversinthisterm."<br /><br />";
        }
    
        echo "<br /><table width=\"100%\"><tr><td><h2><span style='background-color:orange'>".get_string("readingreportfor", "reader").": {$USER->firstname} {$USER->lastname} </span></h2>";
        if (isset($USER->realuser)) {
            echo '</td><td width="50%" align="right"><small><span style="text-align: right;"><a href="admin.php?a=admin&id='.$id.'&act=reports">'.get_string("returntostudentlist", "reader")."</a></span></small>";
        }
        echo "</td></tr></table>";
        if (!empty($table->data)) {
            echo '<center>'.html_writer::table($table).'</center>';
        }
        else
        {
            print_string("nodata", "reader");
        }
        
        /*
        Under table fields
        */
        
        if ($reader->wordsprogressbar) {
            echo '<table width="800"  cellpadding="5" cellspacing="1" class="generaltable boxaligncenter" >

    <tr><th width="500" style="text-align:right;font-weight:lighter;">'.get_string("totalwords", "reader").': '.$totalwords.'</th><th style="text-align:right;font-weight:lighter;">'.get_string("totalwordsall", "reader").": ".$totalwordsall.'</th></tr></table>';
    
            echo '<table width="820"  cellpadding="0" cellspacing="0" class="generaltable boxaligncenter" ><tr><td align="center">';
            if ($progressimage = reader_get_goal_progress($totalwords, $reader)) {
                echo '<table width="820px"><tr><td align="center"><div style="position:relative;z-index:5;height:100px;width:850px;">'.$progressimage.'</div>';
                echo '</td></tr><tr><td>&nbsp;<b>&nbsp;'.get_string("in1000sofwords", "reader").'</b></td></tr></table>';
            }
            echo '</td></tr></table>';
        }
        /*
    }
    else
    {
        echo "<br /><h2><span style='background-color:orange'>".get_string("readingreportfor", "reader").": {$USER->firstname} {$USER->lastname} </span></h2>";
        print_string("nodata", "reader");
    }
    */
    
    //if ($reader->nextlevel == $leveldata['onthislevel'] ) {$displaymore = "";} else {$displaymore = " more ";}
 
    echo "<h3>".get_string("yourcurrentlevel", "reader").": {$leveldata['studentlevel']}</h3>";
    
    $promoteinfo = $DB->get_record("reader_levels", array( "userid" => $USER->id,  "readerid" => $reader->id));
    if ($promoteinfo->nopromote == 1) {
        if ($promoteinfo->promotionstop == $leveldata['studentlevel']) {
            print_string("pleaseaskyourinstructor", "reader");
        }
        else
        {
            print_string("yourteacherhasstopped", "reader");
        }
        
        //-------Promotion stop On this level----------//
        print_string("youcantakeasmanyquizzesasyouwant", "reader", $leveldata['studentlevel']);
        
        //-------Promotion stop On prev level----------//
        if ($leveldata['onprevlevel'] <= 0) {
            $quizcount = 'no';
        }
        else
        {
            $quizcount = $leveldata['onprevlevel'];
        }
        if ($leveldata['onprevlevel'] == 1) { $quiztext = "quiz"; } else { $quiztext = "quizzes"; }
        print_string("youmayalsotake", "reader", $quizcount);
        echo "{$quiztext} ".get_string("atlevel", "reader")." ".($leveldata['studentlevel'] - 1)." ";
        
    }
    else if ($reader->levelcheck == 1)
    {
        //-------Promotion On his level----------//
        if ($leveldata['onthislevel'] == 1) {
            print_string("youmusttakequiz", "reader", $leveldata['onthislevel']);
        }
        else
        {
            print_string("youmusttakequizzes", "reader", $leveldata['onthislevel']);
        }
        print_string("atlevelbeforebeingpromoted", "reader", $leveldata['studentlevel']);
       
       
       
        //-------Promotion On prev level----------//
        if ($leveldata['onprevlevel'] <= 0) {
            $quizcount = 'no';
        }
        else
        {
            $quizcount = $leveldata['onprevlevel'];
        }
        if ($leveldata['onprevlevel'] == 1) { $quiztext = "quiz"; } else { $quiztext = "quizzes"; }
        
        if (($leveldata['studentlevel'] - 1) >= 0) {
            
            //-------Promotion On next level----------//
            if ($leveldata['onprevlevel'] > 0 && $leveldata['onnextlevel'] <= 0) {
                $quiznextlevelso = 'but';
            } else {
                $quiznextlevelso = 'and';
            }
            print_string("youmayalsotake", "reader", $quizcount);
//This line moved down to here
            echo "{$quiztext} ".get_string("atlevel", "reader")." ".($leveldata['studentlevel'] - 1)." ";
      }
        else
        {
            print_string("youcantake", "reader");
        }
        
        
        if ($leveldata['onnextlevel'] <= 0) {
            $quizcount = 'no';
        }
        else
        {
            $quizcount = $leveldata['onnextlevel'];
        }
        
        if (!isset($quiznextlevelso)) $quiznextlevelso = "";
        
        if ($leveldata['onnextlevel'] == 1) { $quiztext = " quiz "; } else { $quiztext = " quizzes "; }
        echo $quiznextlevelso.get_string("andnextmore", "reader", $quizcount).$quiztext.get_string("atlevel", "reader"). " " . ($leveldata['studentlevel'] + 1 .".");
    }
    else if ($reader->levelcheck == 0) 
    {
        print_string("butyoumaytakequizzes", "reader");
    }
    
    //-----------------Check time----------------------------------//
    //if ($reader->attemptsofday == 0) $reader->attemptsofday = 5;
    if ($reader->attemptsofday > 0) { // && $_SESSION['SESSION']->reader_teacherview != "teacherview"
        //$lastttempt = $DB->get_record_sql("SELECT * FROM {reader_attempts} WHERE reader= ? and userid= ?  ORDER by timefinish DESC",array($reader->id, $USER->id));
        //$lastttempt = $DB->get_record_sql("SELECT * FROM {reader_attempts} ra, {reader} r WHERE ra.userid= ?  and r.id=ra.reader and r.course= ?  ORDER by ra.timefinish DESC", array($USER->id, $course->id));
        $cleartime = $lastattemptdate + ($reader->attemptsofday * 24 * 60 * 60);
        $cleartime = reader_forcedtimedelay_check ($cleartime, $reader, $leveldata['studentlevel'], $lastattemptdate);
        $time = time();
        if ($time > $cleartime) {
            $showform = true;
            echo '<span style="background-color:#00CC00">&nbsp;&nbsp;'.get_string("youcantakeaquiznow", "reader").'&nbsp;&nbsp;</span>';
        }
        else
        {
            $showform = false;
            $approvetime = $cleartime - time();
            echo '<span style="background-color:#FF9900">&nbsp;&nbsp;'.get_string("youcantakeaquizafter", "reader").' '.reader_nicetime2($approvetime).'&nbsp;&nbsp;</span>';
                
        }
    }
    else
    {
        $showform = true;
    }
    //--------------------------------------------------------------//

    
    echo "<h3>".get_string("messagefromyourteacher", "reader")."</h3>";
    
    //print_simple_box_start('center', '700px', '#ffffff', 10);
    
    $messages = $DB->get_records_sql("SELECT * FROM {reader_messages} WHERE instance = ?", array($cm->instance));
    
    if (count($messages) > 0 && !empty($messages)) {
        $usergroupsarray = array(0);
        $studentgroups = groups_get_user_groups($course->id, $USER->id);
        foreach ($studentgroups as $studentgroup) {
            foreach ($studentgroup as $studentgroup_) {
                $usergroupsarray[] = $studentgroup_;
            }
        }
        //$messages = $DB->get_records_sql("SELECT * FROM {reader_messages} WHERE instance = ?", array($cm->instance));
        foreach ($messages as $message) {
          $forgroupsarray = explode (',', $message->users);
          $showmessage = false;
          $bgcolor  = '';
          
          foreach ($forgroupsarray as $forgroupsarray_) {
              if (in_array($forgroupsarray_, $usergroupsarray)) {
                  $showmessage = true;
              }
          }
          
          if ($message->timemodified > (time() - ( 48 * 60 * 60))) {
              $bgcolor = 'bgcolor="#CCFFCC"';
          }
          
          if ($showmessage) {
            echo '<table width="100%"><tr><td align="right"><table cellspacing="0" cellpadding="0" class="forumpost blogpost blog" '.$bgcolor.' width="90%">';
            echo '<tr><td align="left"><div style="margin-left: 10px;margin-right: 10px;">'."\n";
            echo format_text($message->text);
            echo '<div style="text-align:right"><small>';
            $teacherdata = $DB->get_record("user", array( "id" => $message->teacherid));
            echo "<a href=\"{$CFG->wwwroot}/user/view.php?id={$message->teacherid}&amp;course={$course->id}\">".fullname($teacherdata, true)."</a>";
            echo '</small></div>';
            echo '</div></td></tr></table></td></tr></table>'."\n\n"; 
          }
        }
    }
    
    
    echo "<h3>".get_string("selectthebookthatyouwant", "reader").":</h3>";
    
    //------GET LIST--------------//
    $publisherform  = Array("id=".$id."&publisher=Select Publisher" => get_string("selectpublisher", "reader"));
    $publisherform2 = Array("id=".$id."&publisher=Select Publisher" => get_string("selectpublisher", "reader"));
    $seriesform     = Array(get_string("selectseries", "reader"));
    $levelsform     = Array(get_string("selectlevel", "reader"));
    $booksform      = Array();
    
    //-------------Get list start----------------//
    $alreadyansweredbooksid = array();
    $leveldata              = reader_get_stlevel_data($reader);
    $promoteinfo            = $DB->get_record("reader_levels", array( "userid" => $USER->id,  "readerid" => $reader->id));
    $allowdifficultysql     = "";
    
    if ($leveldata['onthislevel'] > 0) $allowdifficultysql .= $leveldata['studentlevel'].",";
    if ($leveldata['onprevlevel'] > 0 && ($leveldata['studentlevel'] - 1) >= 0) $allowdifficultysql .= ($leveldata['studentlevel'] - 1).",";
    if ($leveldata['onnextlevel'] > 0) $allowdifficultysql .= ($leveldata['studentlevel'] + 1).",";
    $allowdifficultysql = substr($allowdifficultysql, 0, -1);
    if ((isset($_SESSION['SESSION']->reader_teacherview) && $_SESSION['SESSION']->reader_teacherview == "teacherview") || $reader->levelcheck == 0) $allowdifficultysql = '0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15';
    
    if (list($attemptdata, $summaryattemptdata) = reader_get_student_attempts($USER->id, $reader, true, true)) {
        foreach ($attemptdata as $attemptdata_) {
            reader_set_attempt_result ($attemptdata_['id'], $reader);  //insert result
            $alreadyansweredbooksid[] = $attemptdata_['quizid'];
            if (!empty($attemptdata_['sametitle'])) $alreadyansweredbookssametitle[] = $attemptdata_['sametitle'];
        }
    }
    
    $publishers = $DB->get_records ("reader_publisher", NULL, "publisher");
    foreach ($publishers as $publisher_) {
        $publisherform["id=".$id."&publisher=".$publisher_->publisher] = $publisher_->publisher;
    }
    
    
    foreach ($publisherform as $key => $value) {
        $needtousepublisher = false;
        if ($allowdifficultysql) {
            if ($reader->individualbooks == 1) {
                $books = $DB->get_records_sql("SELECT * FROM {reader_publisher} rp INNER JOIN {reader_individual_books} ib ON ib.bookid = rp.id WHERE ib.readerid = ? and rp.publisher= ? and rp.hidden='0' and rp.private IN(0, ? ) and ib.difficulty IN( ".$allowdifficultysql." ) ORDER BY rp.name", array($reader->id, $value, $reader->id));
            }
            else
            {
                $books = $DB->get_records_sql("SELECT * FROM {reader_publisher} WHERE publisher= ? and hidden='0' and private IN(0, ? ) and difficulty IN( ".$allowdifficultysql." ) ORDER BY name", array($value, $reader->id));
            }
            foreach ($books as $books_) {
                if (!empty($books_->quizid)) {
                    if ($reader->individualbooks == 1) {
                        if (!in_array($books_->bookid, $alreadyansweredbooksid)) $needtousepublisher = true;
                    }
                    else
                    {
                        if (!in_array($books_->id, $alreadyansweredbooksid)) $needtousepublisher = true;
                    }
                    
                    if ($showform) {
                        if (!empty($books_->sametitle) && is_array($alreadyansweredbookssametitle)) {
                            if ($reader->individualbooks == 1) {
                                if (!in_array($books_->sametitle, $alreadyansweredbookssametitle)) { $needtousepublisher = true; break; }
                            }
                            else
                            {
                                if (!in_array($books_->sametitle, $alreadyansweredbookssametitle)) { $needtousepublisher = true; break; }
                            }
                        }
                        else
                        {
							if ($reader->individualbooks == 1) {
								$needtousepublisher = true;
								break;
							}
							else
							{
								$needtousepublisher = true;
								break;
							}
                        }
                    }
                }
            }
        }
        if ($needtousepublisher) {
            $publisherform2["id=".$id."&publisher=".$value] = $value;
        }
    }
    unset($publisherform);
    $publisherform = $publisherform2;
    //-------------Get list finish----------------//
    
    if ($notcomplitequiz = $DB->get_record("reader_attempts", array( "reader" => $cm->instance,  "userid" => $USER->id,  "timefinish" => 0))) {
        $showform = false;
        
        $timelimit = 60 * $reader->timelimit;
        
        if ($timelimit < (time() - $notcomplitequiz->timestart)) {
            $showform = true;
            
            $DB->set_field("reader_attempts", "timemodified", time(), array("id" => $notcomplitequiz->id));
            $DB->set_field("reader_attempts", "timefinish", time(), array("id" => $notcomplitequiz->id));
            $DB->set_field("reader_attempts",  "passed",  "false", array( "id" => $notcomplitequiz->id));
            $DB->set_field("reader_attempts",  "persent",  "0", array( "id" => $notcomplitequiz->id));
            $DB->set_field("reader_attempts",  "sumgrades",  "0", array( "id" => $notcomplitequiz->id));
            $DB->set_field("reader_attempts",  "bookrating",  0, array( "id" => $notcomplitequiz->id));
        }
        else
        {
            $bookdata = $DB->get_record("reader_publisher", array( "id" => $notcomplitequiz->quizid));
        
            print_string("pleasecompletequiz", "reader", $bookdata->name);
            //attempt.php?attempt=12910&page=1#q0
            echo ' <a href="attempt.php?'.$_SESSION['SESSION']->reader_lastattemptpage.'">'.get_string("complete", "reader").'</a>';
        }
    }
    
    
    if (isset($_SESSION['SESSION']->reader_changetostudentview))
      if ($showform == false && $_SESSION['SESSION']->reader_changetostudentview > 0) { 
          echo "<br />".get_string("thisblockunavailable", "reader")."<br />";
          $showform == true;
      }
    
    
    if ($showform && has_capability('mod/reader:attemptreaders', $contextmodule)) {
    
echo "<script type=\"text/javascript\">
function validateForm(form) {
    // NEW LINES START
    return (form && form.book && isChosen(form.book));
    // NEW LINES STOP
    /* OLD LINES START **
    if (isChosen(form.book)) {
        return true;
    }
    return false;
    ** OLD LINES STOP **/
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


        echo '<form action="startattempt.php?id='.$id.'" method="post" id="mform1">';
        echo '<center><table width="600px">';
        echo '<tr><td width="200px">'.get_string("publisherseries", "reader").'</td><td width="10px"></td><td width="200px"></td></tr>';
        echo '<tr><td valign="top">';
        echo '<select name="publisher" id="id_publisher" onchange="request(\'view_get_bookslist.php?ajax=true&\' + this.options[this.selectedIndex].value,\'selectthebook\'); return false;">';
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
        echo '<tr><td colspan="3" align="center"><input type="button" value="Take quiz" onclick="if (validateForm(this.form)) {this.form.submit() };" /> <input value="'.get_string("returntocoursepage", "reader").'" onclick="location.href=\''.$CFG->wwwroot.'/course/view.php?id='.$course->id.'\'" type="button" /></td></tr>';
        echo '</table>';
        echo '</form></center>';
    
    }
    else if (!$DB->get_record("reader_attempts", array( "reader" => $cm->instance,  "userid" => $USER->id,  "timefinish" => 0)))
    {
        print_string("pleasewait", "reader");
    }
    
    
    
    echo $OUTPUT->box_end();
    
    print ("<center><img src='img/credit.jpg' height='40px'></center>");

    echo $OUTPUT->footer();