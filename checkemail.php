<?php

include_once "../../config.php";

$user1 = $DB->get_record("user", array("id" => 4));

email_to_user($user1,get_admin(),"Cheated notice",$reader->cheated_message);

echo 'done';

//print_r (get_admin());

mail($user1->email, "My Subject", "Line 1\nLine 2\nLine 3");