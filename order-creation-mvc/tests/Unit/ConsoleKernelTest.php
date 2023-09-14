<?php

use Illuminate\Console\Scheduling\Schedule;

uses(\Tests\TestCase::class);

test('it should  schedule app:notify-marcher-ingredient-is-low every 5 minutes', function () {
    $schedule = app(Schedule::class);

    $events = collect($schedule->events())->filter(function (Illuminate\Console\Scheduling\Event $event) {
        return stripos($event->command, 'app:notify-marcher-ingredient-is-low');
    });

    if ($events->count() != 1) {
        $this->fail('console command not registered');
    }

    $everyFiveMinuteCronExpression = '*/5 * * * *';

    expect($events->first()->expression)->toEqual($everyFiveMinuteCronExpression);
});
