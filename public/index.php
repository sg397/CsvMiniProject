
<html>
    <head>

        <title>Csv Mini Project</title>
        <link rel="stylesheet" type="text/css" href="stylesheets/main.css">
        <link rel="stylesheet" type="text/css" href="stylesheets/bootstrap.css">

    </head>
    <body>
        <div class="container">

            <?php main::start("example.csv"); ?>

        </div>
    </body>
</html>
<?php
/**
 * Created by PhpStorm.
 * User: sunitha
 * Date: 10/1/18
 * Time: 10:11 PM
 */


/**
 * classname: main
 * input: csv file name
 * output: html table
 * */

class main {

    static public function start($filename){

        $records = csv::getRecords($filename);

        $tableStr = html::generateTable($records);

        html::printTable( $tableStr);

    }
}

class html{

    public function generateTable($records){

        $count = 0;
        $rowStr ='';

        foreach($records as $record){
            
                $array = $record->returnArray($record);

                if($count==0){
                    /* creating html table header row  */
                    $fields = array_keys($array);
                    $rowStr .= '<tr><th>' .implode('</th><th>', $fields) .'</tr>';
                }

                /* creating html table rows for every record object 0,1,2..n */
                $values = array_values($array);

                //empty row handling
                if(sizeof($values) >0){

                    /* setting css class for odd/even rows */
                    $rowClass = ($count%2==0)? "even" : "odd";

                    $rowStr .= "<tr class='$rowClass'><td>" .implode('</td><td>', $values) ."</tr>";

                    $count++;

                }

        }

        return $rowStr;
    }

    public function printTable($rowStr){

        echo " <table id='csv' class='table .table-striped .table-bordered'> <tbody>". $rowStr. "</tbody></table> ";

    }

}

class csv{

    public static function getRecords($filename){

        $file = fopen (  $filename, "r");
        $fieldNames = array();
        $count =0;

        while ( ! feof($file)){

            $record = fgetcsv($file);

            if($count==0){
                $fieldNames = $record;
            } else {
                $records[] = recordFactory::create($fieldNames, $record);
            }

            $count++;
        }

        fclose($file);

        return $records;

    }
}

class record {
    public function __construct(Array $fieldNames = null,  $values = null)    {

        $record = array_combine($fieldNames, $values );

        foreach ($record as $property => $value){
            $this->createProperty($property , $value);
        }

    }

    public function returnArray(){

        $array = (array) $this;
        return $array;

    }

    public function createProperty($name= null, $value=null) {

        $this->{$name} = $value;

    }
}

class recordFactory {

    public static  function create(Array $fieldNames = null,  $values = null) {

        $record = new record($fieldNames, $values);

        return $record;
    }

}

?>
