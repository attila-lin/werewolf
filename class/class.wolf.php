<?php

class wolf{
  private $mySql;
  private $tblName;
  private $fieldList;

  public function __construct($mysql){
    $this->mySql    = $mysql;
    $this->tblName  = "wolf_room";

    $this->fieldList= array("id", "room",
//  狼人     平民       女巫     预言家      丘比特     医生
  "wolf", "common", "witch", "prophet", "cupid", "doctor",
//  守卫     长老      猎人      小女孩  白痴  吹笛者
  "ward", "eldest", "hunter", "girl", "idiot", "flute",
  "num",
  "now", "total", "post", "postnum", "dead", "cp");
  }

  function findRoom($room){
    $sql = "SELECT * FROM `$this->tblName`
            WHERE `room` = '$room'";
    return $this->mySql->getData($sql);
  }

  function getNum($room){
    $tmp = $this->findRoom($room);
    $num = explode('|', $tmp[0]['num']);

    return $num;
  }

  function setNum($room, $num){
    $total = 1;
    for($i = 0; $i < 12 ;$i++)
      $total += $num[$i];

    $numstr = implode('|', $num);

    $sql = "UPDATE `$this->tblName`
            SET `num`   = '$numstr',
                `total` = '$total'
            WHERE `room` = '$room'";
    return $this->mySql->runSql( $sql );
  }

  function getNow($room){
    $tmp = $this->findRoom($room);
    return $tmp[0]['now'];
  }

  function getTotal($room){
    $tmp = $this->findRoom($room);
    return $tmp[0]['total'];
  }

  function setNow($room, $now){
    $sql = "UPDATE `$this->tblName`
            SET `now` = '$now'
            WHERE `room` = '$room'";
    return $this->mySql->runSql( $sql );
  }

  function hasPosted($room, $no){
    $tmp = $this->findRoom($room);
    $postnum = $tmp[0]['postnum'];

    foreach (explode('|', $postnum) as $key => $value) {
      $x = explode(':', $value);
      if(strval($no) == $x[0]){
        return true;
      }
    }
    return false;
  }

  function setPost($room, $no, $postno){
    $tmp = $this->findRoom($room);
    $postnum = $tmp[0]['postnum'];

    if($postnum == ''){
      $postnum = strval($no) . ':' . strval($postno);
    }
    else{
      $postnum .= '|' . strval($no) . ':' . strval($postno);
    }

    $sql = "UPDATE `$this->tblName`
            SET `postnum` = '$postnum'
            WHERE `room` = '$room'";

    return $this->mySql->runSql( $sql );
  }

  function getIdentity($room){
    $tmp = $this->findRoom($room);
    $id = array();

    $id['wolf'] = $tmp[0]['wolf'];
    $id['common'] = $tmp[0]['common'];
    $id['witch'] = $tmp[0]['witch'];
    $id['prophet'] = $tmp[0]['prophet'];
    $id['cupid'] = $tmp[0]['cupid'];
    $id['doctor'] = $tmp[0]['doctor'];
    $id['ward'] = $tmp[0]['ward'];
    $id['eldest'] = $tmp[0]['eldest'];
    $id['hunter'] = $tmp[0]['hunter'];
    $id['girl'] = $tmp[0]['girl'];
    $id['idiot'] = $tmp[0]['idiot'];
    $id['flute'] = $tmp[0]['flute'];

    $idarr = array();
    foreach ($id as $key => $value) {
      array_push($idarr, explode("|", $value));
    }
    return $idarr;
  }

  function addRoom($room) {
    $sql = "INSERT INTO `$this->tblName`
        VALUES(null,'$room',
          '','','','','','','','','','','','',
          '',
          1,1,0,'','','')";
    return $this->mySql->runSql( $sql );
  }

  function beginGame($room){
    // setIdentity
    $num = $this->getNum($room);

    $total = $this->getTotal($room);
    $identity = range(2, $total);

    // random array
    shuffle($identity);

    $idarr = array();
    $i = 0;
    foreach ($num as $key => $value) {
      $subarr = array_slice($identity, $i, $value);
      $str = implode("|", $subarr);
      array_push($idarr, $str);
      $i += $value;
    }

    $sql = "UPDATE `$this->tblName`
            SET `wolf`    =   '$idarr[0]',
                `common`  =   '$idarr[1]',
                `witch`   =   '$idarr[2]',
                `prophet` =   '$idarr[3]',
                `cupid`   =   '$idarr[4]',
                `doctor`  =   '$idarr[5]',
                `ward`    =   '$idarr[6]',
                `eldest`  =   '$idarr[7]',
                `hunter`  =   '$idarr[8]',
                `girl`    =   '$idarr[9]',
                `idiot`   =   '$idarr[10]',
                `flute`   =   '$idarr[11]',
                `post`    =   0,
                `postnum` =   '',
                `dead`    =   '',
                `cp`      =   ''
            WHERE `room` = '$room'";
    $this->mySql->runSql( $sql );
    return $idarr;
  }

  function setTotal($room, $total){
    $sql = "UPDATE `$this->tblName`
            SET `total` = '$total'
            WHERE `room` = '$room'";
    return $this->mySql->runSql( $sql );
  }

  function setCp($room, $c, $p){
    $cp = strval($c) . '|' . strval($p);
    $sql = "UPDATE `$this->tblName`
            SET `cp` = '$cp'
            WHERE `room` = '$room'";
    return $this->mySql->runSql( $sql );
  }

  function openPost($room){
    $sql = "UPDATE `$this->tblName`
            SET `post` = 1
            WHERE `room` = '$room'";
    return $this->mySql->runSql( $sql );
  }

  function closePost($room){
    $sql = "UPDATE `$this->tblName`
            SET `post` = 0
            WHERE `room` = '$room'";
    return $this->mySql->runSql( $sql );
  }

  function getPost($room){
    $tmp = $this->findRoom($room);
    return $tmp[0]['post'];
  }

  function getPostRes($room){
    $tmp = $this->findRoom($room);
    $postnum = $tmp[0]['postnum'];

    if($postnum == ''){
      return false;
    }

    $count = array();



    foreach (explode('|', $postnum) as $key => $value) {
      $x = explode(':', $value);
      if( array_key_exists($x[1],$count) ){
        $count[$x[1]] ++;
      }
      else{
        $count[$x[1]] = 1;
      }
    }
    return $count;
  }

  function clearPostNum($room){
    $sql = "UPDATE `$this->tblName`
            SET `postnum` = ''
            WHERE `room` = '$room'";
    return $this->mySql->runSql( $sql );
  }

  function delRoom($room) {
    $sql = "DELETE FROM `$this->tblName`
            WHERE `room` = '$room'";
    return $this->mySql->runSql( $sql );
  }

  function isDead($room, $deadstr){
    $tmp = $this->findRoom($room);
    $dead = $tmp[0]['dead'];

    $deadarr = explode('|', $dead);
    return in_array(intval($deadstr), $deadarr);
  }

  function setDead($room, $deadstr){
    $tmp = $this->findRoom($room);
    $dead = $tmp[0]['dead'];

    if($dead == ''){
      $dead = $deadstr;
    }
    else {
      $dead .= '|' . $deadstr;
    }
    $sql = "UPDATE `$this->tblName`
        SET `dead` = '$dead'
        WHERE `room` = '$room'";
    return $this->mySql->runSql( $sql );
  }

  function getWord($room, $no){
    $tmp = $this->findRoom($room);

//  狼人     平民       女巫     预言家      丘比特     医生
// "wolf", "common", "witch", "prophet", "cupid", "doctor",
//  守卫     长老      猎人      小女孩  白痴  吹笛者
// "ward", "eldest", "hunter", "girl", "idiot", "flute",
    $wolf   = $tmp[0]['wolf'];
    $common = $tmp[0]['common'];
    $witch  = $tmp[0]['witch'];
    $prophet= $tmp[0]['prophet'];

    $cupid  = $tmp[0]['cupid'];
    $doctor = $tmp[0]['doctor'];
    $ward   = $tmp[0]['ward'];
    $eldest = $tmp[0]['eldest'];

    $hunter = $tmp[0]['hunter'];
    $girl   = $tmp[0]['girl'];
    $idiot  = $tmp[0]['idiot'];
    $flute  = $tmp[0]['flute'];

    if( in_array( strval($no), explode("|", $wolf)) ){
      return 1;
    }
    if( in_array( strval($no), explode("|", $common)) ){
      return 2;
    }
    if( in_array( strval($no), explode("|", $witch)) ){
      return 3;
    }
    if( in_array( strval($no), explode("|", $prophet)) ){
      return 4;
    }
    if( in_array( strval($no), explode("|", $cupid)) ){
      return 5;
    }
    if( in_array( strval($no), explode("|", $doctor)) ){
      return 6;
    }
    if( in_array( strval($no), explode("|", $ward)) ){
      return 7;
    }
    if( in_array( strval($no), explode("|", $eldest)) ){
      return 8;
    }
    if( in_array( strval($no), explode("|", $hunter)) ){
      return 9;
    }
    if( in_array( strval($no), explode("|", $girl)) ){
      return 10;
    }
    if( in_array( strval($no), explode("|", $idiot)) ){
      return 11;
    }
    if( in_array( strval($no), explode("|", $flute)) ){
      return 12;
    }
  }
}