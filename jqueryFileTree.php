<?php
//
// jQuery File Tree PHP Connector
//
// Version 1.01
//
// Cory S.N. LaViska
// A Beautiful Site (http://abeautifulsite.net/)
// 24 March 2008
//
// History:
//
// 1.01 - updated to work with foreign characters in directory/file names (12 April 2008)
// 1.00 - released (24 March 2008)
//
// Output a list of files for jQuery File Tree
//
require("auth.php");

function sortDate( $a, $b ) {
	preg_match("/\d{2}\.\d{2}/", $a,$m_a);
	preg_match("/\d{2}\.\d{2}/", $b,$m_b);
    return strtotime($m_b[0].".".date('Y')) - strtotime($m_a[0].".".date('Y'));
}

$_POST['dir'] = urldecode($_POST['dir']);
$root = getcwd()."/gpx/";

if( file_exists($root . $_POST['dir']) ) {
	$files = scandir($root . $_POST['dir']);
	natcasesort($files);
	if( count($files) > 2 ) { /* The 2 accounts for . and .. */
		echo "<ul class=\"jqueryFileTree\" style=\"display: none;\">";
		// All dirs
		foreach( $files as $file ) {
			if( file_exists($root . $_POST['dir'] . $file) && $file != '.' && $file != '..' && is_dir($root . $_POST['dir'] . $file) ) {
				echo "<li class=\"directory collapsed\"><a href=\"#\" rel=\"" . htmlentities($_POST['dir'] . $file,ENT_QUOTES,"UTF-8") . "/\">" . htmlentities($file,ENT_QUOTES,"UTF-8") . " (".iterator_count(new FilesystemIterator($root . $_POST['dir'] . $file, FilesystemIterator::SKIP_DOTS)).")</a></li>";
			}
		}
		// All files
		usort($files, "sortDate");
		foreach( $files as $file ) {
			if( file_exists($root . $_POST['dir'] . $file) && $file != '.' && $file != '..' && !is_dir($root . $_POST['dir'] . $file) && preg_match("/.+\.(gpx|kml)$/", $file) ) {
				$ext = preg_replace('/^.*\./', '', $file);
				echo "<li class=\"file ext_$ext\"><img src=\"images/unchecked.svg\" class=\"save\" id=\"".$_POST['dir'].$file."\" /><a href=\"#\" rel=\"" . htmlentities($_POST['dir'] . $file,ENT_QUOTES,"UTF-8") . "\" class=\"files\">" . htmlentities($file,ENT_QUOTES,"UTF-8")."</a></li>";
			}
		}
		echo "</ul>";	
	}
}

?>