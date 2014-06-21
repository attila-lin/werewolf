<?

function open_kill(){
  $registry   =& Registry::getInstance();

  $weObj      =& $registry->get('weObj');
  $player     =& $registry->get('player');
  $kill_room  =& $registry->get('kill_room');
  $user       =& $registry->get('user');
  $CONST_STR  =& $registry->get('CONST_STR');

  $player->setRoom($kill_room);
  $player->openRoom($user, 2);
  $weObj->text($CONST_STR['kill_begin'])->reply();
}

function kill_fa_1(){
 $registry =& Registry::getInstance();
  $weObj      =& $registry->get('weObj');
  $player     =& $registry->get('player');
  $kill_room  =& $registry->get('kill_room');
  $user       =& $registry->get('user');
  $CONST_STR  =& $registry->get('CONST_STR');
  $content    =& $registry->get('content');

  $tmp = $player->findPlayer($user);
  $room = $tmp[0]['room'];

  $num = explode(' ', $content);

  $player->setRoom($kill_room);
  $player->getRoom()->setNum($room, $num); //DONE

  $identity = $player->getRoom()->beginGame($room);

  $res = $CONST_STR['kill_res_0'] . strval($room) . $CONST_STR['kill_res_1'];
  $res .= implode('，', explode('|', $identity[0]) ) . $CONST_STR['kill_res_2'];
  $res .= implode('，', explode('|', $identity[1]) ) . $CONST_STR['kill_res_3'];
  $res .= implode('，', explode('|', $identity[2]) ) . $CONST_STR['kill_res_4'];
  $res .= implode('，', explode('|', $identity[3]) ) . $CONST_STR['kill_res_5'];

  $weObj->text($res)->reply();
}

function kill_fa(){

  $registry =& Registry::getInstance();

  $weObj      =& $registry->get('weObj');
  $player     =& $registry->get('player');
  $kill_room  =& $registry->get('kill_room');
  $user       =& $registry->get('user');
  $CONST_STR  =& $registry->get('CONST_STR');
  $content    =& $registry->get('content');

  $player->setRoom($kill_room);

  switch($content) {
    case "关":
      $player->closeRoom($user);
      $weObj->text($CONST_STR['help_info'])->reply();
      break;
    case "换":
     $tmp = $player->findPlayer($user);
    $room = $tmp[0]['room'];
     $identity = $player->getRoom()->beginGame($room);

    $res = $CONST_STR['kill_res_0'] . strval($room) . $CONST_STR['kill_res_1'];
    $res .= implode('，', explode('|', $identity[0]) ) . $CONST_STR['kill_res_2'];
    $res .= implode('，', explode('|', $identity[1]) ) . $CONST_STR['kill_res_3'];
    $res .= implode('，', explode('|', $identity[2]) ) . $CONST_STR['kill_res_4'];
    $res .= implode('，', explode('|', $identity[3]) ) . $CONST_STR['kill_res_5'];

    $weObj->text($res)->reply();
      break;
    case "投":
     $player->openPost($user);
      $weObj->text($CONST_STR['who_post'])->reply();
      break;
    default:
     if(intval($content)){
      if($player->isDead($user, $content)){
       $weObj->text("已经死啦。输入“投”开放投票，输入“换”进行下一轮，输入“关”关闭房间。")->reply();
      }
      else{
       $player->setDead($user, $content);
       $weObj->text("继续输入死亡者或者输入“投”开放投票，输入“换”进行下一轮，输入“关”关闭房间。")->reply();
      }
     }
     else {
      $weObj->text("好吧，我看不懂啦。")->reply();
     }
      break;
  }
}

function kill_post_res(){
  $registry =& Registry::getInstance();
  $weObj      =& $registry->get('weObj');
  $player     =& $registry->get('player');
  $kill_room   =& $registry->get('kill_room');
  $user       =& $registry->get('user');
  $CONST_STR  =& $registry->get('CONST_STR');
  $content    =& $registry->get('content');

  $player->setRoom($kill_room);

  switch($content) {
    case "查":
      $player->closePost($user);
      $postres = $player->getPostRes($user);

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
     $player->text("输个“查”会死啊")->reply();
      break;
  }
}


?>