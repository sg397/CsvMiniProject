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
    public  function generateTable($records){

        $count = 0;
        $rowStr ='';
        foreach($records as $record){
            
                $array = $record->returnArray($record);

                if($count==0){
                    $fields = array_keys($array);
                    $rowStr .= html::populateTableRow($fields, -1);


                }

                $values = array_values($array);
                $rowStr .= html::populateTableRow($values, $count);
                $count++;

        }
        echo "<div> 
                    <table id='csv'>". $rowStr. "</table>
              </div>";
    }

    public function populateTableRow(Array $values, $count) {

        $cells = array();

        foreach($values as $cell){

            if($count <0){
                $cells[] = "<th> {$cell} </th>";
            }else{
                $cells[] = "<td> {$cell} </td>";
            }

        }
        $rowClass = ($count%2==0)? "even" : "odd";

        $rows[] = "<tr class={$rowClass}>". implode('', $cells). "</tr>";

        return implode('', $rows) ;

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


