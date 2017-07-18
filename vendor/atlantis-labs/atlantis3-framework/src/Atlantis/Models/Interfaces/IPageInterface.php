<?php

namespace Atlantis\Models\Interfaces;


interface IPageInterface {
  
    public function getAllPages(); 
  
    public function findPageByURL( $url, $lang );
}