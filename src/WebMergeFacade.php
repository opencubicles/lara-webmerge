<?php

namespace Mindastic\LaraWebmerge;

use Illuminate\Support\Facades\Facade;

class WebMergeFacade extends Facade {

  protected static function getFacadeAccessor() {
    return 'webmerge';
  }

}
