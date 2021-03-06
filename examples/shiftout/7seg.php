<?php
$board = require(__DIR__.'/../bootstrap.php');

use Carica\Io;
use Carica\Firmata;

$loop = Io\Event\Loop\Factory::get();

$board
  ->activate()
  ->done(
    function () use ($board, $loop) {
      echo "Firmata ".$board->version." active\n";

      $latchPin = $board->pins[8];
      $clockPin = $board->pins[12];
      $dataPin = $board->pins[11];
      $latchPin->mode = Firmata\Pin::MODE_OUTPUT;
      $clockPin->mode = Firmata\Pin::MODE_OUTPUT;
      $dataPin->mode = Firmata\Pin::MODE_OUTPUT;

      $numbers = [
        0x3F, 0x06, 0x5B, 0x4F, 0x66, 0x6D, 0x7D, 0x07, 0x7F, 0x6F
      ];

      $loop->setInterval(
        function () use ($board, $latchPin, $clockPin, $dataPin, $numbers) {
          static $number = 0;

          echo $number, "\n";

          $latchPin->digital = FALSE;
          $board->shiftOut(
            $dataPin->pin, $clockPin->pin, 0xFF ^ $numbers[$number]
          );
          $latchPin->digital = TRUE;

          if (++$number > 9) {
            $number = 0;
          }
        },
        1000
      );
    }
  )
  ->fail(
    function ($error) {
      echo $error."\n";
    }
  );

if ($board->isActive()) {
  $loop->run();
}

