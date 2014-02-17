<?
function open_big(){
	$registry =& Registry::getInstance();
    $weObj    =& $registry->get('weObj');


    // $weObj->text("heh")->reply();

	$file_handle = fopen(WE_ROOT . "big.txt", "r");
	$lines = array();
	while (!feof($file_handle)) {
	   $line = fgets($file_handle);
	   array_push($lines, $line);
	}
	fclose($file_handle);

	$random = rand(0, count($lines) - 1);

	$weObj->text($lines[$random])->reply();

}

?>