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
 * input: csv file name
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

                /* Using just first record to get the fields/ Table Header names */
                if($count==0){

                    $fields = array_keys($array);

                    /* Using count = -1 to represent Header row in the table. */
                    $rowStr .= html::populateTableRow($fields, -1);

                }

                /* reading rows from every record */
                $values = array_values($array);

                /* count is used to identify odd/even row to set the css styles */
                $rowStr .= html::populateTableRow($values, $count);

                $count++;

        }

        /*printing the HTML table*/
        echo "<div> <table id='csv'>". $rowStr. "</table> </div>";
    }



    /**
     * function: populateTableRow
     * inputs : Array of record values, line count
     * output: html table row as string
     * */
    public function populateTableRow(Array $values, $count) {

        $cells = array();

        foreach($values as $cell){

            if($count <0){
                /* this is header row, setting <th> */
                $cells[] = "<th> {$cell} </th>";
            }else{
                $cells[] = "<td> {$cell} </td>";
            }

        }

        /* setting css class for odd/even rows */
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


