<?php

ini_set("display_errors", 1);
error_reporting(E_ALL);

include "wechat.class.php";
include "const.php";
include "function.php";

include "./class/class.player.php";
include "./class/class.who.php";
include "./class/class.wolf.php";
include "./class/class.kill.php";


$options = array(
        'token'=>'lalala', //填写你设定的key
        // 'appid'=>'wx1efe5966178f5a9f', //填写高级调用功能的app id
        // 'appsecret'=>'fb152772bdd80a86e2a4117d482ed3d1', //填写高级调用功能的密钥
    );
$weObj = new Wechat($options);

// $echoStr = $weObj->valid($return=true);
// echo $echoStr;

$type = $weObj->getRev()->getRevType();
$user = $weObj->getRev()->getRevFrom();
$content = $weObj->getRev()->getRevContent();

$mysql = new SaeMysql();

$who_room = new who($mysql);
$kill_room = new kill($mysql);
$wolf_room = new wolf($mysql);

$player = new player($mysql);

function get_state($user){
    $tmp = $player->findPlayer($user);
    
    if($tmp){
        $room = $tmp[0]['room'];
        $no   = $tmp[0]['no'];
        $game = $room/1000;
        switch ($game) {
            case 1:
                $player->setRoom($who_room);
                
                if($no == 1){
                    if($player->room->getTotal($room) == 1)
                        return $ST_WHO_FA_1;
                    if($player->room->getWord1($room) == '')
                        return $ST_WHO_FA_2;
                    if($player->room->getWord2($room) == '')
                        return $ST_WHO_FA_3;
                    // all finished
                    return $ST_WHO_FA;
                }
                else
                    return $ST_WHO_PLAYER;
                break;
            case 2:
                $player->setRoom($kill_room);
                if($no == 1){
                    return $ST_KILL_FA;
                }
                else
                    return $ST_KILL_PLAYER;
                break;
            case 3:
                $player->setRoom($wolf_room);
                if($no == 1)
                    return $ST_WOLF_FA;
                else
                    return $ST_WOLF_PLAYER;
                break;
            default:
                break;
        }
    }
    else{
        return $ST_NEW;
    }
}

function open_who(){
    $player->setRoom($who_room);
    $player->openRoom($user, 1);
    $weObj->text($who_begin)->reply();
}

function open_kill(){
    $player->setRoom($kill_room);
    $player->openRoom($user, 2);
}

function open_wolf(){
    $player->setRoom($wolf_room);
    $player->openRoom($user, 3);
}

function true(){

}
function big(){

}

function goto_room(){
    $fixed_room = intval($content) % 1000;
    switch ($room/1000) {
        case 1:
            $player->setRoom($who_room);
            if($player->addPlayer($user, $fixed_room)){
                // "欢迎加入房间号1001,你的编号是2,输入“领”领取身份";
                $weObj->text($goto_1 . $content . $goto_2 . $player->getNo($user)[0] . $goto_3 )->reply();
            }
            else{
                $weObj->text($goto_error)->reply();
            }
            break;
        case 2:
            $player->setRoom($kill_room);
            $player->addPlayer($user, $fixed_room);
            break;
        case 3:
            $player->setRoom($wolf_room);
            $player->addPlayer($user, $fixed_room);
            break;
        default:
            break;
    }
}

function init() {
    switch($content) {
        case "开":
            $weObj->text($chose_game)->reply();
            break;
        case "1":
            open_who();
            break;
        case "2":
            open_kill();
            break;
        case "3":
            open_wolf();
            break;
        case "4":
            true();
            break;
        case "5":
            big();
            break;
        default:
            goto_room();
    }
}

function who_fa_1(){
    $total = intval($content);
    $tmp = $player->findPlayer($user);
    $room = $tmp[0]['room'];

    $player->setRoom($who_room);
    $player->room->setTotal($room, $total); 

    $weObj->text($set_word1)->reply();
}

function who_fa_2(){
    $word1 = intval($content);
    $tmp = $player->findPlayer($user);
    $room = $tmp[0]['room'];

    $player->setRoom($who_room);
    $player->room->setWord1($room, $word1); 

    $weObj->text($set_word2)->reply();
}

function who_fa_3(){
    $word2 = intval($content);
    $tmp = $player->findPlayer($user);
    $room = $tmp[0]['room'];

    $player->setRoom($who_room);
    $player->room->setWord2($room, $word2); 

    $res = $who_res_1;
    $res += string($room) . ' ' . $who_res_2;
    $wodi = $player->room->beginGame();
    foreach ($wodi as $key => $value) {
        $res += string($value);
        $res += " ";
    }
    $res += $who_res_3;

    $weObj->text($res)->reply();
}

function who_fa(){
    switch($content) {
        case "开":
            $weObj->text($chose_game)->reply();
            break;
        case "换":
            $player->setRoom($who_room);
            $player->room->unsetWord();
            $weObj->text($set_word1)->reply();
            break;
        case "换":
            $player->setRoom($who_room);
            $player->room->unsetWord();
            $weObj->text($set_word1)->reply();
            break;
    }
}

function kill_fa(){

}

function who_player(){
    switch($content) {
        case "领":
            $weObj->text($player->getWord())->reply();
        default:
            $weObj->text($goto_help)->reply();
    }
}

function kill_player(){

}

function wolf_player(){

}

switch($type) {
    case Wechat::MSGTYPE_TEXT:
    	$weObj->text("hehe")->reply();
        switch ( get_state($user) ) {
            case $ST_NEW:
                init();
                break;

            case $ST_WHO_FA_1:
                who_fa_1();
                break;
            case $ST_WHO_FA_2:
                who_fa_2();
                break;
            case $ST_WHO_FA_3:
                who_fa_3();
                break;

            case $ST_WHO_FA:
                who_fa();
                break;
            case $ST_KILL_FA:
                kill_fa();
                break;
            case $ST_WOLF_FA:
                wolf_fa();
                break;

            case $ST_WHO_PLAYER:
                who_player();
                break;
            case $ST_KILL_PLAYER:
                kill_player();
                break;
            case $ST_WOLF_PLAYER:
                wolf_player();
                break;

            default:
                break;
        }
    case Wechat::MSGTYPE_EVENT:
        $weObj->text($welcome)->reply();
        break;
    case Wechat::MSGTYPE_IMAGE:
        break;
    default:
        $weObj->text($help_info)->reply();
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
