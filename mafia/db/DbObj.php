<?php
require_once('dbconfig.php');

class DbObj{
	var $tablename;         // table name
	var $rows_per_page;     // used in pagination
	var $pageno;            // current page number
	var $lastpage;          // highest page number
	var $fieldlist;         // list of fields in this table
	var $data_array;        // data from the database
	var $errors;            // array of error messages

	function __construct(){
		$this->errors = FALSE;
	}
	function db_connect()
	{
	   $dbconnect = mysql_connect(DB_SERVER, DB_USERNAME, DB_PASSWD);
	   if (!$dbconnect) {
		  return 0;
	   } elseif (!mysql_select_db(DB_NAME)) {
		  return 0;
	   } else {
		  return $dbconnect;
	   } // if

	} // db_connect

	function db_close($dbconnect){
		$closed=FALSE;
		if($dbconnect)
			$closed = mysql_close($dbconnect);
		else
			return FALSE;
		return $closed;
	}

	function get_data($table, $where){
		global $dbconnect;
		$this->data_array = array();

		if(!empty($table))
			$this->tablename = $table;
		else
			return FALSE;

		if(!empty($where))
			$where_str = "WHERE {$where}";
		else
			$where_str = NULL;

		$dbconnect = DbObj::db_connect();
		$query = "SELECT * FROM {$table} {$where_str}";
		$result = mysql_query($query, $dbconnect) or trigger_error("SQL", E_USER_ERROR);

		while ($row = mysql_fetch_assoc($result)) {
			$this->data_array[] = $row;
		} // while

		mysql_free_result($result);
		DbObj::db_close($dbconnect);

		return $this->data_array;
	}

	function get_contestant_data($range='all', $user_id=NULL){
		global $dbconnect;
		$this->data_array = array();

		switch($range){
			case 'all':
				$dbconnect = DbObj::db_connect();
				$query = "SELECT tblPossibleParticipantList.`user_id` ,  `name` ,  `screen_name` ,  `profile_image_url` ,  `no_of_tweets` ,  `follower` ,  `description` ,  `url` ,  `vote_count`
				FROM  `tblPossibleParticipantList`
				LEFT JOIN  `tblContestantList` ON tblPossibleParticipantList.user_id = tblContestantList.user_id";
				$result = mysql_query($query, $dbconnect) or trigger_error("SQL", E_USER_ERROR);

				while ($row = mysql_fetch_assoc($result)) {
					$this->data_array[] = $row;
				} // while

				mysql_free_result($result);
				DbObj::db_close($dbconnect);
			break;
			case 'single':
				$dbconnect = DbObj::db_connect();
				$query = "SELECT ppl.`user_id` , ppl.`name` , ppl.`screen_name` , ppl.`profile_image_url` , ppl.`no_of_tweets` , ppl.`follower` , ppl.`description` , ppl.`url` , cl.`vote_count`
				FROM  `tblPossibleParticipantList` ppl,  `tblContestantList` cl
				WHERE ppl.user_id = cl.user_id AND ppl.user_id='{$user_id}'";
				$result = mysql_query($query, $dbconnect) or trigger_error("SQL", E_USER_ERROR);

				while ($row = mysql_fetch_assoc($result)) {
					$this->data_array[] = $row;
				} // while

				mysql_free_result($result);
				DbObj::db_close($dbconnect);
			break;
		}
		return $this->data_array;
	}

	function insert_data($table, $data){

		if(!empty($table))
			$this->tablename = $table;
		else
			return FALSE;

		if(empty($data) || !is_array($data))
			return FALSE;

		$dbconnect = DbObj::db_connect();
		$query = "INSERT INTO {$this->tablename} SET ";
		foreach ($data as $item => $value) {
			$value=mysql_real_escape_string($value);
			$query .= "{$item}='{$value}', ";
		} // foreach
		$query = rtrim($query, ', ');

		$result = @mysql_query($query, $dbconnect);
                $result_id = mysql_insert_id();
		if (mysql_errno() <> 0) {
			trigger_error("SQL", E_USER_ERROR);
		} // if
		DbObj::db_close($dbconnect);
		return $result_id;
	}
	function execute_query($query){

		$dbconnect = DbObj::db_connect();

		$result = @mysql_query($query, $dbconnect);
		if (mysql_errno() <> 0) {
			trigger_error("SQL", E_USER_ERROR);
		} // if
		DbObj::db_close($dbconnect);
		return;
	}

	function update_data($table, $data, $where){

		if(!empty($table))
			$this->tablename = $table;
		else
			return FALSE;

		if(empty($data) || !is_array($data))
			return FALSE;

		if(!empty($where))
			$where_str = "WHERE {$where}";
		else
			$where_str = NULL;

		$dbconnect = DbObj::db_connect();
		$query = "UPDATE {$this->tablename} SET ";
		foreach ($data as $item => $value) {
			$value=mysql_real_escape_string($value);
			$query .= "{$item}='{$value}', ";
		} // foreach
		$query = rtrim($query, ', ');

		$query = $query . " " . $where_str;
                
		$result = @mysql_query($query, $dbconnect);
		if (mysql_errno() <> 0) {
			trigger_error("SQL", E_USER_ERROR);
		} // if
		DbObj::db_close($dbconnect);
		return;
	}
}

?>
