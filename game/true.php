<?
function open_true(){
  $registry =& Registry::getInstance();
  $weObj    =& $registry->get('weObj');

  $file_handle = fopen(WE_ROOT . "true.txt", "r");
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