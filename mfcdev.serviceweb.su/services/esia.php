<?
const EsiaParams = 'esia-params';
	const EsiaDomain = 'esia-domain';
	const EsiaClientID = 'esia-client-id';
	const EsiaCertPath = 'esia-cert-path';
	const EsiaPrivateKeyPath = 'esia-private-key-path';
	const EsiaRedirectUrl = 'esia-redirect-url';
	const EsiaPortalUrl = 'esia-portal-url';
	const EsiaPrivateKeyPass = 'esia-private-key-pass';
	const EsiaTmpPath = 'esia-tmp-path';
	const EsiaLoginUrl = 'esia-login-url';

$localConfig = array(
			'clientId' =>  EsiaClientID,
			'redirectUrl' =>  EsiaRedirectUrl,
			'portalUrl' =>  EsiaPortalUrl,
			'privateKeyPath' =>  EsiaPrivateKeyPath,
			'privateKeyPassword' =>  EsiaPrivateKeyPass,
			'certPath' =>  EsiaCertPath,
			'tmpPath' =>  EsiaTmpPath,
		);

$instance = new OpenId($localConfig);

public function getUser($code) {
		
			$instance->getToken($code);
	
		return array(
			'id' => $this->instance->oid,
			'person' => json_decode(json_encode($this->instance->getPersonInfo()), true),
		);
	}

	public function getUrl() {
		//Вызов функции из OpenId.php
		return $this->instance->getUrl();
	}
?>