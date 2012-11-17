<?php

namespace Maps\Test;

/**
 * Tests for the Maps\Distance class.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @since 2.0
 *
 * @ingroup Maps
 * @ingroup Test
 *
 * @group Maps
 * @group ParserHook
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class DistanceTest extends ParserHookTest {

	/**
	 * @see ParserHookTest::getInstance
	 * @since 2.0
	 * @return \ParserHook
	 */
	protected function getInstance() {
		return new \MapsDistance();
	}

	/**
	 * @since 0.3
	 * @var array
	 */
	protected static $distances = array(
		'42' => 42,
		'42m' => 42,
		'42 m' => 42,
		'42 km' => 42000,
		'4.2 km' => 4200,
		'4.2 m' => 4.2,
	);

	/**
	 * @see ParserHookTest::parametersProvider
	 * @since 2.0
	 * @return array
	 */
	public function parametersProvider() {
		$paramLists = array();

		foreach ( array_keys( self::$distances ) as $distance ) {
			$paramLists[] = array( 'distance' => $distance );
		}

		return $this->arrayWrap( $paramLists );
	}

	/**
	 * @see ParserHookTest::processingProvider
	 * @since 0.3
	 * @return array
	 */
	public function processingProvider() {
		$definitions = \ParamDefinition::getCleanDefinitions( $this->getInstance()->getParamDefinitions() );
		$argLists = array();

		foreach ( self::$distances as $input => $output ) {
			$values = array(
				'distance' => $input,
			);

			$expected = array(
				'distance' => $output,
				'unit' => $definitions['unit']->getDefault(),
				'decimals' => $definitions['decimals']->getDefault(),
			);

			$argLists[] = array( $values, $expected );
		}

		$values = array(
			'distance' => '42m',
			'unit' => 'km',
			'decimals' => '1',
		);

		$expected = array(
			'distance' => 42,
			'unit' => 'km',
			'decimals' => 1,
		);

		$argLists[] = array( $values, $expected );

		$values = array(
			'distance' => '42m',
			'unit' => '~=[,,_,,]:3',
			'decimals' => 'foobar',
		);

		$expected = array(
			'distance' => 42,
			'unit' => $definitions['unit']->getDefault(),
			'decimals' => $definitions['decimals']->getDefault(),
		);

		$argLists[] = array( $values, $expected );

		return $argLists;
	}

}