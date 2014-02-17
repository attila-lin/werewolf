<?
@define("DB_HOST", "w.rdc.sae.sina.com.cn:3307");	
@define("DB_USER", "3wzz1kk0j0");
@define("DB_PWD" , "m2l23jl32k520mwwhm03z3jj0kikm4iimz0jzj1j");	
@define("DB_NAME", "app_playgame01");
require_once("./class/class.db.php");


$db	 = new db(DB_HOST, DB_USER, DB_PWD, DB_NAME);
?>