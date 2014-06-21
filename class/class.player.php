<?php

class player{
  private $mySql;
  private $tblName;
  private $fieldList;
  public $room;

  public function __construct($mysql, $room){
    $this->mySql     = $mysql;
    $this->tblName   = "player";
    $this->fieldList = array("id", "room", "no");
    $this->room    = $room;
  }

  function setRoom($room){
    $this->room = $room;
  }

  function getRoom(){
    return $this->room;
  }

  function findPlayer($id){
    $sql = "SELECT * FROM `$this->tblName`
            WHERE `id` = '$id'";
    return $this->mySql->getData($sql);
  }

  function updatePlayer($id, $room){
    $now = $this->getRoom()->getNow($room);
    $total = $this->getRoom()->getTotal($room);
    $now ++;
    if($now > $total){
      return false;
    }
    $sql = "UPDATE `$this->tblName`
            SET `room` = '$room',
                `no`   = '$now'
            WHERE `id` = '$id'";
    $this->mySql->runSql( $sql );
    return $this->getRoom()->setNow($room, $now);
  }

  function insertPlayer($id, $room){
    $now = $this->getRoom()->getNow($room);
    $total = $this->getRoom()->getTotal($room);
    $now ++;
    if($now > $total){
      return false;
    }
    $sql = "INSERT INTO `$this->tblName`
            VALUES('$id','$room','$now')";
    $this->mySql->runSql( $sql );
    return $this->getRoom()->setNow($room, $now);
  }

  function addPlayer($id, $room){
    if($this->findPlayer($id)){
      return $this->updatePlayer($id, $room);
    }
    else{
      return $this->insertPlayer($id, $room);
    }
  }

  function openRoom($id, $game){
    $room = rand(1, 999);

    while($this->getRoom()->findRoom($room)){
      $room = rand(1, 999);
    }

    $room += $game * 1000;

    $sql = "INSERT INTO `$this->tblName`
            VALUES('$id','$room',1)";
    $this->mySql->runSql( $sql );

    return $this->getRoom()->addRoom($room);
  }

  function closeRoom($id){
    $tmp = $this->findPlayer($id);
      $room = $tmp[0]['room'];

      $this->getRoom()->delRoom($room);

      $sql = "DELETE FROM `$this->tblName`
              WHERE `room` = '$room' ";

    return $this->mySql->runSql( $sql );
  }

  function getNo($id){
    $tmp = $this->findPlayer($id);
    return $tmp[0]['no'];
  }

  function getWord($id){
    $tmp = $this->findPlayer($id);
    $room = $tmp[0]['room'];
    $no = $tmp[0]['no'];
    return $this->getRoom()->getWord($room, $no);
  }

  function openPost($id){
    $tmp = $this->findPlayer($id);
    $room = $tmp[0]['room'];

    return $this->getRoom()->openPost($room);
  }

  function closePost($id){
    $tmp = $this->findPlayer($id);
    $room = $tmp[0]['room'];

    return $this->getRoom()->closePost($room);
  }

  function getPost($id){
    $tmp = $this->findPlayer($id);
    $room = $tmp[0]['room'];

    return $this->getRoom()->getPost($room);

  }

  function setPost($id, $postno){
    $tmp = $this->findPlayer($id);
    $room = $tmp[0]['room'];
    $no = $tmp[0]['no'];

    return $this->getRoom()->setPost($room, $no, $postno);
  }

  function getPostRes($id){
    $tmp = $this->findPlayer($id);
    $room = $tmp[0]['room'];

    return $this->getRoom()->getPostRes($room);
  }

  function clearPostNum($id){
    $tmp = $this->findPlayer($id);
    $room = $tmp[0]['room'];

    return $this->getRoom()->clearPostNum($room);
  }

  function hasPosted($id){
    $tmp = $this->findPlayer($id);
    $room = $tmp[0]['room'];
    $no = $tmp[0]['no'];

    return $this->getRoom()->hasPosted($room, $no);
  }

  function unsetWord($id){
    $tmp = $this->findPlayer($id);
    $room = $tmp[0]['room'];

    return $this->getRoom()->unsetWord($room);
  }

  function exitRoom($id){
    $tmp = $this->findPlayer($id);
      $room = $tmp[0]['room'];
      $no = $tmp[0]['no'];

      $now = $this->getRoom()->getNow($room);
      $this->getRoom()->setNow($room, $now - 1);

      for(;$no <= $now; $no ++){
        $no_1 = $no - 1;
        $sql = "UPDATE `$this->tblName`
                SET `no` = '$no_1'
                WHERE `room` = '$room' AND `no` = '$no'";
      $this->mySql->runSql( $sql );
      }

      $sql = "DELETE FROM `$this->tblName`
              WHERE `id` = '$id' ";

    return $this->mySql->runSql( $sql );
  }

  function setDead($id, $dead){
    $tmp = $this->findPlayer($id);
    $room = $tmp[0]['room'];

    return $this->getRoom()->setDead($room, $dead);
  }

  function isDead($id, $dead){
    $tmp = $this->findPlayer($id);
    $room = $tmp[0]['room'];

    return $this->getRoom()->isDead($room, $dead);
  }

  function setCp($id, $c, $p){
    $tmp = $this->findPlayer($id);
    $room = $tmp[0]['room'];

    return $this->getRoom()->setCp($room, $c, $p);
  }

}