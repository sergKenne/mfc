<?php

class OpenId
{
    public $clientId;
    public $redirectUrl;

    /**
     * @var callable|null
     */
    public $log = null;
    public $portalUrl = 'https://esia.gosuslugi.ru/';
    public $tokenUrl = 'aas/oauth2/te';
    public $codeUrl = 'aas/oauth2/ac';
    public $personUrl = 'rs/prns';
    public $privateKeyPath;
    public $privateKeyPassword;
    public $certPath;
    public $oid = null;

    //protected $scope = 'fullname birthdate gender email mobile id_doc snils inn';
	protected $scope = 'fullname';

    protected $clientSecret = null;
    protected $responseType = 'code';
    protected $state = null;
    protected $timestamp = null;
    protected $accessType = 'offline';
    protected $tmpPath;

    private $url = null;
    public $token = null;

    public function __construct(array $config = [])
    {
        foreach ($config as $k => $v) {
            if (property_exists($this, $k)) {
                $this->$k = $v;
            }
        }
    }

    /**
     * Return an url for authentication
     *
     * ```
     *     <a href="<?=$esia->getUrl()?>">Login</a>
     * ```
     *
     * @return string|false
     */
    public function getUrl()
    {
        $this->timestamp = $this->getTimeStamp();
        $this->state = $this->getState();

        #$message = $this->scope . $this->timestamp . $this->clientId . $this->state;
        $this->clientSecret = $this->signPKCS7($this->scope . $this->timestamp . $this->clientId . $this->state);

        if ($this->clientSecret === false) {
            return false;
        }

        $url = $this->getCodeUrl() . '?%s';

        $params = [
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'redirect_uri' => $this->redirectUrl,
            'scope' => $this->scope,
            'response_type' => $this->responseType,
            'state' => $this->state,
            'access_type' => $this->accessType,
            'timestamp' => $this->timestamp,
        ];

        $request = http_build_query($params);

        $this->url = sprintf($url, $request);

        return $this->url;
    }

    /**
     * Return an url for request to get an access token
     *
     * @return string
     */
    public function getTokenUrl()
    {
        return $this->portalUrl . $this->tokenUrl;
    }

    /**
     * Return an url for request to get an authorization code
     *
     * @return string
     */
    public function getCodeUrl()
    {
        return $this->portalUrl . $this->codeUrl;
    }

    /**
     * Return an url for request person information
     *
     * @return string
     */
    public function getPersonUrl()
    {
        return $this->portalUrl . $this->personUrl;
    }

    /**
     * Method collect a token with given code
     *
     * @param $code
     * @return false|string
     * @throws SignFailException
     */
    public function getToken($code)
    {
        $this->timestamp = $this->getTimeStamp();
        $this->state = $this->getState();

        #$message = ($this->scope . $this->timestamp . $this->clientId . $this->state);
        $clientSecret = $this->signPKCS7($this->scope . $this->timestamp . $this->clientId . $this->state);

        if ($clientSecret === false) {
            throw new SignFailException(SignFailException::CODE_SIGN_FAIL);
        }

        $request = [
            'client_id' => $this->clientId,
            'code' => $code,
            'grant_type' => 'authorization_code',
            'client_secret' => $clientSecret,
            'state' => $this->state,
            'redirect_uri' => $this->redirectUrl,
            'scope' => $this->scope,
            'timestamp' => $this->timestamp,
            'token_type' => 'Bearer',
            'refresh_token' => $this->state,
        ];

        $curl = curl_init();

        if ($curl === false) {
            return false;
        }

        $options = [
            CURLOPT_URL => $this->getTokenUrl(),
            CURLOPT_POSTFIELDS => http_build_query($request),
            CURLOPT_POST => true,
            CURLOPT_SSL_VERIFYPEER => 0,
	#    CURLOPT_SSL_VERIFYHOST => false,
	#    CURLOPT_CAINFO => '/etc/ssl/certs/gosuslugi-ru-chain.pem',
            CURLOPT_RETURNTRANSFER => true,
	    CURLOPT_VERBOSE => 1,
	    CURLOPT_STDERR => fopen("/tmp/errorcab.txt", 'w'),
        ];

        curl_setopt_array($curl, $options);

        $result = curl_exec($curl);
        $result = json_decode($result);

        $this->writeLog(print_r($result, true));

        $this->token = $result->access_token;

        # get object id from token
        $chunks = explode('.', $this->token);
        $payload = json_decode($this->base64UrlSafeDecode($chunks[1]));
        $this->oid = $payload->{'urn:esia:sbj_id'};

        $this->writeLog(var_export($payload, true));

        return $this->token;
    }


    /**
     * Algorithm for singing message which
     * will be send in client_secret param
     *
     * @param string $message
     * @return string
     * @throws SignFailException
     */
    public function signPKCS7($message)
    {
        $curl = curl_init();

        if ($curl === false) {
            return false;
        }

        $data = [
            'content' => base64_encode($message),
            'certId' => 'mfc66_2020',
        ];
        $options = [
            CURLOPT_URL => 'http://10.10.0.55:8083/api/v1/signature/sign',
            CURLOPT_HTTPHEADER => array('Content-Type: application/json'),
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_VERBOSE => 1,
            CURLOPT_STDERR => fopen("/tmp/errorcab2012.txt", 'w'),
        ];

        curl_setopt_array($curl, $options);

        $result = curl_exec($curl);
		$result = json_decode($result);

		return $this->urlSafe($result->pksc7);
    }

    /**
     * Fetch person info from current person
     *
     * You must collect token person before
     * calling this method
     *
     * @throws \Exception
     * @return null|\stdClass
     */
    public function getPersonInfo()
    {
        $url = $this->personUrl . '/' . $this->oid;

        $request = $this->buildRequest();
        return $request->call($url);
    }

    /**
     * Fetch contact info about current person
     *
     * You must collect token person before
     * calling this method
     *
     * @throws \Exception
     * @return null|\stdClass
     */
    public function getContactInfo()
    {
        $url = $this->personUrl . '/' . $this->oid . '/ctts';
        $request = $this->buildRequest();
        $result = $request->call($url);

        if ($result && $result->size > 0) {
            return $this->collectArrayElements($result->elements);
        }

        return $result;
    }


    /**
     * Fetch address from current person
     *
     * You must collect token person before
     * calling this method
     *
     * @throws \Exception
     * @return null|\stdClass
     */
    public function getAddressInfo()
    {
        $url = $this->personUrl . '/' . $this->oid . '/addrs';
        $request = $this->buildRequest();
        $result = $request->call($url);

        if ($result && $result->size > 0) {
            return $this->collectArrayElements($result->elements);
        }

        return null;
    }

	public function getDocInfo()
	{
		$url = $this->personUrl . '/' . $this->oid . '/docs';

		$request = $this->buildRequest();

		$result = $request->call($url);

		if ($result && $result->size > 0) {
			$contacts = $this->collectArrayElements($result->elements);
			return $contacts;
		}

		return $result;
	}

    /**
     * This method can iterate on each element
     * and fetch entities from esia by url
     *
     *
     * @param $elements array of urls
     * @return array
     * @throws \Exception
     */
    protected function collectArrayElements($elements)
    {
        $result = [];
        foreach ($elements as $element) {

            $request = $this->buildRequest();
            $source = $request->call($element, true);

            if ($source) {
                array_push($result, $source);
            }

        }

        return $result;
    }

    /**
     * @return Request
     * @throws RequestFailException
     */
    public function buildRequest()
    {
        if (!$this->token) {
            throw new RequestFailException(RequestFailException::CODE_TOKEN_IS_EMPTY);
        }

        return new Request($this->portalUrl, $this->token);
    }

    /**
     * @throws SignFailException
     */
    protected function checkFilesExists()
    {
        if (! file_exists($this->certPath)) {
            throw new SignFailException(SignFailException::CODE_NO_SUCH_CERT_FILE);
        }
        if (! file_exists($this->privateKeyPath)) {
            throw new SignFailException(SignFailException::CODE_NO_SUCH_KEY_FILE);
        }
        if (! file_exists($this->tmpPath)) {
            throw new SignFailException(SignFailException::CODE_NO_TEMP_DIRECTORY);
        }
    }

    /**
     * @return string
     */
    private function getTimeStamp()
    {
        return date("Y.m.d H:i:s O");
    }


    /**
     * Generate state with uuid
     *
     * @return string
     */
    private function getState()
    {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }

    /**
     * Url safe for base64
     *
     * @param string $string
     * @return string
     */
    private function urlSafe($string)
    {
        return rtrim(strtr(trim($string), '+/', '-_'), '=');
    }


    /**
     * Url safe for base64
     *
     * @param string $string
     * @return string
     */
    private function base64UrlSafeDecode($string)
    {
        $base64 = strtr($string, '-_', '+/');

        return base64_decode($base64);
    }

    /**
     * Write log
     *
     * @param string $message
     */
    private function writeLog($message)
    {
        $log = $this->log;

        if (is_callable($log)) {
            $log($message);
        }
    }
    
    /**
     * Generate random unique string
     *
     * @return string
     */
    private function getRandomString()
    {
        return md5(uniqid(mt_rand(), true));
    }
}

