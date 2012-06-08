<?php


require_once(dirname(__FILE__) . '/../../config.php');
require_once($CFG->dirroot . '/mod/reader/attemptlib.php');
require_once($CFG->dirroot . '/mod/reader/lib.php');
require_once($CFG->dirroot . '/mod/reader/accessrules.php');
require_once($CFG->dirroot . '/question/engine/lib.php');


// Get submitted parameters.
$attemptid = required_param('attempt', PARAM_INT);
$page = optional_param('page', 0, PARAM_INT);
$id = optional_param('id', 0, PARAM_INT);

$attemptobj = reader_attempt::create($attemptid);

$PAGE->set_url($attemptobj->attempt_url(0, $page));

// Check login.
require_login($attemptobj->get_course(), false, $attemptobj->get_cm());

// Check that this attempt belongs to this user.
if ($attemptobj->get_userid() != $USER->id) {
    if ($attemptobj->has_capability('mod/reader:viewreports')) {
        redirect($attemptobj->review_url(0, $page));
    } else {
        throw new moodle_reader_exception($attemptobj->get_readerobj(), 'notyourattempt');
    }
}



navigation_node::override_active_url($attemptobj->start_attempt_url());

// If the attempt is already closed, send them to the review page.

if ($attemptobj->is_finished()) {
    redirect($attemptobj->review_url(0, $page));
}

// Check the access rules.
$output = $PAGE->get_renderer('mod_quiz');
$accessmanager = $attemptobj->get_access_manager(time());
$messages = $accessmanager->prevent_access();

/*
add_to_log($attemptobj->get_courseid(), 'reader', 'continue attempt',
        'review.php?attempt=' . $attemptobj->get_attemptid(),
        0, 0);
add_to_log($attemptobj->readerobj->course->id, "reader", "finish attempt: ".addslashes($attemptobj->readerobj->book->name), "view.php?id={$attemptobj->readerobj->reader->id}", $attemptobj->attempt->persent."%/".$attemptobj->attempt->passed);
*/
$pagetext = $page + 1;
$logtext  = "readerID {$attemptobj->readerobj->reader->id}; reader quiz {$attemptobj->readerobj->book->id}; page: {$pagetext}";
//if ($data = $DB->get_record("log", array("userid" => $USER->id, "course" => $attemptobj->readerobj->course->id, "info" => $logtext)) {
  add_to_log($attemptobj->readerobj->course->id, "reader", "view attempt: ".addslashes($attemptobj->readerobj->book->name), "view.php?id=$id", $logtext);
//}


// Get the list of questions needed by this page.
$slots = $attemptobj->get_slots($page);

// Check.
if (empty($slots)) {
    throw new moodle_reader_exception($attemptobj->get_readerobj(), 'noquestionsfound');
}

// Initialise the JavaScript.
$headtags = $attemptobj->get_html_head_contributions($page);
$PAGE->requires->js_init_call('M.mod_quiz.init_attempt_form', null, false, reader_get_js_module());


// Arrange for the navigation to be displayed.
$headtags = $attemptobj->get_html_head_contributions($page);
$PAGE->set_heading($attemptobj->get_course()->fullname);
$PAGE->set_title(format_string($attemptobj->get_reader_name()));

//echo $OUTPUT->header();

if ($attemptobj->is_last_page($page)) {
    $nextpage = -1;
} else {
    $nextpage = $page + 1;
}

//print_r ($page);
//die();

//print_r ($attemptobj);

$accessmanager->show_attempt_timer_if_needed($attemptobj->get_attempt(), time());

if ($attemptobj->readerobj->reader->timelimit > 0) {
    //print_r ($attemptobj);
    //echo $attemptobj->readerobj->reader->timelimit."/";
    //echo $attemptobj->attempt->timestart;
    $totaltimertime = $attemptobj->readerobj->reader->timelimit * 60 - (time() - $attemptobj->attempt->timestart);
    if ($totaltimertime < 0) $totaltimertime = 0;

  echo '
  <script>

    var timeminuse = 0;
    var totaltime  = '.$totaltimertime.';
    
    function showDiv() {
      timeminuse = timeminuse + 1;
      var timer = totaltime - timeminuse;
      if (timer >= 0) 
        UpdateTimer(timer);
    }
    
  function UpdateTimer(Seconds) {
      var Days = Math.floor(Seconds / 86400);
      Seconds -= Days * 86400;

      var Hours = Math.floor(Seconds / 3600);
      Seconds -= Hours * (3600);

      var Minutes = Math.floor(Seconds / 60);
      Seconds -= Minutes * (60);

      var TimeStr = ((Days > 0) ? Days + " days " : "") + ((Hours > 0) ? LeadingZero(Hours) + ":" : "") + LeadingZero(Minutes) + ":" + LeadingZero(Seconds);
      
      document.getElementById(\'fixededit\').innerHTML = TimeStr;
  }


  function LeadingZero(Time) {
      return (Time < 10) ? "0" + Time : + Time;
  }
    
    var timer = setInterval( showDiv, 1000);
  </script>
  <style>
  #fixededit{position:fixed;width:117px;height:54px;bottom:0;z-index:1000;background-color:#fff;border:1px solid #ddd;font-size:27px;text-align:center;padding-top: 20px;}
  </style>
  <div id="fixededit"></div>
  ';

}

$_SESSION['SESSION']->reader_lastattemptpage = $_SERVER['QUERY_STRING'];

echo $output->attempt_page($attemptobj, $page, $accessmanager, $messages, $slots, $id, $nextpage);

//print_r ($_SESSION['SESSION']);
//echo $OUTPUT->footer();
