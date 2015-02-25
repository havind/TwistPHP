<?php
	/**
	 * This file is part of TwistPHP.
	 *
	 * TwistPHP is free software: you can redistribute it and/or modify
	 * it under the terms of the GNU General Public License as published by
	 * the Free Software Foundation, either version 3 of the License, or
	 * (at your option) any later version.
	 *
	 * TwistPHP is distributed in the hope that it will be useful,
	 * but WITHOUT ANY WARRANTY; without even the implied warranty of
	 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	 * GNU General Public License for more details.
	 *
	 * You should have received a copy of the GNU General Public License
	 * along with TwistPHP.  If not, see <http://www.gnu.org/licenses/>.
	 *
	 * @author     Shadow Technologies Ltd. <contact@shadow-technologies.co.uk>
	 * @license    https://www.gnu.org/licenses/gpl.html LGPL License
	 * @link       https://twistphp.com
	 *
	 */

	namespace Twist\Core\Classes;

	/**
	 * Debugging the framework and its modules, functionality to access debug data can be found here. Data will only be present if Debugging is enabled in your settings.
	 */
	final class Debug{

		protected $resTemplate = null;
		public $arrDebugLog = array();

		public function __construct(){

		}

		/**
		 * Log some debug data
		 * @param $strSystem
		 * @param $strType
		 * @param $mxdData
		 */
		public function log($strSystem,$strType,$mxdData){

			if(!array_key_exists($strSystem,$this->arrDebugLog)){
				$this->arrDebugLog[$strSystem] = array();
			}

			if(!array_key_exists($strType,$this->arrDebugLog[$strSystem])){
				$this->arrDebugLog[$strSystem][$strType] = array();
			}

			$this->arrDebugLog[$strSystem][$strType][] = $mxdData;
		}

		public function window($arrCurrentRoute){

			//print_r($this->arrDebugLog);

			$this->resTemplate = \Twist::Template('CoreDebug');
			$this->resTemplate->setTemplatesDirectory( sprintf('%sdebug/',DIR_FRAMEWORK_VIEWS));

			$arrTags = array(
				'errors' => '',
				'database' => '',
				'views' => '',
				'stats' => '',
				'cache' => ''
			);

			foreach($this->arrDebugLog['Error']['php'] as $arrEachItem){
				$arrTags['errors'] .= $this->resTemplate->build('components/php-error.tpl',$arrEachItem);
			}

			foreach($this->arrDebugLog['Database']['queries'] as $arrEachItem){
				$arrTags['database'] .= $this->resTemplate->build('components/database-query.tpl',$arrEachItem);
			}

			foreach($this->arrDebugLog['View']['usage'] as $arrEachItem){
				$arrTags['views'] .= sprintf("<tr><td>%s</td><td>%s</td><td>%s</td></tr>",$arrEachItem['instance'],$arrEachItem['file'],implode("<br>",$arrEachItem['tags']));
			}

			$arrTags['route_current'] = print_r($arrCurrentRoute,true);

			/**
			 * Process the stats timer bar graph
			 * @todo tidy up masivley and made a function in Timer
			 */
			\Twist::Timer('TwistPageLoad')->stop();
			$arrTimer = \Twist::Timer('TwistPageLoad')->results();

			$intTotalTime = $arrTimer['end']-$arrTimer['start'];
			$intOnePercent = $intTotalTime/100;
			$intCurrentPercentage = 0;
			$intPreviousTime = 0;
			$intColourCounter = 0;

			$arrColours = array('#2277aa','#34AA4B','#A2AA36','#AA4250','#6644AA');

			$arrTags['stats'] .= '<div class="timer">';

			foreach($arrTimer['log'] as $strKey => $intTime){

				$arrTags['stats'] .= sprintf('<span style="width:%d%%; background-color: %s;" title="Time taken: %s">%s</span>',
					($intTime/$intOnePercent)-$intCurrentPercentage,
					$arrColours[$intColourCounter%count($arrColours)],
					$intTime-$intPreviousTime,
					$strKey
				);

				$intCurrentPercentage += ($intTime/$intOnePercent)-$intCurrentPercentage;
				$intPreviousTime = $intTime;
				$intColourCounter++;
			}

			$arrTags['stats'] .= sprintf('<span style="width:%d%%; background-color: %s;" title="Time taken: %s">%s</span>',
				ceil(100-$intCurrentPercentage),
				$arrColours[$intColourCounter%count($arrColours)],
				$intTotalTime-$intPreviousTime,
				'Page Load'
			);

			$arrTags['stats'] .= '</div> Execution Time: '.$intTotalTime;

			return $this->resTemplate->build('_base.tpl',$arrTags);
		}

	}