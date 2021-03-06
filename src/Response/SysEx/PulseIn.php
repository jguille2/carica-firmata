<?php

namespace Carica\Firmata\Response\SysEx {

  use Carica\Firmata;

  /**
   * Class PulseIn
   *
   * @property integer $pin pin index
   * @property integer $duration pulse duration in microsecond
   */
  class PulseIn extends Firmata\Response\SysEx {

    /**
     * @var int
     */
    private $_pin = 0;

    /**
     * @var int
     */
    private $_duration = 0;

    /**
     * @param string $command
     * @param array $bytes
     */
    public function __construct($command, array $bytes) {
      parent::__construct($command, $bytes);
      $pin = unpack('C', self::decodeBytes([$bytes[1], $bytes[2]]));
      $this->_pin = $pin[1];
      $duration = unpack('N', self::decodeBytes(array_slice($bytes, 3)));
      $this->_duration = $duration[1];
    }

    public function __get($name) {
      switch ($name) {
      case 'pin' :
        return $this->_pin;
      case 'duration' :
        return $this->_duration;
      }
      return parent::__get($name);
    }
  }
}