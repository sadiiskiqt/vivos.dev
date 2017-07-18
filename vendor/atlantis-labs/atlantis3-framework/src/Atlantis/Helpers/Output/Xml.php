<?php

namespace Atlantis\Helpers\Output; 


class Xml  implements \Atlantis\Helpers\Interfaces\IOutputInterface {
  
  public  function output( $source, $params ) {
    
    if ( is_array($source) ) {
      
        $dom = new \DOMDocument('1.0', 'utf-8');
        
        $documentNode = $dom->appendChild($dom->createElement("document"));
        
        foreach( $source as $element => $value ) {
          
            $element = $dom->createElement( $element, htmlspecialchars(trim($value), ENT_QUOTES ) ); 
            
            $documentNode->appendChild($element);
            
        }
      
        return \Response::make($dom->saveXML(), '200')->header('Content-Type', 'text/xml');
      
    }
        
  }
  
}