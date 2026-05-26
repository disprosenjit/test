<?php

use App\Providers\AppServiceProvider;
use Plugins\PluginLoader;

return array_merge([
    AppServiceProvider::class,
], PluginLoader::loadPaymentPlugins());
