<?
// $file_handle = fopen("../big.txt", "r");
// while (!feof($file_handle)) {
//    $line = fgets($file_handle);
//    echo $line;
// }
// fclose($file_handle);
// 
// 

	$file_handle = fopen("../big.txt", "r");
	$lines = array();
	while (!feof($file_handle)) {
	   $line = fgets($file_handle);
	   array_push($lines, $line);
	}
	fclose($file_handle);

	$random = rand(0, count($lines) - 1);
  	echo ($lines[$random]) ;

?>