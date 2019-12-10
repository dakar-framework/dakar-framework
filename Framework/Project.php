<?php 

class ProjectStructure {
    public function __construct($dirname){
        // checks if a directory exists
        
        if(!is_dir(__DIR__.'/' .$dirname)) {
        // creates the project structure
              mkdir($dirname);
              mkdir($dirname.'/'.'App');
              mkdir($dirname.'/'.'App/php');
              mkdir($dirname.'/'.'App/views');
              mkdir($dirname.'/'.'App/controllers');
              touch($dirname.'/'.'router.dk');
              mkdir($dirname.'/'.'Database');
              touch($dirname.'/'.'config.toml');
              echo("Project $dirname created!");
            } else {
              echo "ERROR => directory exists".PHP_EOL;
            }
        }
}

?>