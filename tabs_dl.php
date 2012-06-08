<?php // $Id: ,v 1.0 2008/03/20 16:41:20 serafim panov 

$tabs = array();
$row  = array();
$inactive = array();
$activated = array();

$row[] = new tabobject('quizes', "view.php?a=quizes&id=".$id, "Quizzes");
$row[] = new tabobject('admin', "admin.php?a=admin&id=".$id, "Admin Area");
$row[] = new tabobject('download', "", "Download Quizzes");

$tabs[] = $row;

print_tabs($tabs, 'download', $inactive, $activated);

