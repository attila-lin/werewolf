<?php

if (!ini_get('display_errors')) {
    ini_set('display_errors', 1);
}

@define("WE_ROOT",      dirname(__FILE__) . "/");           
@define("WE_CLASS_DIR",    WE_ROOT . "class");  
@define("WE_GAME_DIR",     WE_ROOT . "game");   

require_once(WE_ROOT . "wechat.class.php");
require_once(WE_ROOT . "const.php");
require_once(WE_ROOT . "function.php");
require_once(WE_ROOT . "Registry.php");

require_once(WE_CLASS_DIR . "/class.who.php");
require_once(WE_CLASS_DIR . "/class.wolf.php");
require_once(WE_CLASS_DIR . "/class.kill.php");
require_once(WE_CLASS_DIR . "/class.player.php");


require_once(WE_GAME_DIR . "/state.php");
require_once(WE_GAME_DIR . "/who.php");
require_once(WE_GAME_DIR . "/kill.php");
require_once(WE_GAME_DIR . "/wolf.php");
require_once(WE_GAME_DIR . "/big.php");
require_once(WE_GAME_DIR . "/true.php");
require_once(WE_GAME_DIR . "/player.php");


$options = array(
    'token'=>'lalala', //填写你设定的key
    'appid'=>'wx1efe5966178f5a9f', //填写高级调用功能的app id
    'appsecret'=>'fb152772bdd80a86e2a4117d482ed3d1', //填写高级调用功能的密钥
);
$weObj = new Wechat($options);

// $echoStr = $weObj->valid($return=true);
// echo $echoStr;

$type = $weObj->getRev()->getRevType();
$user = $weObj->getRev()->getRevFrom();
$content = $weObj->getRev()->getRevContent();

$content = safe_replace($content);

$mysql = new SaeMysql();

$who_room = new who($mysql);
$kill_room = new kill($mysql);
$wolf_room = new wolf($mysql);

$player = new player($mysql, $who_room);

//------------- 注册----------------
$registry =& Registry::getInstance();
$registry->set ('weObj', $weObj);

$registry->set ('type', $type);
$registry->set ('user', $user);
$registry->set ('content', $content);

$registry->set ('mysql', $mysql);

$registry->set ('who_room', $who_room);
$registry->set ('kill_room', $kill_room);
$registry->set ('wolf_room', $wolf_room);

$registry->set ('player', $player);

// regist const
$registry->set ('CONST_STR', $CONST_STR);

// regist state
$registry->set ('STATE', $STATE);

//-----------------------------------

function safe_replace($string) {
    $string = str_replace('%20','',$string);
    $string = str_replace('%27','',$string);
    $string = str_replace('%2527','',$string);
    $string = str_replace('*','',$string);
    $string = str_replace('"','&quot;',$string);
    $string = str_replace("'",'',$string);
    $string = str_replace('"','',$string);
    $string = str_replace(';','',$string);
    $string = str_replace('<','&lt;',$string);
    $string = str_replace('>','&gt;',$string);
    $string = str_replace("{",'',$string);
    $string = str_replace('}','',$string);
    $string = str_replace('','',$string);
    return $string;
}

function init() {
    $registry =& Registry::getInstance();
    $weObj =& $registry->get('weObj');
    $CONST_STR =& $registry->get('CONST_STR');
    $STATE =& $registry->get('STATE');
    $content =& $registry->get('content');

    // $weObj->text($content)->reply();
    // exit();

    if($content == '开'){
        $weObj->text($CONST_STR['chose_game'])->reply();
    }
    elseif ($content == '1') {
        // $weObj->text("I'm 1")->reply();
        open_who();
    }
    elseif ($content == '2') {
        // $weObj->text("I'm 2")->reply();
        // exit();
        open_kill();
    }
    elseif ($content == '3') {
        // $weObj->text("I'm 3")->reply();
        // exit();
        open_wolf();
    }
    elseif ($content == '真') {
        // $weObj->text("I'm 真")->reply();
        // exit();
        open_true();
    }
    elseif ($content == '冒') {
        // $weObj->text("I'm 冒")->reply();
        // exit();
        open_big();
    }
    else {
        // $weObj->text("I'm else")->reply();
        // exit();
        if(intval($content)){
            goto_room();
        }
        else {
            $weObj->text("好吧，我看不懂啦。")->reply();
        }
    }
    // switch($content) {
    //     case "开":
    //         $weObj->text($CONST_STR['chose_game'])->reply();
    //         break;
    //     case "1":
    //         open_who();
    //         break;
    //     case "2":
    //         open_kill();
    //         break;
    //     case "3":
    //         open_wolf();
    //         break;
    //     case "4":
    //         $weObj->text("4")->reply();
    //         true();
    //         break;
    //     case "5":
    //         $weObj->text("5")->reply();
    //         big();
    //         break;
    //     default:
    //         if(intval($content)){
    //             goto_room();
    //         }
    //         else {
    //             $weObj->text("好吧，我看不懂啦。")->reply();
    //         }
    // }
}

switch($type) {
    case Wechat::MSGTYPE_TEXT:
        switch ( get_state() ) {
            // no date
            case $STATE['ST_NEW']:
                // $weObj->text($STATE['ST_NEW'])->reply();
                init();
                break;

            // WHO FA
            case $STATE['ST_WHO_FA_1']:
                // $weObj->text("ST_WHO_FA_1")->reply();
                who_fa_1();
                break;
            case $STATE['ST_WHO_FA_2']:
                // $weObj->text("ST_WHO_FA_2")->reply();
                who_fa_2();
                break;
            case $STATE['ST_WHO_FA_3']:
                // $weObj->text("ST_WHO_FA_3")->reply();
                who_fa_3();
                break;
            case $STATE['ST_WHO_FA']:
                // $weObj->text("ST_WHO_FA")->reply();
                who_fa();
                break;
            case $STATE['ST_WHO_POST']:
                who_post_res();
                break;

            // KILL FA
            case $STATE['ST_KILL_FA_1']:
                kill_fa_1();
                break;
            case $STATE['ST_KILL_FA']:
                kill_fa();
                break;
            case $STATE['ST_KILL_POST']:
                // $weObj->text("ST_WHO_FA")->reply();
                kill_post_res();
                break;

            // WOLF FA
            case $STATE['ST_WOLF_FA_1']:
            // $weObj->text("ST_WHO_FA")->reply();
                wolf_fa_1();
                break;
            case $STATE['ST_WOLF_FA_2']:
                wolf_fa_2();
                break;
            case $STATE['ST_WOLF_FA']:
                wolf_fa();
                break;
            case $STATE['ST_WOLF_POST']:
                wolf_post_res();
                break;

            // player
            case $STATE['ST_WHO_PLAYER']:
                who_player();
                break;
            case $STATE['ST_KILL_PLAYER']:
                kill_player();
                break;
            case $STATE['ST_WOLF_PLAYER']:
                wolf_player();
                break;

            default:
                $weObj->text("看不懂")->reply();
                break;
        }
        break;
    case Wechat::MSGTYPE_EVENT:
        $weObj->text($CONST_STR['welcome'])->reply();
        break;
    case Wechat::MSGTYPE_IMAGE:
        break;
    default:
        $weObj->text($CONST_STR['help_info'])->reply();
}

// //获取菜单操作:
// $menu = $weObj->getMenu();
// //设置菜单
// $newmenu =  array(
//   "button"=>
//       array(
//           array('type'=>'click','name'=>'最新消息','key'=>'MENU_KEY_NEWS'),
//           array('type'=>'view','name'=>'我要点单','url'=>'http://www.ingfs.com/today.php?user=' . $user),
//           )
// );
// $result = $weObj->createMenu($newmenu);
