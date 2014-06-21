<?

function open_who(){
  $registry   =& Registry::getInstance();

  $weObj      =& $registry->get('weObj');
  $player     =& $registry->get('player');
  $who_room   =& $registry->get('who_room');
  $user       =& $registry->get('user');
  $CONST_STR  =& $registry->get('CONST_STR');

  $player->setRoom($who_room);
  $player->openRoom($user, 1);
  $weObj->text($CONST_STR['who_begin'])->reply();
}


function who_fa_1(){

  $registry   =& Registry::getInstance();
  $weObj      =& $registry->get('weObj');
  $player     =& $registry->get('player');
  $who_room   =& $registry->get('who_room');
  $user       =& $registry->get('user');
  $CONST_STR  =& $registry->get('CONST_STR');
  $content    =& $registry->get('content');

  $total = intval($content);
  $tmp = $player->findPlayer($user);
  $room = $tmp[0]['room'];

  // $weObj->text($room)->reply();

  $player->setRoom($who_room);
  $player->getRoom()->setTotal($room, $total);

  $weObj->text($CONST_STR['set_word1'])->reply();
}


function who_fa_2(){

  $registry =& Registry::getInstance();
  $weObj      =& $registry->get('weObj');
  $player     =& $registry->get('player');
  $who_room   =& $registry->get('who_room');
  $user       =& $registry->get('user');
  $CONST_STR  =& $registry->get('CONST_STR');
  $content    =& $registry->get('content');

  $tmp = $player->findPlayer($user);
  $room = $tmp[0]['room'];

  $player->setRoom($who_room);
  $player->getRoom()->setWord1($room, $content);

  $weObj->text($CONST_STR['set_word2'])->reply();
}

function who_fa_3(){

  $registry =& Registry::getInstance();
  $weObj      =& $registry->get('weObj');
  $player     =& $registry->get('player');
  $who_room   =& $registry->get('who_room');
  $user       =& $registry->get('user');
  $CONST_STR  =& $registry->get('CONST_STR');
  $content    =& $registry->get('content');

  $tmp = $player->findPlayer($user);
  $room = $tmp[0]['room'];

  $player->setRoom($who_room);
  $player->getRoom()->setWord2($room, $content);

  $wodi = $player->getRoom()->beginGame($room);

  $res = $CONST_STR['who_res_1'] . strval($room) . ' ' . $CONST_STR['who_res_2'];
  $res .= implode('，',$wodi);
  $res .= $CONST_STR['who_res_3'];

  $weObj->text($res)->reply();
}

function who_fa(){

  $registry =& Registry::getInstance();
  $weObj      =& $registry->get('weObj');
  $player     =& $registry->get('player');
  $who_room   =& $registry->get('who_room');
  $user       =& $registry->get('user');
  $CONST_STR  =& $registry->get('CONST_STR');
  $content    =& $registry->get('content');

  $player->setRoom($who_room);

  switch($content) {
    case "关":
      $player->closeRoom($user);
      $weObj->text($CONST_STR['help_info'])->reply();
      break;
    case "投":
      $player->openPost($user);
      $weObj->text("输入“查”查看投票结果")->reply();
      break;
    case "换":
      $player->unsetWord($user);
      $weObj->text($CONST_STR['set_word1'])->reply();
      break;
    default:
      break;
  }
}

function who_post_res(){
  $registry =& Registry::getInstance();
  $weObj      =& $registry->get('weObj');
  $player     =& $registry->get('player');
  $who_room   =& $registry->get('who_room');
  $user       =& $registry->get('user');
  $CONST_STR  =& $registry->get('CONST_STR');
  $content    =& $registry->get('content');

  $player->setRoom($who_room);

  switch($content) {
    case "查":
      if($postres){
        $player->clearPostNum($user);

        arsort($postres, SORT_NUMERIC);

        $res = "投票结果如下：";
        foreach ($postres as $key => $value) {
          $res .= strval($key) . "号" . strval($value) . "票,";
        }
        $res = substr($res,0,strlen($res)-1) . "。";

        if( !isset($postres[1]) || $postres[0] != $postres[1] ){
          $keys = array_keys($postres);
          $res .= "所以" . strval( $keys[0] ) . "号shi了。";
        }

        else
          $res .= "所以需要重新投票。";
        $weObj->text($res)->reply();
      }
      else{

        $weObj->text("没人投啊！")->reply();
      }
      break;
    default:
      break;
  }
}

?>