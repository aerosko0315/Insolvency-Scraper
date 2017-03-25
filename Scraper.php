<?php

class Scraper {
	public $result;
	public $url;

	public function __construct($url) {
		$this->url = $url;

		if (!empty($this->url)) {
			$curl = curl_init($this->url);

			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_VERBOSE, 1);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

			$this->result = curl_exec($curl);

			if(curl_errno($curl)) {
				$this->result = 'Scraping Failed' . curl_error($curl);
			}

			curl_close($curl);

		} else {
			throw new InvalidArgumentException('Invalid or empty URL!');
		}
	}

	public function findAll($regex = '') {
		$output = array();

		if (!empty($regex) && preg_match_all($regex, $this->result, $list) ) {
			$output = $list[1];
		}
		else {
			$output = $this->result;
		}

		return $output;
	}

	public function findSingle($regex = '') {
		if (!empty($regex) && preg_match_all($regex, $this->result, $list) ) {
			$output = $list[1][0];
		}
		else {
			$output = $this->result;
		}

		return $output;
	}

}
