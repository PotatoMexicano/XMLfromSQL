<?php

class XMLfromSQL
{
public static function generate($table,$connection): SimpleXMLElement
{
    
    //How to use: 
    // another .php call for: $xml = XMLfromSQL::generate("table_name",$connection); 
    //Use to show result: echo $xml->asXML();
    
    header('Content-Type: text/xml');
    //header('Content-Type: text/html; charset=utf-8');

    $stmt = $connection->query("SELECT * FROM " . $table);
    $keys = $stmt->fetch(PDO::FETCH_ASSOC);
    // get all columns from table
    $size = count(array_keys($keys)) - 1;
    $arr = array_keys($keys);


    $str = '';
    $first_column = '';

    for ($i = 0; $i <= $size; $i++) {
        if ($i == $size) {
            $str .= $arr[$i];
        } else {
            if ($i == 0) {
                $first_column = $arr[$i];
            }
            $str .= $arr[$i] . ",";
        }
    }
    //for make string for next query
    $stmt = $connection->query("SELECT " . $str . " FROM " . $table);
    //execute select with columns from first select
    $xml = new SimpleXMLElement('<' . $table . '/>');
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $add = $xml->addChild('item' . $row[$first_column]);
        //mount item with 'item' + id from first column

        foreach ($row as $key => $value) {
            $add->addChild($key, htmlspecialchars($value));
            //add values in $add
        }
    }
    return $xml;
    //mount xml on screen
    }
}
