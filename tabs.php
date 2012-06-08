<?php // $Id: ,v 1.0 2008/03/20 16:41:20 serafim panov 

$currenttab = $a;
if (!isset($currenttab)) {
    $currenttab = 'quizes';
}

if (!isset($idh)) {
    $idh = $id;
}

if (!isset($cm)) {
    if (! $cm = $DB->get_record("course_modules", array( "id" => $idh))) {
        error("Course Module ID was incorrect");
    }
}

$context = get_context_instance(CONTEXT_MODULE, $cm->id);

if (!isset($contexts)){
    $contexts = new question_edit_contexts($context);
}
$tabs = array();
$row  = array();
$inactive = array();
$activated = array();

$row[] = new tabobject('quizes', "view.php?a=quizes&id=".$idh, "Quizzes");
$row[] = new tabobject('admin', "admin.php?a=admin&id=".$idh, "Admin Area");

$tabs[] = $row;

if ($currenttab == 'admin' and isset($mode)) {
    $inactive[] = 'admin';
    $activated[] = 'admin';

    // Standard reports we want to show first.
    $reportlist = array ('overview', 'regrade', 'grading', 'analysis');
    // Reports that are restricted by capability.
    $reportrestrictions = array(
        'regrade' => 'mod/quiz:grade',
        'grading' => 'mod/quiz:grade'
    );

    $allreports = get_list_of_plugins("mod/quiz/report");
    foreach ($allreports as $report) {
        if (!in_array($report, $reportlist)) {
            $reportlist[] = $report;
        }
    }

    $row  = array();
    $currenttab = '';
    foreach ($reportlist as $report) {
        if (!isset($reportrestrictions[$report]) || has_capability($reportrestrictions[$report], $context)) {
            $row[] = new tabobject($report, "$CFG->wwwroot/mod/reader/report.php?idh={$idh}&q={$q}&mode={$report}&b={$b}",
                                    get_string($report, 'quiz_'.$report));
            if ($report == $mode) {
                $currenttab = $report;
            }
        }
    }
    $tabs[] = $row;
}

unset ($tabs[1][0]);

print_tabs($tabs, $currenttab, $inactive, $activated);

?>