<?php

namespace Carica\Firmata\Response\Midi {

  use Carica\Firmata;

  /**
   * Class Message
   *
   * @property integer $port
   * @property integer $value
   */
  class Message extends Firmata\Response\Midi {

    /**
     * @var int
     */
    private $_port = 0;

    /**
     * @var int
     */
    private $_value = 0;

    /**
     * @param string $command
     * @param array $bytes
     */
    public function __construct($command, array $bytes) {
      parent::__construct($command, $bytes);
      $this->_port = $bytes[0] & 0x0F;
      $this->_value = $bytes[1] | ($bytes[2] << 7);
    }

    /**
     * @param string $name
     * @return int
     */
    public function __get($name) {
      switch ($name) {
      case 'port' :
        return $this->_port;
      case 'value' :
        return $this->_value;
      }
      return parent::__get($name);
    }
  }
}