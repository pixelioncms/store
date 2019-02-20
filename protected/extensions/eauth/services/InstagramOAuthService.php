<?php
/**
 * YandexOAuthService class file.
 *
 * Register application: https://oauth.yandex.ru/client/my
 *
 * @author Maxim Zemskov <nodge@yandex.ru>
 * @link http://github.com/Nodge/yii-eauth/
 * @license http://www.opensource.org/licenses/bsd-license.php
 */

require_once dirname(dirname(__FILE__)) . '/EOAuth2Service.php';

/**
 * Yandex OAuth provider class.
 *
 * @package application.extensions.eauth.services
 */
class InstagramOAuthService extends EOAuth2Service {

	protected $name = 'instagram_oauth';
	protected $title = 'Instagram';
	protected $type = 'OAuth';
	protected $jsArguments = array('popup' => array('width' => 500, 'height' => 450));

	protected $client_id = '';
	protected $client_secret = '';
	protected $scope = 'basic'; //basic, public_content
	protected $providerOptions = array(
		'authorize' => 'https://instagram.com/oauth/authorize',
		'access_token' => 'https://api.instagram.com/oauth/access_token',
	);
	protected $fields = '';

	protected function fetchAttributes() {
		$info = (array) $this->makeSignedRequest('https://instagram.com/oauth/authorize');

		$this->attributes['id'] = $info['id'];
		$this->attributes['name'] = $info['username'];
		//$this->attributes['login'] = $info['display_name'];
		//$this->attributes['email'] = $info['emails'][0];
		//$this->attributes['email'] = $info['default_email'];
		//$this->attributes['gender'] = ($info['sex'] == 'male') ? 'M' : 'F';
	}

	protected function getCodeUrl($redirect_uri) {
		$url = parent::getCodeUrl($redirect_uri);
		//if (isset($_GET['js'])) {
		//	$url .= '&display=popup';
		//}
		return $url;
	}

	protected function getTokenUrl($code) {
		return $this->providerOptions['access_token'];
	}

	protected function getAccessToken($code) {

        $server = Yii::app()->request->getHostInfo();
        $path = Yii::app()->request->getUrl();
        $redirect_uri = $server . $path;

		$params = array(
			'grant_type' => 'authorization_code',
			'code' => $code,
			'client_id' => $this->client_id,
            'client_secret' => $this->client_secret,
            'redirect_uri' => 'https://store.pixelion.com.ua/users/login?service=instagram',
		);
		return $this->makeRequest($this->getTokenUrl($code), array('data' => $params));
	}

	/**
	 * Save access token to the session.
	 *
	 * @param stdClass $token access token array.
	 */
	protected function saveAccessToken($token) {
		$this->setState('auth_token', $token->access_token);
		$this->setState('expires', time() + (isset($token->expires_in) ? $token->expires_in : 365 * 86400) - 60);
		$this->access_token = $token->access_token;
	}

	/**
	 * Returns the protected resource.
	 *
	 * @param string $url url to request.
	 * @param array $options HTTP request options. Keys: query, data, referer.
	 * @param boolean $parseJson Whether to parse response in json format.
	 * @return string the response.
	 * @see makeRequest
	 */
	public function makeSignedRequest($url, $options = array(), $parseJson = true) {
		if (!$this->getIsAuthenticated()) {
			throw new CHttpException(401, 'Unable to complete the authentication because the required data was not received.');
		}

		$options['query']['oauth_token'] = $this->access_token;

		$result = $this->makeRequest($url, $options);
		return $result;
	}
}