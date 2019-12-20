<?php 

class Processor {

    public static $KEYWORDS = ["return","extends","as","class","elseif","endif","catch","implements","interface","trait" ,"instanceof","insteadof","else","if","function","for","endfor","clone","include_once","require_once","try","static","const","echo","print","print_r","switch","endswitch","foreach","do","while","endwhile","break","continue","new","yield","namespace","and","or","case","default","use","include","require","final",'global',"private","public","protected","declare","enddeclare","goto","finally"] ; 
    
    public static $SYMBOLS = [
        "LEFT_PAREN" => "(",
        "RIGHT_PAREN" => ")",
        "LEFT_BRACKET" => "[",
        "RIGHT_BRACKET" => "]",
        "LEFT_CURLY_BRACE" => "{",
        "RIGHT_CURLY_BRACE" => "}",
        "PLUS_SIGN" => "+",
        "MINUS_SIGN" => "-",
        "UNDERSCORE" => "_",
        "MULTIPLICATION_SIGN" => "*",
        "EQUAL_SIGN" => "=",
        "SLASH_SIGN" => "/",
        "SEMICOLON" => ";",
        "COLON" => ":",
        "COMMA" => ",",
        "DOT" => ".",
        "QUESTION_MARK" => "?",
        "EXCLAMATION_MARK" => "!",
        "AMPERSAND" => "&",
        "QUOTE" => "'",
        "DOUBLE_QUOTE" => "\"",
        "LESS_THAN_SIGN" => "<",
        "_THAN_SIGN" => "<",
        "SPACE" => " ",
        "AT_SIGN" => "@",
        "BACKSLASH" => "\\",
        "PERCENT_SIGN" => "%",
        "PIPE" => "|",
        "HASH" => "#"
    ];
        
        public function __construct($file_name){}

        public static function process($file_name) : array { 
            $processed_file = [];
         
            $file = file_get_contents($file_name);
            print("Processing..\n");
            $line_number = 0 ; 
            $file = explode("\n",$file);
            // walk the file per line
            foreach($file as &$line){
                ltrim($line);
                self::add_sc($line);
                self::process_variables($line);
               
                $line_number++ ; 
                array_push($processed_file,$line);
            }
            return $processed_file ; 

        }
        
        public function benchmark() {
            $date1 = strtotime(date('Y-m-d H:i:s')); 
            print($date1);
            print("\nProcessing...\n");  
            sleep(3);
            $date2 = strtotime(date('Y-m-d H:i:s')) ; 
            echo "elapsed : ";
            echo $date2 - $date1;
            echo " seconds ";
        }
        public function lex($file_name) {
             
            $file = file($file_name) ; 
            $line_number = 0 ; 
            foreach($file as $line){

                $line_number++ ; 
                echo "line : $line_number "  . PHP_EOL ; 
                // var_export(self::find_keywords($line));
                // var_dump(self::find_symbols($line));
                // var_export(self::find_function($line));
                // var_export(self::_variables($line));
                
            }
            return ; 
        }

       
        static public function find_keywords($line){
            // full keywords count
            $KEYWORD_COUNT = 0 ;
            
            $words = explode(" ",$line);
            if(array_intersect(self::$KEYWORDS,$words)){
                $KEYWORD_COUNT++ ; 
            }
            
            return  "Found $KEYWORD_COUNT keywords ." . PHP_EOL ; 
        } 
        
        static public function find_symbols($line){
            
            // SYMBOLS_FOUND In the line
            $SYMBOLS_FOUND = [] ; 
            $SYMBOLS_COUNT = 0 ; 
            $Z= [] ; 
            foreach(str_split($line) as $char){
                if(in_array($char,self::$SYMBOLS)){
                    $SYMBOLS_COUNT++ ; 
                    array_push($SYMBOLS_FOUND,$char);
                    $key = array_search($char,self::$SYMBOLS);
                    array_push($Z,$key);
                }
            }
            return ["SYMBOLS ($SYMBOLS_COUNT)" , $SYMBOLS_FOUND   , $Z]; 
        
        } 
        
        // find functions    
        static public function process_function(&$line) {
            $regex = "/(?!_|\d)\b\w+[0-9]*\([a-z]*\)?/" ;
            preg_match($regex,$line,$function_found);
            if($function_found){
                $function_found_name = explode("(",$function_found[0])[0];
                if(in_array($function_found_name,self::$KEYWORDS)){
                    $function_found_name .= "(KEYWORD)" . PHP_EOL;
                }
          
            }
        }   

     
        // Process semicolons
        static public function add_sc(&$line) {
            if(substr( rtrim($line),  -1) == self::$SYMBOLS["LEFT_CURLY_BRACE"] || 
                substr( rtrim($line),  -1) == self::$SYMBOLS["RIGHT_CURLY_BRACE"] 
            ){
                $line[strlen($line)] = PHP_EOL ;
            }  elseif(strlen(trim($line)) == 0 ){
            }else {
                $line[strlen($line)] = ";" ;
                $line[strlen($line)+ 1] = PHP_EOL ;
            }
           
        }   

         // process the variables 
             static public function process_variables(&$line) {

            $regex = "/[a-zA-Z]+(?![\w\(+])/" ;
            // check the line for varibales 
            preg_match_all($regex,$line,$variable_found);

            if($variable_found) {
                
                // regex to find string
                preg_match_all("/\".+\"/",$line,$var_in_string);
                $string_words = []; 
                foreach($variable_found[0] as $var) {
                   foreach($var_in_string[0] as $str_var){
                        if(strpos($str_var ,$var)){
                            if(!in_array($var,self::$KEYWORDS)){
                                array_push($string_words,$var) ; 
                            }
                        }
                   }

                    if(!in_array($var,self::$KEYWORDS)){        
                        if($var=="fn"){
                            $line =  str_replace($var, "function", $line);
                        } 
                                    
                        if(!in_array($var,$string_words)) {
                           $line =  str_replace($var, '$'.$var, $line);
                           }
                    }                  
                
                }
            }            

        }


}

        ?>