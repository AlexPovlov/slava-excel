<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('excel', function ($user) {
    return \Auth::check();
});

Broadcast::channel('model', function ($user) {
    return \Auth::check();
});
