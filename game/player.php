<?
function goto_room(){
  $registry   =& Registry::getInstance();

  $weObj      =& $registry->get('weObj');
  $player     =& $registry->get('player');

  $who_room   =& $registry->get('who_room');
  $kill_room  =& $registry->get('kill_room');
  $wolf_room  =& $registry->get('wolf_room');

  $user       =& $registry->get('user');
  $CONST_STR  =& $registry->get('CONST_STR');
  $content    =& $registry->get('content');

  $room = intval($content);
  switch (intval($room/1000)) {
    case 1:
      $player->setRoom($who_room);

      if($player->addPlayer($user, $room)){
        // "欢迎加入房间号1001,你的编号是2,输入“领”领取身份";
        $weObj->text($CONST_STR['goto'][0] . $content . $CONST_STR['goto'][1] . strval($player->getNo($user)) . $CONST_STR['goto'][2] )->reply();
      }
      else{
        $weObj->text($CONST_STR['goto_error'])->reply();
      }
      break;
    case 2:
      $player->setRoom($kill_room);

      if($player->addPlayer($user, $room)){
        // "欢迎加入房间号2001,你的编号是2,输入“领”领取身份";
        $weObj->text($CONST_STR['goto'][0] . $content . $CONST_STR['goto'][1] . strval($player->getNo($user)) . $CONST_STR['goto'][2] )->reply();
      }
      else{
        $weObj->text($CONST_STR['goto_error'])->reply();
      }
      break;
    case 3:
      $player->setRoom($wolf_room);

      if($player->addPlayer($user, $room)){
        // "欢迎加入房间号3001,你的编号是2,输入“领”领取身份";
        $weObj->text($CONST_STR['goto'][0] . $content . $CONST_STR['goto'][1] . strval($player->getNo($user)) . $CONST_STR['goto'][2] )->reply();
      }
      else{
        $weObj->text($CONST_STR['goto_error'])->reply();
      }
      break;
    default:
      break;
  }
}


function who_player(){
  $registry =& Registry::getInstance();
  $weObj      =& $registry->get('weObj');
  $player     =& $registry->get('player');
  $who_room   =& $registry->get('who_room');
  $user       =& $registry->get('user');
  $CONST_STR  =& $registry->get('CONST_STR');
  $content    =& $registry->get('content');

  $player->setRoom($who_room);

  $tmp = $player->findPlayer($user);
  $room = $tmp[0]['room'];
  $no = $tmp[0]['no'];

  switch ($content) {
    case '领':
      $word = "你的编号是" . strval($player->getNo($user)) . "，词语是" . $player->getWord($user) . "。";
      $weObj->text($word)->reply();
      break;
    case '退':
      $word = $player->exitRoom($user);
      $weObj->text("你已经退出房间啦。")->reply();
      break;
    default:
      if(intval($content)){
        if($player->getPost($user)){
          // $weObj->text("可以投")->reply();
          if(!$player->hasPosted($user)){
            // $weObj->text("可以投")->reply();
            $player->setPost($user, intval($content));
            $weObj->text("已投。法官如果说开始投票，那么请在法官查询结果前输入想投人的数字，过时不候。输入“领”领取身份。")->reply();
          }
          else{
            $weObj->text("这一轮你已经投过了。")->reply();
          }
        }
        else{
          $weObj->text("不是投票时间")->reply();
        }
      }
      else{
        $weObj->text("好吧，我看不懂啦。")->reply();
      }
      break;
  }
}


function kill_player(){
  $registry =& Registry::getInstance();
  $weObj      =& $registry->get('weObj');
  $player     =& $registry->get('player');
  $kill_room  =& $registry->get('kill_room');
  $user       =& $registry->get('user');
  $CONST_STR  =& $registry->get('CONST_STR');
  $content    =& $registry->get('content');

  $player->setRoom($kill_room);

  $tmp = $player->findPlayer($user);
  $room = $tmp[0]['room'];
  $no = $tmp[0]['no'];

  switch ($content) {
    case '领':
      $wordnum = $player->getWord($user);
      $word = "你的编号是" . strval($player->getNo($user)) . "，身份是";
      switch ($wordnum) {
        case 1:
          $word .= "杀手";
          break;
        case 2:
          $word .= "警察";
          break;
        case 3:
          $word .= "平民";
          break;
        case 4:
          $word .= "医生";
          break;
        default:
          break;
      }
      $word .= "。";
      $weObj->text($word)->reply();
      break;
    case '退':
      $word = $player->exitRoom($user);
      $weObj->text("你已经退出房间啦。")->reply();
      break;
    default:
      if(intval($content)){
        if($player->getPost($user)){
          // $weObj->text("可以投")->reply();
          if(!$player->hasPosted($user)){
            // $weObj->text("可以投")->reply();
            $player->setPost($user, intval($content));
            $weObj->text("已投。法官如果说开始投票，那么请在法官查询结果前输入想投人的数字，过时不候。输入“领”领取身份。")->reply();
          }
          else{
            $weObj->text("这一轮你已经投过了。")->reply();
          }
        }
        else{
          $weObj->text("不是投票时间")->reply();
        }
      }
      else{
        $weObj->text("好吧，我看不懂啦。")->reply();
      }
      break;
  }
}


function wolf_player(){
  $registry =& Registry::getInstance();
  $weObj      =& $registry->get('weObj');
  $player     =& $registry->get('player');
  $wolf_room   =& $registry->get('wolf_room');
  $user       =& $registry->get('user');
  $CONST_STR  =& $registry->get('CONST_STR');
  $content    =& $registry->get('content');

  $player->setRoom($wolf_room);

  $tmp = $player->findPlayer($user);
  $room = $tmp[0]['room'];
  $no = $tmp[0]['no'];

  switch ($content) {
    case '领':
      $wordnum = $player->getWord($user);
      $word = "你的编号是" . strval($player->getNo($user)) . "，身份是";
      //  狼人     平民       女巫     预言家      丘比特     医生
      //  守卫     长老      猎人      小女孩  白痴  吹笛者
      switch ($wordnum) {
        case 1:
          $word .= "狼人";
          break;
        case 2:
          $word .= "平民";
          break;
        case 3:
          $word .= "女巫";
          break;
        case 4:
          $word .= "预言家";
          break;
        case 5:
          $word .= "丘比特";
          break;
        case 6:
          $word .= "医生";
          break;
        case 7:
          $word .= "守卫";
          break;
        case 8:
          $word .= "长老";
          break;
        case 9:
          $word .= "猎人";
          break;
        case 10:
          $word .= "小女孩";
          break;
        case 11:
          $word .= "白痴";
          break;
        case 12:
          $word .= "吹笛者";
          break;
        default:
          break;
      }
      $word .= "。";
      $weObj->text($word)->reply();
      break;
    case '退':
      $word = $player->exitRoom($user);
      $weObj->text("你已经退出房间啦。")->reply();
      break;
    default:
      if(intval($content)){
        if($player->getPost($user)){
          // $weObj->text("可以投")->reply();
          if(!$player->hasPosted($user)){
            // $weObj->text("可以投")->reply();
            $player->setPost($user, intval($content));
            $weObj->text("已投。法官如果说开始投票，那么请在法官查询结果前输入想投人的数字，过时不候。输入“领”领取身份。")->reply();
          }
          else{
            $weObj->text("这一轮你已经投过了。")->reply();
          }
        }
        else{
          $weObj->text("不是投票时间")->reply();
        }
      }
      else{
        $weObj->text("好吧，我看不懂啦。")->reply();
      }
      break;
  }
}

?>