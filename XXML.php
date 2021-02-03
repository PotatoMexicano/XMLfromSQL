<?php

require_once("../Controllers/Conexao.php");
$table1 = "registros";
header('Content-Type: text/xml');

$connection = Conexao::getConnection();

$stmt = $connection->query("SELECT * FROM ".$table1);
$keys = $stmt->fetch(PDO::FETCH_ASSOC);
// get all clumns from table
$size = count(array_keys($keys))-1;
$arr = array_keys($keys);

$str = '';
$first_column = '';

for ($i = 0; $i <=$size; $i++) {
    if($i == $size){
        $str .=  $arr[$i];
    }else{
        if($i == 0){
            $str .= $arr[$i].",";
            $first_column = $arr[$i];
        }
        $str .= $arr[$i].",";
    }
}
//for make string for next query
    $stmt = $connection->query("SELECT ".$str." FROM " . $table1);
//execute select with columns from frist select
    $xml = new SimpleXMLElement('<' . $table1 . '/>');
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $add = $xml->addChild('item'.$row[$first_column]);
        //mount item with 'item' + id from frist column

        foreach ($row as $key => $value) {
            $add->addChild($key, $value);
            //add values in $add
        }
    }
echo $xml->asXML();
    //mount xml on screen