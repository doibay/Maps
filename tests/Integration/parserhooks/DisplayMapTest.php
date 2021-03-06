<?php

namespace Maps\Test;

use DataValues\Geo\Values\LatLongValue;
use Maps\Elements\Location;

/**
 * @covers MapsDisplayMap
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class DisplayMapTest extends ParserHookTest {

	/**
	 * @see ParserHookTest::getInstance
	 * @since 2.0
	 * @return \ParserHook
	 */
	protected function getInstance() {
		return new \MapsDisplayMap();
	}

	/**
	 * @see ParserHookTest::parametersProvider
	 * @since 2.0
	 * @return array
	 */
	public function parametersProvider() {
		$paramLists = [];

		// TODO
		$paramLists[] = [ 'coordinates' => '4,2' ];

		$paramLists[] = [ 'location' => '4,2' ];

		$paramLists[] = [ 'location' => 'new york city' ];

		$paramLists[] = [
			'service' => 'googlemaps',
			'location' => 'new york city',
			'zoom' => '10',
			'minzoom' => '5',
			'maxzoom' => '7',
			'autozoom' => 'off',
		];

		return $this->arrayWrap( $paramLists );
	}

	public function testForSomeReasonPhpSegfaultsIfThereIsOneMethodLess() {
		$this->assertTrue( (bool)'This is fucking weird' );
	}

	/**
	 * @see ParserHookTest::processingProvider
	 * @since 3.0
	 * @return array
	 */
	public function processingProvider() {
		$argLists = [];

		$values = [
			'locations' => '4,2',
			'width' => '420',
			'height' => '420',
			'service' => 'openlayers',
		];

		$expected = [
			'coordinates' => [ new Location( new LatLongValue( 4, 2 ) ) ],
			'width' => '420px',
			'height' => '420px',
			'mappingservice' => 'openlayers',
		];

		$argLists[] = [ $values, $expected ];

		return $argLists;
	}

}