<?php

class kill{
  private $mySql;
  private $tblName;
  private $fieldList;

  public function __construct($mysql){
    $this->mySql    = $mysql;
    $this->tblName  = "kill_room";

    $this->fieldList= array("id", "room",
      "kill", "plice", "common", "doctor", // 杀手 警察 平民 医生
      "num",
      "now", "total", "post", "postnum", "dead");
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
    $total = $num[0] + $num[1] + $num[2] + $num[3] + 1;
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

  function getIdentity($room){
    $tmp = $this->findRoom($room);
    $id = array();

    $id['kill'] = $tmp[0]['kill'];
    $id['plice'] = $tmp[0]['plice'];
    $id['common'] = $tmp[0]['common'];
    $id['doctor'] = $tmp[0]['doctor'];

    $idarr = array();
    foreach ($id as $key => $value) {
      array_push($idarr, explode("|", $value));
    }
    return $idarr;
  }

  function addRoom($room) {
    $sql = "INSERT INTO `$this->tblName`
            VALUES(null,'$room',
            '','','','',
            '',
            1,1,0,'','')";
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
            SET `kill`  =   '$idarr[0]',
            `plice` =   '$idarr[1]',
            `common` =  '$idarr[2]',
            `doctor` =  '$idarr[3]',
            `post` =  0,
            `postnum` = '',
            `dead`  =   ''
            WHERE `room` =  '$room'";
    $this->mySql->runSql( $sql );
    return $idarr;
  }

  function setTotal($room, $total){
    $sql = "UPDATE `$this->tblName`
            SET `total` = '$total'
            WHERE `room` = '$room'";
    return $this->mySql->runSql( $sql );
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

  function isDead($room, $deadstr){
    $tmp = $this->findRoom($room);
    $dead = $tmp[0]['dead'];

    $deadarr = explode('|', $dead);
    return in_array(intval($deadstr), $deadarr);
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

  function delRoom($room) {
    $sql = "DELETE FROM `$this->tblName`
            WHERE `room` = '$room'";
    return $this->mySql->runSql( $sql );
  }

  function getPost($room){
    $tmp = $this->findRoom($room);
    return $tmp[0]['post'];
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

  function getPostRes($room){
    $tmp = $this->findRoom($room);
    $postnum = $tmp[0]['postnum'];

    if($postnum == ''){
      return false;
    }

    $count = array();

    foreach (explode('|', $postnum) as $key => $value) {
      $x = explode(':', $value);
      if(array_key_exists($x[1],$count)){
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


  function getWord($room, $no){
    $tmp = $this->findRoom($room);

    // "kill", "plice", "common", "doctor"
    $kill = $tmp[0]['kill'];
    $plice = $tmp[0]['plice'];
    $common = $tmp[0]['common'];
    $doctor = $tmp[0]['doctor'];


    if( in_array( strval($no), explode("|", $kill)) ){
      return 1;
    }
    if( in_array( strval($no), explode("|", $plice)) ){
      return 2;
    }
    if( in_array( strval($no), explode("|", $common)) ){
      return 3;
    }
    if( in_array( strval($no), explode("|", $doctor)) ){
      return 4;
    }
  }

}