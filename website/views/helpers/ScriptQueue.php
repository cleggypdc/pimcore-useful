<?php
class Zend_View_Helper_ScriptQueue extends \Zend_View_Helper_Abstract {
   
   /*
    * Some constants for zend registry
    */
   const QUEUE_REGISTRY_ID    = 'qs_script_queue';
   const POSITION_REGISTRY_ID = 'qs_script_positions'; 
   
   public function scriptQueue() {
      return $this;
   }
   
   
   /**
    * Queues a script 
    * @param string $name - a unique friendly identifier
    * @param string $path - the path to the script (i.e src)
    * @param string $dependancies - other scripts this script depends on
    */
   public function add($name, $path, $dependancies=array()) {
       
      if(\Zend_Registry::isRegistered(self::QUEUE_REGISTRY_ID)) {
         $scriptQueue     = \Zend_Registry::get(self::QUEUE_REGISTRY_ID);
         $scriptPositions = \Zend_Registry::get(self::POSITION_REGISTRY_ID);
      } else {
         $scriptQueue     = array();
         $scriptPositions = array();
      }
       
      $scriptPositions[$name] = count($scriptQueue);
      $scriptQueue[$name] = array(
            'path' => $path,
            'dependancies' => $dependancies,
      );
       
      \Zend_Registry::set(self::QUEUE_REGISTRY_ID,    $scriptQueue);
      \Zend_Registry::set(self::POSITION_REGISTRY_ID, $scriptPositions);
      
      return $this;
   }//queueScript
   
   
   /**
    * Returns the html for all of the scripts to be included. 
    * @return string
    */
   public function getHtml() {
   
      if(!\Zend_Registry::isRegistered(self::QUEUE_REGISTRY_ID)) {
         return '';
      }
   
      //put each script name in a queue
      $scriptQueue      = \Zend_Registry::get(self::QUEUE_REGISTRY_ID);
      $scriptPositions  = \Zend_Registry::get(self::POSITION_REGISTRY_ID);
   
      //for every script name
      foreach($scriptQueue as $scriptName => &$details) {
         //if it has any dependancies
         if(is_array($details['dependancies'])) {
            //get its position in the queue
            $currentPosition=$details['pos'];
            //for each dependancy
            foreach($details['dependancies'] as $dep) {
               //if that dependancy is in the queue
               if(array_key_exists($dep, $scriptPositions) && $scriptPositions[$dep] > $currentPosition) {
                  //shift that script to the next position in the queue
                  $scriptPositions[$scriptName] = $details['pos'] = $scriptPositions[$dep] + 1;
               }
            }
         }
      }
   
      //order the script positions
      asort($scriptPositions);
   
      $html = '';
      //now print out each script in its correct order
      foreach($scriptPositions as $scriptName=>$position) {
         $html .= '<script src="' . $scriptQueue[$scriptName]['path'] . '" ></script>'.PHP_EOL;
      } unset($scriptName, $position);
   
      return $html;
   }//printScriptQueue
   
   
}//class
