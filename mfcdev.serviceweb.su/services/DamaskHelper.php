<?php

final class DamaskHelper {
	private $SoapClient = null;

	/** @var Exception */
	public $LastException = null;

	const CHANNEL_INTERNET = 1;
	const CHANNEL_OFFICE = 2;

	const DefaultLanguage = 'ru';

	public function FetchOperations() {
		$this->LastException = null;

		try {
			$client = $this->GetSoapClient();
			$operations = $client->getOperations();
		} catch (Exception $exception) {
			$this->LastException = $exception;
			return array();
		}

		if (null === $client || !empty($operations->errorCode) || empty($operations) || !is_array($operations)) {
			return array();
		}

		return $operations;
	}

	public function FetchOperationsForOffice($officeID) {
		$this->LastException = null;

		try {
			$client = $this->GetSoapClient();
			$operations = $client->getOperationsForOffice($officeID);
		} catch (Exception $exception) {
			$this->LastException = $exception;
			return array();
		}

		if (null === $client || empty($operations) || !is_array($operations)) {
			return array();
		}

		return $operations;
	}

	public function FetchAllOffices() {
		$this->LastException = null;

		try {
			$client = $this->GetSoapClient();
			$offices = $client->getOffices();
		} catch (Exception $exception) {
			$this->LastException = $exception;
			return array();
		}

		if (null === $client || !empty($offices->errorCode) || empty($offices) || !is_array($offices)) {
			return array();
		}

		return $offices;
	}

	public function FetchTreeOperationsPerOffice($officeID) {
		$this->LastException = null;

		try {
			$client = $this->GetSoapClient();
			$tree = $client->GetTree($officeID);
		} catch (Exception $exception) {
			$this->LastException = $exception;
			return array();
		}

		if (null === $client || !empty($tree->errorCode) || empty($tree->groups) || !is_array($tree->groups)) {
			return array();
		}

		return $tree;
	}

	public function FetchOfficesForOperation($operationID) {
		$this->LastException = null;

		try {
			$client = $this->GetSoapClient();
			$offices = $client->getOfficesForOperation($operationID);
		} catch (Exception $exception) {
			$this->LastException = $exception;
			return array();
		}

		if (!is_array($offices)) {
			return array();
		}

		for ($i = 0, $max = count($offices); $i < $max; $i++) {
			$offices[$i]->Address = $offices[$i]->Name[0]->text;
			unset($offices[$i]->Name);
		}

		return $offices;
	}

	public function FetchFreeDates($operationID, $officeID) {
		$this->LastException = null;

		try {
			$client = $this->GetSoapClient();
			$dates = $client->getFreeDates($officeID, array($operationID), self::CHANNEL_INTERNET);
		} catch (Exception $exception) {
			$this->LastException = $exception;
			return array();
		}

		if (!is_array($dates)) {
			return array();
		}

		return $dates;
	}

	public function FetchFreeTimes($operationID, $officeID, $date) {
		$this->LastException = null;

		try {
			$client = $this->GetSoapClient();
			$times = $client->getIntervals($officeID, array($operationID), $date, self::CHANNEL_INTERNET);
		} catch (Exception $exception) {
			$this->LastException = $exception;
			return array();
		}

		if (!isset($times->errorCode) || $times->errorCode > 0 || !is_array($times->combinations)) {
			return array();
		}

		return $times;
	}

	public function ReserveTime($operationID, $officeID, $date, $time) {
		$this->LastException = null;

		$soapAliases = new CSOAPOperationStart();
		$soapAliases->id = $operationID;
		$soapAliases->start = $time;

		try {
			$client = $this->GetSoapClient();
			$reserve = $client->reserveTime($officeID, array($soapAliases), $date, self::CHANNEL_INTERNET, self::DefaultLanguage);
		} catch (Exception $exception) {
			$this->LastException = $exception;
			return '';
		}

		if (!isset($reserve->ErrorCode) || $reserve->ErrorCode > 0 || empty($reserve->reserveCode)) {
			return '';
		}

		return $reserve->reserveCode;
	}

	public function ActivateTime($userName, $officeID, $date, $time, $reserveCode, $addressExt, $phone) {
		$this->LastException = null;

		$soapClient = new CSOAPClient();
		$soapClient->Name = $userName;
		$soapClient->Date = $date;
		$soapClient->Time = $time;

		$phone = preg_replace('/([^\d])/', '', $phone);
		if (!empty($addressExt)) {
			$soapClient->AInfo = json_encode(array('phone' => $phone, 'comments' => array('21' => $addressExt)));
		} else if (!empty($phone)) {
			$soapClient->AInfo = json_encode(array('phone' => $phone));
		}

		try {
			$client = $this->GetSoapClient();
			$activate = $client->activateTime($officeID, $soapClient, $reserveCode);
		} catch (Exception $exception) {
			$this->LastException = $exception;
			return '';
		}

		if (!isset($activate->ErrorCode) || $activate->ErrorCode > 0 || empty($activate->ActivateCode)) {
			return '';
		}

		return $activate->ActivateCode;
	}

	public function Activate($activateCode) {
		$this->LastException = null;

		try {
			$client = $this->GetSoapClient();
			$activateStatus = $client->Activate($activateCode);
		} catch (Exception $exception) {
			$this->LastException = $exception;
			return 1;
		}

		if (0 !== $activateStatus && 1 !== $activateStatus && 2 !== $activateStatus) {
			return 1;
		}

		return $activateStatus;
	}

	public function DeleteTicket($officeID, $date, $pin) {
		$this->LastException = null;

		try {
			$client = $this->GetSoapClient();
			$deleteStatus = $client->Delete($officeID, $date, $pin);
		} catch (Exception $exception) {
			$this->LastException = $exception;
			return 1;
		}

		return $deleteStatus;
	}

	/**
	 * @return SoapClient
	 * @throws Exception
	 */
	private function GetSoapClient() {
		if (null === $this->SoapClient) {
			$this->LastException = null;

			$streamContext = stream_context_create(array(
				'http' => array(
					'timeout' => 30,
				),
				'ssl' => array(
					'verify_peer' => false,
					'verify_peer_name' => false,
					'allow_self_signed' => true,
				),
			));

			try {
				$this->SoapClient = new SoapClient("http://10.10.0.112/preorder_service/wsdlv2", array(
						'exceptions' => 1,
						'connection_timeout' => 15,
						'stream_context' => $streamContext,
						//'proxy_host' => '127.0.0.1', 'proxy_port' => 8888,
					)
				);
			} catch (Exception $exception) {
				$this->LastException = $exception;
			}
		}

		return $this->SoapClient;
	}
}

final class CSOAPClient {
	// имя клиента
	public $Name = '';
	// адрес электронной почты
	public $Email = '';
	// идентификатор операции (целое число)
	public $Operation_id = 0;
	// идентификатор станции
	public $Station = 0;
	// информация посетителя (объект JSON)
	public $AInfo = '{}';
	// дата в формате гггг.мм.дд
	public $Date = '';
	// время, указывается в минутах с начала дня (целое число)
	public $Time = '';
}

final class CSOAPOperationStart {
	// отметка (мин.), соответствующая началу выбранного интервала времени (целое число)
	public $start = 0;
	// идентификатор операции
	public $id = 0;
}

?>