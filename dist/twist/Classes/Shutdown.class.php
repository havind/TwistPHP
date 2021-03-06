<?php

	/**
	 * TwistPHP - An open source PHP MVC framework built from the ground up.
	 * Copyright (C) 2016  Shadow Technologies Ltd.
	 *
	 * This program is free software: you can redistribute it and/or modify
	 * it under the terms of the GNU General Public License as published by
	 * the Free Software Foundation, either version 3 of the License, or
	 * (at your option) any later version.
	 *
	 * This program is distributed in the hope that it will be useful,
	 * but WITHOUT ANY WARRANTY; without even the implied warranty of
	 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	 * GNU General Public License for more details.
	 *
	 * You should have received a copy of the GNU General Public License
	 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
	 *
	 * @author     Shadow Technologies Ltd. <contact@shadow-technologies.co.uk>
	 * @license    https://www.gnu.org/licenses/gpl.html GPL License
	 * @link       https://twistphp.com
	 */

	namespace Twist\Classes;

	/**
	 * Shutdown handler to call registered functions upon completion or failure of the PHP instance.
	 * The functions are run in the order that they are registered, using shutdown functions will help to imporve page load time by completing tasks after the page has loaded.
	 * For example storing/removing cache files, updating last logged in data etc.
	 * @package Twist\Classes
	 */
	class Shutdown{

		public static $blShutdownRegistered = false;
		public static $arrCallbackEvents = array(); // array to store user callbacks

		/**
		 * Used for TwistPHP to register the master shutdown handler that will then run through and process each of the registered shutdown events.
		 * @related Twist\Core\Classes\Register
		 */
		public static function enableHandler(){
			register_shutdown_function(array(__NAMESPACE__ .'\Shutdown', 'callEvents'));
			self::$blShutdownRegistered = true;
		}

		/**
		 * Register a shutdown event, this function should be used exclusively by the \Twist::framework()->register() object.
		 * @related Twist\Core\Classes\Register
		 * @return bool
		 */
		public static function registerEvent(){

			$arrCallback = func_get_args();

			if(empty($arrCallback)){
				trigger_error('No callback passed to '.__FUNCTION__.' method', E_TWIST_ERROR);
				return false;
			}

			if(count($arrCallback[0]) != 3){
				trigger_error('Invalid callback parameters, 3 are required key,method,function when passed to the '.__FUNCTION__.' method', E_TWIST_ERROR);
				return false;
			}

			$strEventKey = $arrCallback[0][0];
			$resCallback = array($arrCallback[0][1],$arrCallback[0][2]);

			if(strstr($resCallback[0],'Twist::')){
				//Ignore the Error
			}elseif(!is_callable($resCallback)){
				trigger_error('Invalid callback passed to the '.__FUNCTION__.' method', E_TWIST_ERROR);
				return false;
			}

			//Register the Shutdown Handler if an event has been added
			if(!self::$blShutdownRegistered){
				self::enableHandler();
			}

			self::$arrCallbackEvents[$strEventKey] = $resCallback;

			return true;
		}

		/**
		 * Called upon PHP shutdown by the PHP shutdown handler to run all of the registered shutdown events one by one.
		 */
		public static function callEvents(){

			foreach(self::$arrCallbackEvents as $arrArguments){
				//$resCallbackEvent = array_shift($arrArguments);
				if(strstr($arrArguments[0],'Twist::')){
					$strPackage = str_replace('Twist::','',$arrArguments[0]);
					$strMethod = $arrArguments[1];
					\Twist::$strPackage()->$strMethod();
				}else{
					call_user_func_array($arrArguments, array());
				}
			}
		}

		/**
		 * Remove a registered even from the event list
		 * @param $strEventKey
		 */
		public static function cancelEvent($strEventKey){
			unset(self::$arrCallbackEvents[$strEventKey]);
		}

		/**
		 * Cancel/Remove all events registered by the shutdown handler
		 */
		public static function cancelEvents(){
			self::$arrCallbackEvents = array();
		}
	}