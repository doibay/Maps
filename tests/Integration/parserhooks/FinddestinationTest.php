<?php

namespace Maps\Test;

use DataValues\Geo\Parsers\GeoCoordinateParser;
use Maps\Elements\Location;

/**
 * @covers MapsFinddestination
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class FinddestinationTest extends ParserHookTest {

	/**
	 * @since 3.0
	 * @var string[]
	 */
	protected $locations = [
		'4,2',
		'4.2,-42',
	];

	/**
	 * @since 3.0
	 * @var array
	 */
	protected $bearings = [
		1,
		42,
		-42,
		0,
		4.2,
	];

	/**
	 * @since 3.0
	 * @var string[]
	 */
	protected $distances = [
		'42' => 42,
		'0' => 0,
		'42 m' => 42,
		'42 km' => 42000,
		'4.2 km' => 4200,
	];

	/**
	 * @see ParserHookTest::getInstance
	 * @since 2.0
	 * @return \ParserHook
	 */
	protected function getInstance() {
		return new \MapsFinddestination();
	}

	/**
	 * @see ParserHookTest::parametersProvider
	 * @since 2.0
	 * @return array
	 */
	public function parametersProvider() {
		$paramLists = [];

		$paramLists[] = [
			'location' => '4,2',
			'bearing' => '1',
			'distance' => '42 km'
		];

		return $this->arrayWrap( $paramLists );
	}

	/**
	 * @see ParserHookTest::processingProvider
	 * @since 3.0
	 * @return array
	 */
	public function processingProvider() {
		$argLists = [];

		$coordinateParser = new GeoCoordinateParser( new \ValueParsers\ParserOptions() );

		foreach ( $this->distances as $distance => $expectedDistance ) {
			foreach ( $this->bearings as $bearing ) {
				foreach ( $this->locations as $locationString ) {
					$values = [
						'distance' => (string)$distance,
						'bearing' => (string)$bearing,
						'location' => (string)$locationString,
					];

					$expected = [
						'distance' => $expectedDistance,
						'bearing' => (float)$bearing,
						'location' => new Location( $coordinateParser->parse( $locationString )->getValue() ),
					];

					$argLists[] = [ $values, $expected ];
				}
			}
		}

		return $argLists;
	}

}