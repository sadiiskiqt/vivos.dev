<?php

namespace Atlantis\Models\Pattern;

use Atlantis\Helpers\RegexMatcher as RegexMatcher;
use Atlantis\Helpers\Tools as Tools;

class Url {

  protected $data;
  protected $tpl;

  public function __construct($oData) {

    $this->data = $oData;
  }

  public function init() {
    
    if ($this->data->url != '' && $this->data->view != '') {

      return \view('atlantis::patterns/' . $this->data->view ,  [ 'text'  => Tools::makeAppCallFromString($this->data->url) ] );
      
    } else {

      return Tools::makeAppCallFromString($this->data->url);
    }
  }

}