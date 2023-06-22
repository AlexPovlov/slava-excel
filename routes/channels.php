<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('excel', function () {
    return \Auth::check();
});
