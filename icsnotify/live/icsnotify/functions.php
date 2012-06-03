<?php

require_once "db.php";

function isListed($email){
	$db = new DbObj();
	$ex_emails = $db->get_data('tbl_email', "address='{$email}'");
	if(count($ex_emails)==0)
		return false;
	else
		return true;
}

function addNotifRecord($si, $email){
	$db = new DbObj();
	$si_id = 0;
	$ex_si = $db->get_data('tbl_si', "number='{$si}'");
	if(count($ex_si)>0){
		$si_id = (int)$ex_si[0]['id'];
	}else{
		$db->insert_data('tbl_si', array('number' => $si));
		$ex_si = $db->get_data('tbl_si', "number='{$si}'");
		$si_id = (int)$ex_si[0]['id'];
	}
	$db->insert_data('tbl_email', array('si_id'=>$si_id, 'address'=>$email));
}