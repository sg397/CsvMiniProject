<style>
    <?php include 'stylesheets/main.css'; ?>
</style>

<?php
/**
 * Created by PhpStorm.
 * User: sunitha
 * Date: 10/1/18
 * Time: 10:11 PM
 */



main::start("example.csv");

/**
 * classname: main
 * input: filename
 * output: html table
 * */

class main {

    static public function start($filename){

        $records = csv::getRecords($filename);

        $table = html::generateTable($records);

    }
}

class html{
    public static function generateTable($records){

        $count = 0;
        $rows = array();

        foreach($records as $record){
            
            if($count==0){

                $array = $record->returnArray($record);
                $fields = array_keys($array);
                $values = array_values($array);

                //Header row
                $cells = array();

                foreach($fields as $cell){
                    $cells[] = "<th> {$cell} </th>";
                }
                $rows[] = "<tr class='tableheader'>". implode('', $cells). "</tr>";

                $cells = array();
                //First Row
                foreach($values as $cell){
                    $cells[] = "<td> {$cell} </td>";
                }
                $rowClass = ($count%2==0)? "even" : "odd";
                $rows[] = "<tr class= {$rowClass} >" . implode('', $cells). "</tr>";


            } else {

                $array = $record->returnArray($record);
                $values = array_values($array);
                //print_r($values);
                $cells = array();

                //table rows
                foreach($values as $cell){
                    $cells[] = "<td> {$cell} </td>";
                }
                $rowClass = ($count%2==0)? "even" : "odd";
                $rows[] = "<tr class={$rowClass}>". implode('', $cells). "</tr>";


            }
            $count++;

        }
        echo "<div> 
                    <table id='csv'>". implode('',$rows). "</table>
              </div>";
    }

    public function populateTableRow($values) {
        /* need to implement DRY */

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
    public function __construct(Array $fieldNames = null,  $values = null)
    {

        $record = array_combine($fieldNames, $values );
        foreach ($record as $property => $value){
            $this->createProperty($property , $value);
        }


    }

    public function returnArray(){
        $array = (array) $this;
        return $array;
    }
    public function createProperty($name= "NAME", $value="VALUE") {
        $this->{$name} = $value;

    }
}

class recordFactory {
    public static  function create(Array $fieldNames = null,  $values = null) {
        $record = new record($fieldNames, $values);

        return $record;
    }

}


