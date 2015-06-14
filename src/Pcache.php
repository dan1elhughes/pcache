<?php namespace xes;
class Pcache {
	private $predis;

	public function __construct(\Predis\Client $predis) {
		$this->predis = $predis;
	}

	public function get($name, $expiry, callable $callback) {
		if (!$this->predis->exists($name)) {
			$this->predis->set($name, $callback());
			$this->predis->expire($name, $this->randomise($expiry));
		}

		return $this->predis->get($name);
	}

	private function randomise($time) {
		return $time + (mt_rand(-$time/10, $time/10));
	}
}
?>
