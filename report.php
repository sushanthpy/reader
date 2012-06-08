<?php 

    require_once("../../config.php");
    require_once("lib.php");

$b                     = optional_param('b', 0, PARAM_INT); 


if (!empty($b)) {
  if ($data = $DB->get_records("reader_attempts", array("quizid" => $b))) {
    if ($datapub = $DB->get_record("reader_publisher", array("id" => $b))) 
      $quizid = $datapub->quizid;
    
    
    while(list($key,$value)=each($data)){
      reader_put_to_quiz_attempt($value->id);
    }
  }
}

if (!empty($quizid)) {
  if ($cm = get_coursemodule_from_instance("quiz", $quizid)) {
    $site_url = $CFG->wwwroot.'/mod/quiz/report.php?id='.$cm->id.'&mode=responses';
    echo("<script> top.location.href='" . $site_url . "'</script>");
  }
} else
  echo '<h1>No attempts found</h1>';
  
  