<?php

require_once($CFG->libdir.'/formslib.php');

class reader_uploadbooks_form extends moodleform {
    function definition() {
        global $USER, $CFG;
        $mform    =& $this->_form;
    }
}

?>