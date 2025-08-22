<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class SmsApiServiceLibyana
{

	private $url;
	private $Http;

	public function __construct()
	{
		$this->setApiProperties();
	}

	public function sendSms(String $phone, $verification)
	{
		return $this->get([
			'username' => 'ldjsender',
			'password' => 'ldj@321',
			'from' => '10157',
			'to' => '218' . $phone,
			'text' => 'رمز التحقق: ' . $verification,
			'charset' => 'utf-8',
			'coding' => 2,
		]);
	}

		public function sendSmsWithMessage(String $phone, $message){
		return $this->get([
			'username' => 'ldjsender',
			'password' => 'ldj@321',
			'from' => '10157',
			// 'to' => '218' . $phone,
			'to' => '218' . $phone,
			'text' => $message,
			'charset' => 'utf-8',
			'coding' => 2,
		]);
	}
	
	public function sendSmsMedication(String $phone, $verification)
	{
		return $this->get([
			'username' => 'ldjsender',
			'password' => 'ldj@321',
			'from' => '10157',
			'to' => '218' . $phone,
			'text' => 'رمز التحقق   : ' . $verification,
			'charset' => 'utf-8',
			'coding' => 2,
		]);
	}

	public function sendSmsreg(String $phone, $regnumber)
	{
		return $this->get([
			'username' => 'ldjsender',
			'password' => 'ldj@321',
			'from' => '10157',
			'to' => '218' . $phone,
			// 'text' =>  'لقد تم قبول طلبك في منظومة التأمين الصحي العام تحت رقم:  ' . $regnumber . '       ' .   route('patientlogin'),

			'text' =>  'لقد تم قبول طلبك في منظومة التأمين الصحي العام تحت رقم:  ' . $regnumber . '       ' .   route('query', encrypt($regnumber)),
			'charset' => 'utf-8',
			'coding' => 2,
		]);
	}

	// drmsg
	public function sendSmsdr(String $phone, $regnumber)
	{
		return $this->get([
			'username' => 'ldjsende',
			'password' => 'ldj@321',
			'from' => '10157',
			'to' => '218' . $phone,
			'text' => '  لقد تم تاكيد الدواء من قبل الدكتور تحت رقم:' . $regnumber . ' ' . route('query', encrypt($regnumber)),
			'charset' => 'utf-8',
			'coding' => 2,
		]);
	}

	public function sendSmappointment(String $phone, $date, $time, $dr)
	{
		return $this->get([
			'username' => 'ldjsender',
			'password' => 'ldj@321',
			'from' => '10157',
			'to' => '218' . $phone,
			'text' => 'الرجاء الحضور لزيارة الطبيب ' . $dr . ' في تاريخ' . $date . 'الساعة' . $time,
			'charset' => 'utf-8',
			'coding' => 2,
		]);
	}

	// appoinmtment operation
	public function sendSmappointmentoper(String $phone, $date, $time, $fullname)
	//public function sendBackupInfo(String $info)
	{
		return $this->get([
			'username' => 'ldjsender',
			'password' => 'ldj@321',
			'from' => '10157',
			'to' => '218' . $phone,
			'text' => ' السيد- السيدة' .  $fullname  . 'الرجاء  الحضور لزيارة الطبيب في تاريخ ' . $date . 'الساعة' . $time,
			//'to' => '218924931737',
			//'text' => $info,
			'charset' => 'utf-8',
			'coding' => 2,
		]);
	}


	public function sendSmsHandingoutcards(String $phone, $msg)
	{
		return $this->get([
			'username' => 'ldjsender',
			'password' => 'ldj@321',
			'from' => '10157',
			'to' => '218' . $phone,
			'text' => $msg,
			'charset' => 'utf-8',
			'coding' => 2,
		]);
	}
	public function sendSurgeryNotification(String $phone, $msg)
	{
		return $this->get([
			'username' => 'ldjsender',
			'password' => 'ldj@321',
			'from' => '10157',
			'to' => '218' . $phone,
			'text' =>  $msg,
			'charset' => 'utf-8',
			'coding' => 2,
		]);
	}
	private function setApiProperties()
	{
		$this->url = "10.5.5.217:8089/cgi-bin/sendsms";
		// $this->url = "http://156.38.58.24:8089/cgi-bin/sendsms";
		// $this->url = "http://41.208.73.61:8030/api/bank/customersms";
	}



	private function get(array $parameters)
	{
		// return Http::get($this->url, [
		// 	$parameters,

		// ]);
		return Http::get($this->url, $parameters);
	}
}
