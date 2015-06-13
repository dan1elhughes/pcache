<?php
class Pcache {
	private $predis;

	public function __construct(\Predis\Client $predis) {
		$this->predis = $predis;
	}

	public function get($name, $expiry, callable $callback) {
		if (!$this->predis->exists($name)) {
			$this->predis->set($name, $callback());
			$this->predis->expire($name, $expiry);
		}

		return $this->predis->get($name);
	}

	public static function expire($length) {
		$mins = 60;
		switch($length) {
			case 'short': return 0.5*$mins;
			case 'default': return 1*$mins;
			case 'long': return 5*$mins;
			default: throw new Exception("Invalid expiry: $length");
		}
	}
}
?>
