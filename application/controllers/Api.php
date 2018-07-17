<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {

	public $curl;
	public $id;
	public $base_url = 'http://api.jakarta.go.id/v1/';
	public const API_KEY = 'API_KEY';

	public function __construct()
	{
		parent::__construct();
		$this->curl = curl_init();
		$this->id = $this->input->get('id');
	}

	public function getRumahSakitUmum()
	{
		curl_setopt_array($this->curl, [
			CURLOPT_URL => $this->base_url . "rumahsakitumum" . $this->checkId($this->id),
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_HTTPHEADER => [
				"Authorization: " . self::API_KEY
			],
		]);

		$response = curl_exec($this->curl);
		$err = curl_error($this->curl);

		curl_close($this->curl);

		if ($err) {
			echo "cURL Error #: " . $err;

		} else {
			echo $response;
		}
	}
	
	public function getRumahSakitKhusus()
	{
		curl_setopt_array($this->curl, [
			CURLOPT_URL => $this->base_url . "rumahsakitkhusus" . $this->checkId($this->id),
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_HTTPHEADER => [
				"Authorization: " . self::API_KEY
			],
		]);

		$response = curl_exec($this->curl);
		$err = curl_error($this->curl);

		curl_close($this->curl);

		if ($err) {
			echo "cURL Error #: " . $err;

		} else {
			echo $response;
		}	
	}
	
	public function getPuskesmas()
	{
		curl_setopt_array($this->curl, [
			CURLOPT_URL => $this->base_url . "puskesmas" . $this->checkId($this->id),
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_HTTPHEADER => [
				"Authorization: " . self::API_KEY
			],
		]);

		$response = curl_exec($this->curl);
		$err = curl_error($this->curl);

		curl_close($this->curl);

		if ($err) {
			echo "cURL Error #: " . $err;

		} else {
			echo $response;
		}
	}

	public function checkId($id)
	{
		if (!isset($id)) {
			return null;

		} else {
			return '/' . $id;
		}
	}
}
