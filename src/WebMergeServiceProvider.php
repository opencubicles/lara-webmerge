<?php

namespace Mindastic\LaraWebmerge;

use Illuminate\Support\ServiceProvider;

class WebMergeServiceProvider extends ServiceProvider {

  public function register() {
    $this->app->bind('webmerge', function() {
      return new WebMerge(config('webmerge.key'), config('webmerge.secret'), config('webmerge.request_mode'));
    });
    $this->mergeConfigFrom(
        __DIR__ . '/config/config.php', 'webmerge'
    );
  }

  public function boot() {
    $this->publishes([
      __DIR__ . '/config/config.php' => config_path('webmerge.php'),
    ]);
    $this->publishes([
      __DIR__ . '/tmp' => storage_path(config('webmerge.tmpDir')),
    ], 'tmpDir');
  }

}
