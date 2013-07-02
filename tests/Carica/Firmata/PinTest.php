<?php

namespace Carica\Firmata {

  include_once(__DIR__.'/Bootstrap.php');

  class PinTest extends \PHPUnit_Framework_TestCase {

    /*
     * @covers Carica\Firmata\Pin::__construct
     * @covers Carica\Firmata\Pin::attachEvents
     */
    public function testConstructor() {
      $board = $this->getBoardFixture();
      $pin = new Pin($board, 12, array(PIN_STATE_OUTPUT));
      $this->assertSame($board, $pin->board);
      $this->assertEquals(12, $pin->pin);
      $this->assertEquals(array(PIN_STATE_OUTPUT), $pin->supports);
    }

    /**
     * @covers Carica\Firmata\Pin::__isset
     * @dataProvider providePinProperties
     */
    public function testPropertyIsset($propertyName) {
      $pin = new Pin($this->getBoardFixture(), 12, array(PIN_STATE_OUTPUT));
      $this->assertTrue(isset($pin->{$propertyName}));
    }

    /**
     * @covers Carica\Firmata\Pin::__isset
     */
    public function testPropertyIssetWithInvalidPropertyExpectingFalse() {
      $pin = new Pin($this->getBoardFixture(), 12, array(PIN_STATE_OUTPUT));
      $this->assertFalse(isset($pin->INVALID_PROPERTY));
    }

    /*
     * @covers Carica\Firmata\Pin::__set
     */
    public function testSetInvalidPropertyExpectingException() {
      $pin = new Pin($this->getBoardFixture(), 12, array(PIN_STATE_OUTPUT));
      $this->setExpectedException('LogicException');
      $pin->INVALID_PROPERTY = 'trigger';
    }

    /*
     * @covers Carica\Firmata\Pin::__get
     */
    public function testGetInvalidPropertyExpectingException() {
      $pin = new Pin($this->getBoardFixture(), 12, array(PIN_STATE_OUTPUT));
      $this->setExpectedException('LogicException');
      $dummy = $pin->INVALID_PROPERTY;
    }

    /*
     * @covers Carica\Firmata\Pin::__get
     */
    public function testGetBoard() {
      $board = $this->getBoardFixture();
      $pin = new Pin($board, 12, array(PIN_STATE_OUTPUT));
      $this->assertSame($board, $pin->board);
    }

    /*
     * @covers Carica\Firmata\Pin::__set
     */
    public function testSetBoardExpectingException() {
      $board = $this->getBoardFixture();
      $pin = new Pin($board, 12, array(PIN_STATE_OUTPUT));
      $this->setExpectedException('LogicException');
      $pin->board = $board;
    }

    /*
     * @covers Carica\Firmata\Pin::__get
     */
    public function testGetPin() {
      $pin = new Pin($this->getBoardFixture(), 12, array(PIN_STATE_OUTPUT));
      $this->assertEquals(12, $pin->pin);
    }

    /*
     * @covers Carica\Firmata\Pin::__set
     */
    public function testSetPinExpectingException() {
      $pin = new Pin($this->getBoardFixture(), 12, array(PIN_STATE_OUTPUT));
      $this->setExpectedException('LogicException');
      $pin->pin = 13;
    }

    /*
     * @covers Carica\Firmata\Pin::__get
     */
    public function testGetSupports() {
      $pin = new Pin($this->getBoardFixture(), 12, array(PIN_STATE_OUTPUT, PIN_STATE_ANALOG));
      $this->assertEquals(array(PIN_STATE_OUTPUT, PIN_STATE_ANALOG), $pin->supports);
    }

    /*
     * @covers Carica\Firmata\Pin::__set
     */
    public function testSetSupportsExpectingException() {
      $pin = new Pin($this->getBoardFixture(), 12, array(PIN_STATE_OUTPUT));
      $this->setExpectedException('LogicException');
      $pin->supports = array();
    }

    /*
     * @covers Carica\Firmata\Pin::__get
     */
    public function testGetMode() {
      $pin = new Pin($this->getBoardFixture(), 12, array(PIN_STATE_OUTPUT, PIN_STATE_ANALOG));
      $this->assertEquals(array(PIN_STATE_OUTPUT, PIN_STATE_ANALOG), $pin->supports);
    }

    /*
     * @covers Carica\Firmata\Pin::__set
     * @covers Carica\Firmata\Pin::__get
     * @covers Carica\Firmata\Pin::setMode
     */
    public function testSetMode() {
      $board = $this->getBoardFixture();
      $board
        ->expects($this->once())
        ->method('pinMode')
        ->with(12, PIN_STATE_OUTPUT);

      $pin = new Pin($board, 12, array(PIN_STATE_OUTPUT, PIN_STATE_ANALOG));
      $pin->mode = PIN_STATE_OUTPUT;
      $this->assertEquals(PIN_STATE_OUTPUT, $pin->mode);
    }

    /*
     * @covers Carica\Firmata\Pin::__set
     * @covers Carica\Firmata\Pin::__get
     * @covers Carica\Firmata\Pin::setMode
     */
    public function testSetModeTwoTimeOnlySentOneTime() {
      $board = $this->getBoardFixture();
      $board
        ->expects($this->once())
        ->method('pinMode')
        ->with(12, PIN_STATE_ANALOG);

      $pin = new Pin($board, 12, array(PIN_STATE_OUTPUT, PIN_STATE_ANALOG));
      $pin->mode = PIN_STATE_ANALOG;
      $pin->mode = PIN_STATE_ANALOG;
      $this->assertEquals(PIN_STATE_ANALOG, $pin->mode);
    }

    /*
     * @covers Carica\Firmata\Pin::__set
     * @covers Carica\Firmata\Pin::setMode
     */
    public function testSetModeWithUnsupportedModeExpectingException() {
      $board = $this->getBoardFixture();
      $board
        ->expects($this->never())
        ->method('pinMode');

      $pin = new Pin($board, 12, array(PIN_STATE_OUTPUT));
      $this->setExpectedException('\Carica\Firmata\Exception\UnsupportedMode');
      $pin->mode = PIN_STATE_ANALOG;
    }

    /*
     * @covers Carica\Firmata\Pin::__set
     * @covers Carica\Firmata\Pin::setDigital
     */
    public function testSetAnalogValue() {
      $board = $this->getBoardFixture();
      $board
        ->expects($this->once())
        ->method('analogWrite')
        ->with(12, 128);

      $pin = new Pin($board, 12, array(PIN_STATE_OUTPUT, PIN_STATE_ANALOG));
      $pin->analog = 128;
      $pin->analog = 128;
      $this->assertEquals(128, $pin->analog);
      $this->assertEquals(128, $pin->value);
    }

    /*
     * @covers Carica\Firmata\Pin::__set
     * @covers Carica\Firmata\Pin::setDigital
     */
    public function testSetDigitalValue() {
      $board = $this->getBoardFixture();
      $board
        ->expects($this->once())
        ->method('digitalWrite')
        ->with(12, DIGITAL_HIGH);

      $pin = new Pin($board, 12, array(PIN_STATE_OUTPUT, PIN_STATE_ANALOG));
      $pin->digital = TRUE;
      $pin->digital = TRUE;
      $this->assertTrue($pin->digital);
      $this->assertEquals(DIGITAL_HIGH, $pin->value);
    }

      /*
     * @covers Carica\Firmata\Pin
     */
    public function testEventPinState() {
      $board = new Board($this->getMock('Carica\Io\Stream'));
      $pin = new Pin($board, 12, array(PIN_STATE_OUTPUT, PIN_STATE_ANALOG));
      $board->events()->emit('pin-state-12', PIN_STATE_ANALOG, 23);
      $this->assertEquals(PIN_STATE_ANALOG, $pin->mode);
      $this->assertEquals(23, $pin->analog);
    }

    /*
     * @covers Carica\Firmata\Pin
     */
    public function testEventAnalogRead() {
      $board = new Board($this->getMock('Carica\Io\Stream'));
      $pin = new Pin($board, 12, array(PIN_STATE_ANALOG));
      $board->events()->emit('analog-read-12', 23);
      $this->assertEquals(23, $pin->analog);
    }

    /*
     * @covers Carica\Firmata\Pin
     */
    public function testEventDigitalRead() {
      $board = new Board($this->getMock('Carica\Io\Stream'));
      $pin = new Pin($board, 12, array(PIN_STATE_ANALOG));
      $board->events()->emit('digital-read-12', TRUE);
      $this->assertTrue($pin->digital);
    }

    /*
     * @covers Carica\Firmata\Pin
     */
    public function testSupportsExpectingTrue() {
      $board = $this->getBoardFixture();
      $pin = new Pin($board, 12, array(PIN_STATE_OUTPUT, PIN_STATE_ANALOG));
      $this->assertTrue(
        $pin->supports(PIN_STATE_ANALOG)
      );
    }

    /*
     * @covers Carica\Firmata\Pin
     */
    public function testSupportsExpectingFalse() {
      $board = $this->getBoardFixture();
      $pin = new Pin($board, 12, array(PIN_STATE_OUTPUT, PIN_STATE_ANALOG));
      $this->assertFalse(
        $pin->supports(PIN_STATE_PWM)
      );
    }

    /*****************
     * Fixtures
     *****************/

    private function getBoardFixture() {
      $board = $this
        ->getMockBuilder('Carica\Firmata\Board')
        ->disableOriginalConstructor()
        ->getMock();
      $board
        ->expects($this->any())
        ->method('events')
        ->will($this->returnValue($this->getMock('Carica\Io\Event\Emitter')));
      return $board;
    }

    /*****************
     * Data Provider
     *****************/

    public static function providePinProperties() {
      return array(
        array('board'),
        array('pin'),
        array('supports'),
        array('mode'),
        array('value'),
        array('digital'),
        array('analog')
      );
    }
  }
}