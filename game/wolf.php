<?
function open_wolf(){
  $registry   =& Registry::getInstance();

  $weObj      =& $registry->get('weObj');
  $player     =& $registry->get('player');
  $wolf_room  =& $registry->get('wolf_room');
  $user       =& $registry->get('user');
  $CONST_STR  =& $registry->get('CONST_STR');

  $player->setRoom($wolf_room);
  $player->openRoom($user, 3);
  $weObj->text($CONST_STR['wolf_begin'])->reply();
}

function wolf_fa_1(){
  $registry   =& Registry::getInstance();

  $weObj      =& $registry->get('weObj');
  $player     =& $registry->get('player');
  $wolf_room  =& $registry->get('wolf_room');
  $user       =& $registry->get('user');
  $CONST_STR  =& $registry->get('CONST_STR');
  $content    =& $registry->get('content');

  $tmp = $player->findPlayer($user);
  $room = $tmp[0]['room'];

  $num = explode(' ', $content);

  $player->setRoom($wolf_room);
  $player->getRoom()->setNum($room, $num); //DONE

  $identity = $player->getRoom()->beginGame($room);

  $res = $CONST_STR['wolf_res'][0] . strval($room) . $CONST_STR['wolf_res'][1];
  $res .= implode('，', explode('|', $identity[0]) ) . $CONST_STR['wolf_res'][2];
  $res .= implode('，', explode('|', $identity[1]) ) . $CONST_STR['wolf_res'][3];
  $res .= implode('，', explode('|', $identity[2]) ) . $CONST_STR['wolf_res'][4];
  $res .= implode('，', explode('|', $identity[3]) ) . $CONST_STR['wolf_res'][5];
  $res .= implode('，', explode('|', $identity[4]) ) . $CONST_STR['wolf_res'][6];
  $res .= implode('，', explode('|', $identity[5]) ) . $CONST_STR['wolf_res'][7];
  $res .= implode('，', explode('|', $identity[6]) ) . $CONST_STR['wolf_res'][8];
  $res .= implode('，', explode('|', $identity[7]) ) . $CONST_STR['wolf_res'][9];
  $res .= implode('，', explode('|', $identity[8]) ) . $CONST_STR['wolf_res'][10];
  $res .= implode('，', explode('|', $identity[9]) ) . $CONST_STR['wolf_res'][11];
  $res .= implode('，', explode('|', $identity[10]) ) . $CONST_STR['wolf_res'][12];
  $res .= implode('，', explode('|', $identity[11]) ) . $CONST_STR['wolf_res'][13];

  $weObj->text($res)->reply();
}

function wolf_fa_2(){
 $registry  =& Registry::getInstance();
  $weObj      =& $registry->get('weObj');
  $player     =& $registry->get('player');
  $wolf_room  =& $registry->get('wolf_room');
  $user       =& $registry->get('user');
  $CONST_STR  =& $registry->get('CONST_STR');
  $content    =& $registry->get('content');

  $player->setRoom($wolf_room);

  $c = explode(' ', $content);

  if(isset($c[0]) && isset($c[1]) && intval($c[0]) && intval($c[1])){
   $player->setCp($user, intval($c[0]), intval($c[1]));
   $weObj->text("输入今夜死掉人的编号开始投票流程（一个一个输入，建议人草鸡多以至于智商不足以计票或者觉得跟票神马的神烦时使用），输入“投”开放投票，输入“换”进行下一轮，输入“关”关闭房间。")->reply();
  }
  else{
   $weObj->text("输cp啊啊啊啊啊。")->reply();
  }
}

function wolf_fa(){
  $registry   =& Registry::getInstance();
  $weObj      =& $registry->get('weObj');
  $player     =& $registry->get('player');
  $wolf_room  =& $registry->get('wolf_room');
  $user       =& $registry->get('user');
  $CONST_STR  =& $registry->get('CONST_STR');
  $content    =& $registry->get('content');

  $player->setRoom($wolf_room);

  switch($content) {
    case "关":
      $player->closeRoom($user);
      $weObj->text($CONST_STR['help_info'])->reply();
      break;
    case "换":
     $tmp = $player->findPlayer($user);
    $room = $tmp[0]['room'];
     $identity = $player->getRoom()->beginGame($room);

    $res = $CONST_STR['wolf_res'][0] . strval($room) . $CONST_STR['wolf_res'][1];
    $res .= implode('，', explode('|', $identity[0]) ) . $CONST_STR['wolf_res'][2];
    $res .= implode('，', explode('|', $identity[1]) ) . $CONST_STR['wolf_res'][3];
    $res .= implode('，', explode('|', $identity[2]) ) . $CONST_STR['wolf_res'][4];
    $res .= implode('，', explode('|', $identity[3]) ) . $CONST_STR['wolf_res'][5];
    $res .= implode('，', explode('|', $identity[4]) ) . $CONST_STR['wolf_res'][6];
    $res .= implode('，', explode('|', $identity[5]) ) . $CONST_STR['wolf_res'][7];
    $res .= implode('，', explode('|', $identity[6]) ) . $CONST_STR['wolf_res'][8];
    $res .= implode('，', explode('|', $identity[7]) ) . $CONST_STR['wolf_res'][9];
    $res .= implode('，', explode('|', $identity[8]) ) . $CONST_STR['wolf_res'][10];
    $res .= implode('，', explode('|', $identity[9]) ) . $CONST_STR['wolf_res'][11];
    $res .= implode('，', explode('|', $identity[10]) ) . $CONST_STR['wolf_res'][12];
    $res .= implode('，', explode('|', $identity[11]) ) . $CONST_STR['wolf_res'][13];

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


function wolf_post_res(){
  $registry   =& Registry::getInstance();

  $weObj      =& $registry->get('weObj');
  $player     =& $registry->get('player');
  $wolf_room  =& $registry->get('wolf_room');
  $user       =& $registry->get('user');
  $CONST_STR  =& $registry->get('CONST_STR');
  $content    =& $registry->get('content');

  $player->setRoom($wolf_room);

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
        else {
          $res .= "所以需要重新投票。";
        }

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