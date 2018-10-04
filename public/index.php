<?php
/**
 * Created by PhpStorm.
 * User: sunitha
 * Date: 10/1/18
 * Time: 10:11 PM
 */


main::start("example.csv");

class main {
    static public function start($filename){

        echo 'Start 123....';
        $records = csv::getRecords($filename);

        print_r($records);

    }
}

class csv{
    public static function getRecords($filename){
        echo 'in  csv::getRecords';
        $file = fopen (  $filename, "r");

        while ( ! feof($file)){

            $record = fgetcsv($file);
            $records[] = $record;

        }
        fclose($file);
        return $records;

    }
}


