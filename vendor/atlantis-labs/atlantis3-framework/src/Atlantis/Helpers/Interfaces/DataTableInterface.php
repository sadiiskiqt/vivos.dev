<?php

namespace Atlantis\Helpers\Interfaces;

interface DataTableInterface {

  public function tableClass();

  public function columns();

  public function bulkActions();

  public function getData(\Illuminate\Http\Request $request);
}
