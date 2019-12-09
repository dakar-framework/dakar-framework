<?php 
require_once("processor.php");

class Parser {
    
    public function __construct(){ }
    public static function parse($file_name) {
        $parsed_file = Processor::process($file_name);
        array_unshift($parsed_file,"<?php" . PHP_EOL);
        array_push($parsed_file,"?>");
        $parsed_file = implode("",$parsed_file);
        $file_name = explode(".",$file_name)[0];
        $php_file = fopen($file_name . ".php", 'w');
        fwrite($php_file,$parsed_file);
        fclose($php_file);

    }
}

?>