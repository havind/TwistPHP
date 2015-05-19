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

class BaseControllerAjax extends BaseController{

	protected $arrAjaxResponse = array();

	public function __construct(){

		//@todo Should these two options still be set by default
		$this->_ignoreUserAbort(true);
		$this->_timeout(60);

		$this->arrAjaxResponse = array(
			'status' => true,
			'message' => '',
			'data' => '',
			'debug' => array()
		);

		//@todo Should we still return loggedin and login_redirect? the old Ajax server did
	}

	/**
	 * Set the status for the Ajax response, true by default
	 * @param $blStatus
	 */
	public function _status($blStatus){
		$this->arrAjaxResponse['status'] = (is_bool($blStatus) && $blStatus);
	}

	/**
	 * Set a message to be returned to the Ajax call, can be used for an error message
	 * @param $strMessage
	 */
	public function _message($strMessage){
		$this->arrAjaxResponse['message'] = $strMessage;
	}

	//Encode the response of the Ajax output
	public function _json($mxdData){
		$this->arrAjaxResponse['debug']['route'] = (\Twist::framework()->setting('DEVELOPMENT_MODE')) ? $this->_route() : array();
		$this->arrAjaxResponse['data'] = $mxdData;
		return json_encode($this->arrAjaxResponse);
	}
}