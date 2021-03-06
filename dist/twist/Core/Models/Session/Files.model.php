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

	namespace Twist\Core\Models\Session;

	class Files{

		protected $savePath;
		protected $sessionName;
		protected $maxLifetime = 0;

		public function __construct(){
			//Use files to store the session info
			$this->setHandlers();
		}

		public function __destruct(){
			//Save the session upon destruct of the handler
			session_write_close();
		}

		protected function setHandlers(){

			session_set_save_handler(
				array($this, 'open'),
				array($this, 'close'),
				array($this, 'read'),
				array($this, 'write'),
				array($this, 'destroy'),
				array($this, 'gc')
			);

			//the following prevents unexpected effects when using objects as save handlers
			register_shutdown_function('session_write_close');
		}

		public function open($savePath, $sessionName){

			$this->savePath = $savePath;
			$this->savePath = $sessionName;
			$this->maxLifetime = ini_get('session.gc_maxlifetime');

			if(!is_dir($this->savePath)){
				mkdir($this->savePath, 0777);
			}

			return true;
		}

		public function close(){
			//Select OS do not call Garbage Collection, so we will need to do it in close
			$this->gc($this->maxLifetime);
			return true;
		}

		public function read($intSessionID){

			$mxdOut = null;

			$strFile = sprintf("%s/sess_%s",$this->savePath,$intSessionID);
			$mxdOut = (string)@file_get_contents($strFile);

			return $mxdOut;
		}

		public function write($intSessionID, $mxdData){

			$blOut = false;

			$strFile = sprintf("%s/sess_%s",$this->savePath,$intSessionID);
			$blOut = (file_put_contents($strFile, $mxdData) === false) ? false : true;

			return $blOut;
		}

		public function destroy($intSessionID){

			$strFile = sprintf("%s/sess_%s",$this->savePath,$intSessionID);
			if(file_exists($strFile)){
				unlink($strFile);
			}

			return true;
		}

		public function gc($intMaxLifetime){

			foreach(glob("$this->savePath/sess_*") as $strFile){
				if(filemtime($strFile) + $intMaxLifetime < \Twist::DateTime()->time() && file_exists($strFile)){
					unlink($strFile);
				}
			}

			return true;
		}
	}