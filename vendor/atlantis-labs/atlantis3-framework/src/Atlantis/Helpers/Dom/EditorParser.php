<?php

namespace Atlantis\Helpers\Dom;

use Atlantis\Helpers\Tools;
use Atlantis\Models\Repositories\ModulesRepository;

class EditorParser {

  public $document;
  public $items;
  private $patternFuncParams = array();
  private $dataPatternFunc = 'data-pattern-func';

  public function __construct($doc) {

    $this->document = new \DOMDocument();

    @$this->document->loadHTML(mb_convert_encoding($doc, 'HTML-ENTITIES', 'UTF-8'));

    $this->document->formatOutput = true;

    $this->items = $this->getAllElements();
  }

  public function getAllElements() {

    return $this->document->getElementsByTagName('*');
  }

  public function process() {
    
    for ($i = 0; $i < $this->items->length; $i++) {

      if ($this->items->item($i)->hasAttribute($this->dataPatternFunc)) {

        $attribute = $this->items->item($i)->getAttribute($this->dataPatternFunc);

        $aAttr = array();

        foreach ($this->items->item($i)->attributes as $attr) {
          $aAttr[$attr->nodeName] = $attr->nodeValue;
        }

        unset($aAttr[$this->dataPatternFunc]);

        $this->patternFuncParams[$attribute] = Tools::makeAppParamsFromString($attribute, $aAttr);

      }
    }
    
    return $this->patternFuncParams;
  }
  
  public function withModules() {
    
    foreach ($this->patternFuncParams as $attribute => $params) {
      $module = ModulesRepository::getModule($params['class']);
      $this->patternFuncParams[$attribute]['module'] = $module->first();
    }
    
    return $this->patternFuncParams;
    
  }

}
