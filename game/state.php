<?

function get_state(){

  $registry =& Registry::getInstance();
  $weObj =& $registry->get('weObj');
  $user =& $registry->get('user');

  $player =& $registry->get('player');
  $STATE =& $registry->get('STATE');

  $who_room = & $registry->get('who_room');
  $kill_room = & $registry->get('kill_room');
  $wolf_room = & $registry->get('wolf_room');

  $tmp = $player->findPlayer($user);

  if($tmp){
    $room = $tmp[0]['room'];
    $no   = $tmp[0]['no'];

    $game = intval($room / 1000);
    // $weObj->text(strval($no))->reply();
    switch ($game) {
      // who
      case 1:
        //---------------------------
        $player->setRoom($who_room);

        $tmp = $player->getRoom()->findRoom($room);

        $total = $tmp[0]['total'];
        $word1 = $tmp[0]['word1'];
        $word2 = $tmp[0]['word2'];
        $post  = $tmp[0]['post'];
        //---------------------------
        if($no == 1){
          if($total == 1)
            return $STATE['ST_WHO_FA_1'];
          if($word1 == '')
            return $STATE['ST_WHO_FA_2'];
          if($word2 == '')
            return $STATE['ST_WHO_FA_3'];
          // all finished
          if($post == 1)
          return $STATE['ST_WHO_POST'];

          return $STATE['ST_WHO_FA'];
        }
        else{
          return $STATE['ST_WHO_PLAYER'];
        }
        break;

      // kill
      case 2:
        //---------------------------
        $player->setRoom($kill_room);
        $tmp = $player->getRoom()->findRoom($room);

        $total = $tmp[0]['total'];
        $post  = $tmp[0]['post'];
        //---------------------------
        // $weObj->text("heh")->reply();

        if($no == 1){
          // $weObj->text("hehe")->reply();
          if($total == 1)
            return $STATE['ST_KILL_FA_1'];
          if($post == 1)
            return $STATE['ST_KILL_POST'];
          return $STATE['ST_KILL_FA'];
        }
        else
          return $STATE['ST_KILL_PLAYER'];
        break;

      // wolf
      case 3:
        //---------------------------
        $player->setRoom($wolf_room);
        $tmp = $player->getRoom()->findRoom($room);

        $total = $tmp[0]['total'];
        $cp = $tmp[0]['cp'];
        $post  = $tmp[0]['post'];
        //---------------------------
        //
        if($no == 1){
          // $weObj->text("hehe")->reply();
          if($total == 1)
            return $STATE['ST_WOLF_FA_1'];
          if($cp == '')
            return $STATE['ST_WOLF_FA_2'];
          if($post == 1)
            return $STATE['ST_WOLF_POST'];
          return $STATE['ST_WOLF_FA'];
        }
        else{
          return $STATE['ST_WOLF_PLAYER'];
        }
        break;
      default:
        break;
    }
  }
  else{
    return $STATE['ST_NEW'];
  }
}


?>