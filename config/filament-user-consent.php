<?php

use Visualbuilder\FilamentUserConsent\Events\ConsentsUpdatedComplete;
use Visualbuilder\FilamentUserConsent\Events\ConsentUpdated;
use Visualbuilder\FilamentUserConsent\Listeners\NotifyConsentsUpdated;

return [

    //To which authenticatable models consents should be applied?
    'models' => [
        App\Models\User::class,
    ],

    'options' => [
        'App\Models\User' => 'User',
    ],

    'notify' => ['mail'],

    'routes' => [
        'prefix' => 'consent-options',
    ],

    'navigation' => [
        'sort' => 50,
        'group' => 'Content',
    ],

    'listeners' => [
        
    ],

    //send user an email with a copy of the consent after saving.
    'notify' => ['mail'],
];
