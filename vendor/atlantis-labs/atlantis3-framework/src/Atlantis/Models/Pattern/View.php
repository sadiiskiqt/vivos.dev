<?php

namespace Atlantis\Models\Pattern;

use Atlantis\Helpers\Cache\AtlantisCache;

class View {

  protected $data;

  public function __construct($oData) {

    $this->data = $oData;
  }

  /**
   * Function init()
   * 
   * @return string
   */
  public function init() {

    if (!is_null($this->data->view)) {

      $id = $this->data->id;

      $pattern = AtlantisCache::rememberQuery('viewInitPatt', [$id], function() use ($id) {

                return \Atlantis\Models\Pattern::with('fields')->where("id", "=", $id)->get();
              });


      //$pattern = \Atlantis\Models\Pattern::with('fields')->where("id", "=", $this->data->id)->get();
      //$response = Tools::makeAppCallFromString($this->data->url);

      $vars = array();

      $vars["text"] = $this->data->text;

      if (count($pattern) > 0) {

        foreach ($pattern as $p) {
          if (count($p->fields)) {
            foreach ($p->fields as $f) {
              $vars[$f->key] = $f->value;
            }
          }
        }
      }

      return \view('atlantis::pattern/' . $this->data->view, $vars);
    } else {

      //throw new exception
    }
  }

}
