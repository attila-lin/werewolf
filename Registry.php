<?php
	Class Registry {
		var $_objects = array();
		
		function set($name, &$object) {
			$this->_objects[$name] =& $object;
		}
		
		function &get($name) {
			return $this->_objects[$name];
		}

		function &getInstance() {
			static $me;
			if (is_object($me) == true) {
				return $me;
			}
			$me = new Registry;
			return $me;
		}

	}
?>