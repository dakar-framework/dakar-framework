<?php 
require_once("processor.php");

class Parser {
    
    public function __construct(){ }
    public static function parse($file_name,$production_file_location) {
        $file_name_path = $file_name ; 
        $parsed_file = Processor::process($file_name);

        array_unshift($parsed_file,"<?php" . PHP_EOL);
        array_push($parsed_file,"?>"); 

        $parsed_file = implode("",$parsed_file);

        $file_name_path = mb_substr($file_name_path,0,-3);
        $file_name_path = @end(explode("/",$file_name_path));
        $php_file = fopen("$production_file_location" . "/" . "$file_name_path.php", 'w');
        fwrite($php_file,$parsed_file);
        fclose($php_file);
        echo "\nCreated file $production_file_location" . "/" . "$file_name_path.php" ; 
    }
}   

?>