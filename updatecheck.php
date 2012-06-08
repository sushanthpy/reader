<?php

    require_once("../../config.php");
    require_once("lib.php");

    $id                = optional_param('id', 0, PARAM_INT);
    $a                 = optional_param('a', NULL, PARAM_CLEAN);
    $quiz              = optional_param('quiz', NULL, PARAM_CLEAN);
    $newquizzes        = optional_param('newquizzes', NULL, PARAM_CLEAN);
    $updatedquizzes    = optional_param('updatedquizzes', NULL, PARAM_CLEAN);
    $quiz              = optional_param('updatedquizzes', NULL, PARAM_CLEAN);
    $newquizzesto      = optional_param('newquizzesto', NULL, PARAM_CLEAN);
    $json              = optional_param('json', NULL, PARAM_CLEAN);
    $checker           = optional_param('checker', 0, PARAM_INT);

    //$readercfg->reader_last_update = $readercfg->reader_last_update - 31 * 24 * 3600;       //Убрать потом

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

    $readercfg = get_config('reader');

    $context = get_context_instance(CONTEXT_COURSE, $course->id);
    $contextmodule = get_context_instance(CONTEXT_MODULE, $cm->id);
    if (!has_capability('mod/reader:manage', $contextmodule)) {
        error("You should be Admin");
    }

    add_to_log($course->id, "reader", "Download Quizzes Process", "dlquizzes.php?id=$id", "$cm->instance");

// Initialize $PAGE, compute blocks
    $PAGE->set_url('/mod/reader/updatecheck.php', array('id' => $cm->id));

    $title = $course->shortname . ': ' . format_string($reader->name);
    $PAGE->set_title($title);
    $PAGE->set_heading($course->fullname);
    /*
    $navigation = build_navigation('', $cm);

    print_header_simple(format_string($reader->name), "", $navigation, "", "", true,
                      update_module_button($cm->id, $course->id, get_string('modulename', 'reader')), navmenu($course, $cm));
    */

    echo $OUTPUT->header();

    require_once ('tabs_dl.php');

    echo $OUTPUT->box_start('generalbox');

    if ($checker == 1) {
        echo "<center>"; print_string("lastupdatedtime", "reader", date("d M Y", $readercfg->reader_last_update));
        echo ' <br /> <a href="updatecheck.php?id='.$id.'">YES</a> / <a href="admin.php?a=admin&id='.$id.'">NO</a></center> ';

        /* OLD LINES START **
        print_footer($course);
        ** OLD LINES STOP **/
        // NEW LINES START
        echo $OUTPUT->box_end();
        echo $OUTPUT->footer();
        // NEW LINES STOP
        die();
    }

/** Find all readers **/
    $readersarr = $DB->get_records ("reader");
    while (list($key,$reader) = each($readersarr)) {
        $datareaders[$reader->id]['ignordate'] = $reader->ignordate;
        $usersarr = $DB->get_records_sql("SELECT DISTINCT userid FROM {reader_attempts} WHERE reader= ? and timestart >= ?", array($reader->id, $reader->ignordate));
        // NEW LINES START
        if (empty($usersarr)) {
            $usersarr = array();
        }
        // NEW LINES STOP
        $datareaders[$reader->id]['totalusers'] = count($usersarr);
        $attemptsarr = $DB->get_records_sql("SELECT id FROM {reader_attempts} WHERE reader= ? and timestart >= ?", array($reader->id, $reader->ignordate));
        // NEW LINES START
        if (empty($attemptsarr) || empty($usersarr)) {
            $datareaders[$reader->id]['attemptsaver'] = 0;
        } else {
            $datareaders[$reader->id]['attemptsaver'] = round(count($attemptsarr) / count($usersarr), 1);
        }
        // NEW LINES STOP
        /* OLD LINES START **
        $datareaders[$reader->id]['attemptsaver'] = round(count($attemptsarr) / count($usersarr), 1);
        ** OLD LINES STOP **/
        $datareaders[$reader->id]['course'] = $reader->course;
        $course = $DB->get_record('course', array('id' => $reader->course));
        $datareaders[$reader->id]['short_name'] = $course->shortname;
        $r[$reader->id]['course'] = $reader->course;
        $r[$reader->id]['short_name'] = $course->shortname;
    }
    /**=============**/

    $publishers = $DB->get_records_sql("SELECT * FROM {reader_publisher} WHERE hidden != 1");

    while (list($key,$book) = each($publishers)) {
        //echo "SELECT passed,bookrating FROM {$CFG->prefix}reader_attempts WHERE quizid = {$book->id}"."<br />";
        if ($book->time < 10) $book->time = $readercfg->reader_last_update;



        $attempts = $DB->get_records_sql("SELECT id,passed,bookrating,reader FROM {reader_attempts} WHERE quizid = ?", array($book->id));
        unset($rate,$c);
        $c = array();
        $data = array();
        $rate = array();
        if (is_array($attempts)) {
            while(list($key2,$attempt) = each($attempts)) {
                @$c[$attempt->reader]++;
                if ($attempt->passed == 'TRUE' || $attempt->passed == 'true') {
                    $data[$attempt->reader][$book->image]['true']++;
                } else if ($attempt->passed == 'credit') {
                    $data[$attempt->reader][$book->image]['credit']++;
                } else {
                    @$data[$attempt->reader][$book->image]['false']++;
                }
                @$rate[$attempt->reader] = $attempt->bookrating + $rate[$attempt->reader];
            }
        } else {
            $data[0][$book->image]['true']       = 0;
            $data[0][$book->image]['false']      = 0;
            $data[0][$book->image]['credit']     = 0;
            $data[0][$book->image]['rate']       = 0;
            $data[0][$book->image]['course']     = 1;
            $data[0][$book->image]['time']       = $book->time;
            $data[0][$book->image]['short_name'] = 'NOTUSED';
        }

        reset($readersarr);
        while (list($key,$reader) = each($readersarr)) {
            //echo "{$rate[$reader->id]}  / {$c[$reader->id]} <br />";
            if (isset($data[$reader->id][$book->image]['true']) || isset($data[$reader->id][$book->image]['credit']) || isset($data[$reader->id][$book->image]['false'])) {
                $data[$reader->id][$book->image]['rate'] = round($rate[$reader->id] / $c[$reader->id],1);
                $data[$reader->id][$book->image]['course'] = $r[$reader->id]['course'];
                $data[$reader->id][$book->image]['time'] = $book->time;
                $data[$reader->id][$book->image]['short_name'] = $r[$reader->id]['short_name'];
            }
        }
    }

    $jdata['userlogin']  = $readercfg->reader_serverlogin;
    $jdata['lastupdate'] = $readercfg->reader_last_update;
    $jdata['books']      = $data;
    $jdata['readers']    = $datareaders;

    $json = json_encode($jdata);

    $postdata = http_build_query(
        array(
            'json' => $json
        )
    );

    $opts = array('http' =>
        array(
            'method'  => 'POST',
            'header'  => 'Content-type: application/x-www-form-urlencoded',
            'content' => $postdata
        )
    );

    $context  = stream_context_create($opts);

    $result = file_get_contents($readercfg->reader_serverlink."/update_quizzes.php", false, $context);

    //echo stripslashes($result);

    $needqudate = json_decode(stripslashes($result));

    unset($data);

    // NEW LINES START
    $cp = 0;
    // NEW LINES STOP

    if (is_object($needqudate)) {
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

        echo '<form action="dlquizzes.php?id='.$id.'" method="post" id="mform1">';
        echo '<div style="width:600px"><a href="#" onclick="expandall();">Show All</a> / <a href="#" onclick="collapseall();">Hide All</a><br />';

        //vivod
        /* OLD LINES START **
        $cp = 0;
        ** OLD LINES STOP **/
        $allquestionscount = 0;
        $newcheckboxes = "";
        $updatedcheckboxes = "";

        while(list($key, $value) = each($needqudate)) {
            while(list($key2, $value2) = each($value)) {
                while(list($key3, $value3) = each($value2)) {
                    $allquestionscount++;
                    $checkboxdatapublishersreal[$key][] = $key3;
                    $checkboxdatalevelsreal[$key][$key2][] = $key3;

                    $checkboxdatapublishers[$key][] = $allquestionscount;
                    $checkboxdatalevels[$key][$key2][] = $allquestionscount;
                    $quizzescountid[$key3] = $allquestionscount;

                    if (strstr($value3, "UPDATE::"))
                        $updatedcheckboxes .= $allquestionscount . ",";
                    if (strstr($value3, "NEW::"))
                        $newcheckboxes .= $allquestionscount . ",";
                }
            }
        }

        $updatedcheckboxes = substr($updatedcheckboxes,0,-1);
        $newcheckboxes = substr($newcheckboxes,0,-1);

        echo '<div><input type="button" name="selectnew" value="Select all new" onclick="expandall2();setcheckedbyid(\''.$newcheckboxes.'\');" /> <input type="button" name="selectupdated" value="Select all updated" onclick="expandall2();setcheckedbyid(\''.$updatedcheckboxes.'\');" />

     <input type="button" name="selectupdated" value="Clear all selections" onclick="uncheckall();" />
    </div>'; //uncheckall

    /*
    <input type="hidden" name="newquizzes" value="'.$newcheckboxes.'"/>
    <input type="hidden" name="updatedquizzes" value="'.$updatedcheckboxes.'"/>
    <input type="hidden" name="json" value="'.urlencode($result).'"/>


    echo '<div><input type="radio" name="newquizzesto" value="1" checked />
     '.get_string("makenewquizzesavailable2", "reader").'<br />
     <input type="radio" name="newquizzesto" value="2" />

     '.get_string("makenewquizzesavailable", "reader").' <br />
     ('.get_string("quizupdateswillbeapplied", "reader").')
     </div>';
    */

        echo '
<script type="text/javascript" defer="defer">
//<![CDATA[
function uncheckall() {
  void(d=document);
  void(el=d.getElementsByTagName(\'INPUT\'));
  for(i=0;i<el.length;i++) {
    void(el[i].checked=0);
  }
}

function checkall() {
  void(d=document);
  void(el=d.getElementsByTagName(\'INPUT\'));
  for(i=0;i<el.length;i++) {
    void(el[i].checked=1);
  }
}
//]]>
</script>
    </script>';

        reset($needqudate);
        while(list($publiher, $datas) = each($needqudate)) {
            $cp++;
            echo '<br /><a href="#" onclick="toggle(\'comments_'.$cp.'\');return false">
            <span id="comments_'.$cp.'indicator"><img src="'.$CFG->wwwroot.'/mod/reader/open.gif" alt="Opened folder" /></span></a> ';
            echo ' <b>'.$publiher.'</b>';

            echo '<span id="comments_'.$cp.'"><input type="checkbox" name="installall['.$cp.']" onclick="setChecked(this,'.$checkboxdatapublishers[$publiher][0].','.end($checkboxdatapublishers[$publiher]).')" value="" /><span id="seltext_'.$cp.'">Install All</span>';
            //print_r ($datas);
            reset($datas);
            while(list($level, $quizzesdata) = each($datas)) {
                $cp++;
                echo '<div style="padding-left:40px;padding-top:10px;padding-bottom:10px;"><a href="#" onclick="toggle(\'comments_'.$cp.'\');return false">
                <span id="comments_'.$cp.'indicator"><img src="'.$CFG->wwwroot.'/mod/reader/open.gif" alt="Opened folder" /></span></a> ';

                //if ($needpassword[$publiher][$level] == "true") {
                //echo ' <img src="'.$CFG->wwwroot.'/mod/reader/pw.png" width="23" height="15" alt="Need password" /> ';
                //}

                echo '<b>'.$level.'</b>';
                echo '<span id="comments_'.$cp.'"><input type="checkbox" name="installall['.$cp.']" onclick="setChecked(this,'.$checkboxdatalevels[$publiher][$level][0].','.end($checkboxdatalevels[$publiher][$level]).')" value="" /><span id="seltext_'.$cp.'">Install All</span>';
                reset($quizzesdata);
                while(list($quizid, $quiztitle) = each($quizzesdata)) {
                    if (strstr($quiztitle, "NEW::")) {$quiztitle = substr($quiztitle, 5); $mark = 'New';}
                    if (strstr($quiztitle, "UPDATE::")) {$quiztitle = substr($quiztitle, 8); $mark = 'Updated';}
                    echo '<div style="padding-left:20px;"><span style="color:blue;">'.$mark.'</span><input type="checkbox" name="quiz[]" id="quiz_'.$quizzescountid[$quizid].'" value="'.$quizid.'" />'.$quiztitle.'</div>';
                }
                echo '</span></div>';
            }
            echo '</span>';
        }

        echo '<div style="margin-top:40px;margin-left:200px;"><input type="submit" name="downloadquizzes" value="Install Quizzes" /></div>';

        echo '</div>';
        echo '</form>';

        echo $OUTPUT->box_end();
    } else {
        echo $OUTPUT->box_start('generalbox');

        print_string("therehavebeennonewquizzesorupdates", "reader");

        echo $OUTPUT->box_end();
    }

//print_r ($needqudate);

//echo 'done';

    echo $OUTPUT->box_end();

    echo '<script type="text/javascript">
//<![CDATA[
var spanmark = 1;
var vh_content = new Array();
function getspan(spanid) {
  if (document.getElementById) {
    return document.getElementById(spanid);
  } else if (window[spanid]) {
    return window[spanid];
  }
  return null;
}
function toggle(spanid) {
  if (getspan(spanid).innerHTML == "") {
    getspan(spanid).innerHTML = vh_content[spanid];
    getspan(spanid + "indicator").innerHTML = \'<img src="'.$CFG->wwwroot.'/mod/reader/open.gif" alt="Opened folder" />\';
  } else {
    vh_content[spanid] = getspan(spanid).innerHTML;
    getspan(spanid).innerHTML = "";
    getspan(spanid + "indicator").innerHTML = \'<img src="'.$CFG->wwwroot.'/mod/reader/closed.gif" alt="Closed folder" />\';
  }
}
function collapse(spanid) {
  if (getspan(spanid).innerHTML !== "") {
    vh_content[spanid] = getspan(spanid).innerHTML;
    getspan(spanid).innerHTML = "";
    getspan(spanid + "indicator").innerHTML = \'<img src="'.$CFG->wwwroot.'/mod/reader/closed.gif" alt="Closed folder" />\';
  }
}
function expand(spanid) {
  getspan(spanid).innerHTML = vh_content[spanid];
  getspan(spanid + "indicator").innerHTML = \'<img src="'.$CFG->wwwroot.'/mod/reader/open.gif" alt="Opened folder" />\';
}
function expandall() {
  for (i = 1; i <= vh_numspans; i++) {
    expand("comments_" + String(i));
  }
}
function collapseall() {
  for (i = vh_numspans; i > 0; i--) {
    collapse("comments_" + String(i));
  }
}
function expandall2() {
  if (window.spanmark == 1) {
    for (i = 1; i <= vh_numspans; i++) {
      expand("comments_" + String(i));
    }
    window.spanmark = 2;
  }
}
function setcheckedbyid(ids) {
  var pos=ids.indexOf(",");
  if (pos>=0) {
    var myArray = ids.split(",");
    for (i = 0; i < myArray.length; i++) {
      document.getElementById("quiz_" + myArray[i]).checked = true;
    }
  } else {
    document.getElementById("quiz_" + ids).checked = true;
  }
}
//]]>

</script>
';

    echo '<script type="text/javascript">
//<![CDATA[
var vh_numspans = '.$cp.';
collapseall();
//]]>

</script>';

    //}

    echo $OUTPUT->footer();

    $DB->set_field("config_plugins", "value", time(), array("name" => "reader_last_update"));
