<?php

define('CLI_SCRIPT', true);

// =======================================================================
// Error reporting
// =======================================================================
error_reporting(E_ALL);
ini_set('display_errors', 'On');

// =======================================================================
// Constants
// =======================================================================
define('KB', 1024);
define('MB', 1048576);

// =======================================================================
require(dirname(dirname(dirname(dirname(__FILE__)))) . '/config.php');

// =======================================================================
global $CFG;
require_once($CFG -> libdir  . '/dmllib.php');
require_once($CFG -> dirroot . '/lib/filelib.php');
require_once($CFG -> dirroot . '/lib/datalib.php');

// =======================================================================
// =======================================================================
$dst_dir = $CFG -> backup_auto_destination;

// =======================================================================
// =======================================================================
global $DB;

$sql = 'select file.id, file.contextid, file.itemid, filepath, filename, filesize,
  from_unixtime(file.timemodified) as mtime, cse.id as course_id, cse.fullname
from {files} as file
left join {context} ctx on (ctx.id = file.contextid and ctx.contextlevel = 50)
left join {course}  cse on (ctx.instanceid = cse.id)
where component = \'backup\' and filearea = \'automated\' and filename like \'%.mbz\' order by filesize desc;';

$sth = $DB -> get_records_sql($sql);

$fs = get_file_storage();

foreach ($sth as $id => $row) {
	printf("| %6d | % 7.2f | %s | %s \n", $row -> id, $row -> filesize / MB, $row -> mtime, $row -> fullname);
	$file = $fs -> get_file($row -> contextid, 'backup', 'automated', $row -> itemid, $row -> filepath, $row -> filename);
	if ($file) { // file found
/*

		// Copy file
		if ( $file -> copy_content_to($dst_dir  . '/' . $file -> get_filename()) ) {
			echo "File sent to $base_dir: " . $file -> get_filename . "\n";
		} else {
			echo 'Could not copy file:    ' . $file -> get_filename . "\n";
		}

		// Delete file
		if ( $file -> delete() ) {
			echo 'File sent to trash:    ' . $file -> get_filename . "\n";
		} else {
			echo 'Could not delete file: ' . $file -> get_filename . "\n";
		}


*/
	}

}

print $CFG -> fileslastcleanup . "\n";





// =======================================================================
?>

