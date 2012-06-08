<?php

    require_once("../../config.php");
    require_once("lib.php");

    $id        = optional_param('id', 0, PARAM_INT); 
    $a         = optional_param('a', NULL, PARAM_CLEAN); 
    $v         = optional_param('v', NULL, PARAM_CLEAN); 
    $publisher = optional_param('publisher', NULL, PARAM_CLEAN);
    $onlypub   = optional_param('onlypub', NULL, PARAM_CLEAN);
    
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

    add_to_log($course->id, "reader", "Ajax get list of books", "view.php?id=$id", "$cm->instance");
    
    
    if ($onlypub == 1) {
        $books = $DB->get_records_sql("SELECT * FROM {reader_publisher} WHERE publisher= ? and hidden='0' ORDER BY name", array($publisher));
        foreach ($books as $books_) {
            $booksform[$books_->id] = "{$books_->name} ({$books_->level}[RL ".reader_get_reader_difficulty($reader, $books_->id)."])";
        }
        if (count($booksform) > 0) {
            echo '<select multiple size="10" name="book" id="id_book" style="width: 500px;">';
            foreach ($booksform as $booksformkey => $booksformvalue) {
                echo '<option value="'.$booksformkey.'">'.$booksformvalue.'</option>';
            }
            echo '</select>';
        }
        else
        {
            print_string('nobooksinlist', 'reader');
        }
        die();
    }
    
    
    //----check admin by user view-----//
    if (isset($_SESSION['SESSION']->reader_lastuser) && $_SESSION['SESSION']->reader_lastuser > 0) {
        $_SESSION['SESSION']->reader_teacherview = "studentview";
        $USER = $DB->get_record("user", array( "id" => $_SESSION['SESSION']->reader_lastuser));
    }
    //----check admin by user view--END//
    
    $alreadyansweredbooksid = array();
    
    $leveldata          = reader_get_stlevel_data($reader);
    $promoteinfo        = $DB->get_record("reader_levels", array( "userid" => $USER->id,  "readerid" => $reader->id));
    $allowdifficultysql = "";
    
    if ($leveldata['onthislevel'] > 0) $allowdifficultysql .= $leveldata['studentlevel'].",";
    if ($leveldata['onprevlevel'] > 0) $allowdifficultysql .= ($leveldata['studentlevel'] - 1).",";
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
    
    if ($publisher) {
        if (strstr($publisher, 'publisher=')) {
            $pubdata = explode ('publisher=', $publisher);
            $pubdata_ = explode ('&', $pubdata[1]);
            $publisher = $pubdata_[0];
        }
        
        if ($allowdifficultysql) {
            if ($reader->individualbooks == 1) {
                $books = $DB->get_records_sql("SELECT * FROM {reader_publisher} rp INNER JOIN {reader_individual_books} ib ON ib.bookid = rp.id WHERE ib.readerid =  ? and rp.publisher= ? and rp.hidden='0' and rp.private IN(0, ?) and ib.difficulty IN( ".$allowdifficultysql." ) ORDER BY rp.name", array($reader->id, $publisher, $reader->id));
            }
            else
            {
                $books = $DB->get_records_sql("SELECT * FROM {reader_publisher} WHERE publisher= ? and hidden='0' and private IN(0, ? ) and difficulty IN( ".$allowdifficultysql." ) ORDER BY name", array($publisher, $reader->id));
            }
         //   print_object($books);
            foreach ($books as $books_) {
                if (empty($books_->quizid)) {
                    $categoriedata = $DB->get_record("question_categories", array( "name" => $books_->name));
                    if (!in_array($categoriedata->id, $alreadyansweredbooksid)) {
                        $booksform[$categoriedata->id] = $books_->name;
                    }
                }
                else
                {
                    $showform = false;
                    if ($reader->individualbooks == 1) {
                        if (!in_array($books_->bookid, $alreadyansweredbooksid)) $showform = true;
                    }
                    else
                    {
                        if (!in_array($books_->id, $alreadyansweredbooksid)) $showform = true;
                    }
                    
                    if ($showform) {
                        if (!empty($books_->sametitle) && is_array($alreadyansweredbookssametitle)) {
                            if ($reader->individualbooks == 1) {
                                if (!in_array($books_->sametitle, $alreadyansweredbookssametitle)) $booksform[$books_->bookid] = "{$books_->name} ({$books_->level}[RL ".reader_get_reader_difficulty($reader, $books_->bookid)."])";
                            }
                            else
                            {
                                if (!in_array($books_->sametitle, $alreadyansweredbookssametitle)) $booksform[$books_->id] = "{$books_->name} ({$books_->level}[RL ".reader_get_reader_difficulty($reader, $books_->id)."])";
                            }
                        }
                        else
                        {
                        //if ($promoteinfo->nopromote == 0 || $promoteinfo->promotionstop >= $leveldata['studentlevel']) {
                        if ($reader->individualbooks == 1) {
                            $booksform[$books_->bookid] = "{$books_->name} ({$books_->level}[RL ".reader_get_reader_difficulty($reader, $books_->bookid)."])";
                        }
                        else
                        {
                            $booksform[$books_->id] = "{$books_->name} ({$books_->level}[RL ".reader_get_reader_difficulty($reader, $books_->id)."])";
                        }
                        //}
                        }
                    }
                }
            }
        }
    }
//    print_object($booksform);
    if ($publisher != "Select Publisher") {
        if (count($booksform) > 0) {
            echo '<select multiple size="10" name="book" id="id_book" style="width: 500px;">';
            foreach ($booksform as $booksformkey => $booksformvalue) {
                echo '<option value="'.$booksformkey.'">'.$booksformvalue.'</option>';
            }
            echo '</select>';
        }
        else
        {
            print_string('nobooksinlist', 'reader');
        }
    }
    else
    {
        print_string('pleaseselectpublisher', 'reader');
    }
    //----check admin by user view-----//
    if (isset($_SESSION['SESSION']->reader_lastuser) && $_SESSION['SESSION']->reader_lastuser > 0) {
        $USER = $DB->get_record("user", array( "id" => $_SESSION['SESSION']->reader_lastuserfrom));
    }
    //----check admin by user view--END//
