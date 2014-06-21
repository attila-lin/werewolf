<?php

class who{
  private $mySql;
  private $tblName;
  private $fieldList;

  public function __construct($mysql){
    $this->mySql    = $mysql;
    $this->tblName  = "who_room";
    $this->fieldList= array("id", "room", "wodi", "word1", "word2", "now", "total", "post", "postnum");
  }

  function findRoom($room){
    $sql = "SELECT *
            FROM `$this->tblName`
            WHERE `room` = '$room'";
    return $this->mySql->getData($sql);
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

  function setWord1($room, $word1){
    $sql = "UPDATE `$this->tblName`
            SET `word1` = '$word1'
            WHERE `room` = '$room'";
    return $this->mySql->runSql( $sql );
  }
  function setWord2($room, $word2){
    $sql = "UPDATE `$this->tblName`
            SET `word2` = '$word2'
            WHERE `room` = '$room'";
    return $this->mySql->runSql( $sql );
  }

  function unsetWord($room){
    $sql = "UPDATE `$this->tblName`
            SET `word1` = '',
                `word2` = ''
            WHERE `room` = '$room'";
    return $this->mySql->runSql( $sql );
  }

  function getWord($room, $no){
    if( in_array( strval($no), $this->getWodi($room)) ){
      return $this->getWord2($room);
    }
    else{
      return $this->getWord1($room);
    }
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


  function getWord1($room){
    $tmp = $this->findRoom($room);
    return $tmp[0]['word1'];
  }

  function getWord2($room){
    $tmp = $this->findRoom($room);
    return $tmp[0]['word2'];
  }

  function addRoom($room) {
    $sql = "INSERT INTO `$this->tblName`
            VALUES(null,'$room','','','',1,1,0,'')";
    return $this->mySql->runSql( $sql );
  }

  function delRoom($room) {
    $sql = "DELETE FROM `$this->tblName`
        WHERE `room` = '$room'";
    return $this->mySql->runSql( $sql );
  }


  function beginGame($room){

    $total = $this->getTotal($room);

    $wodinum = intval($total / 7 + 1);

    $wodiarr = array();
    for($i = 0; $i < $wodinum; $i ++){
      $wodi = rand(2, $total);
      if( in_array($wodi, $wodiarr) ){
        $i --;
      }
      else{
        array_push($wodiarr, $wodi);
      }
    }
    $wodistr = implode('|',$wodiarr);

    $sql = "UPDATE `$this->tblName`
        SET `wodi` = '$wodistr'
        WHERE `room` = '$room'";
    $this->mySql->runSql( $sql );
    return $wodiarr;
  }

  function getWodi($room){
    $tmp = $this->findRoom($room);
    $wodi = $tmp[0]['wodi'];

    return explode("|", $wodi);
  }

  function setTotal($room, $total){
    $sql = "UPDATE `$this->tblName`
            SET `total` = '$total'
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

  // add words random
  //TODO
}