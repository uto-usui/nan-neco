<?php
class FacebookPortalApi
{

/**
 * Facebook API application ID
 *
 * @var string
 */
    public $appId;

/**
 * Facebook API application secret key
 *
 * @var string
 */
    public $appSecret;

/**
 * Facebook API access token
 *
 * @var string
 */
    protected $accessToken = null;

/**
 * Facebook URL.
 *
 * @var string
 */
    public $linkUrl = 'https://www.facebook.com';

/**
 * Facebook API URL.
 *
 * @var string
 */
    public $requestUrl = 'https://graph.facebook.com/v2.8';

/**
 * Facebook single post URL.
 *
 * @var string
 */
    public $postUrlFormat = 'https://www.facebook.com/%s/posts/%s';

/**
 * Delimiter of the post ID and page ID
 *
 * @var string
 */
    public $idDelimiter = '_';

/**
 * Facebook Default Album
 *
 * @var string
 */
    public $defaultAlbum = 'Timeline Photos';

/**
 * Default options for curl.
 *
 * @var array
 */
    public $curlOpts = array(
        CURLOPT_CONNECTTIMEOUT => 10,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 60,
        CURLOPT_HEADER => false,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => 0
    );

/**
 * Construct
 *
 * @return void
 */
    public function __construct($access_token = null)
    {
        if (!empty($access_token)) {
            $this->accessToken = $access_token;
        }
    }

/**
 * Set the Application ID.
 *
 * @param string $appId The Application ID
 * @return this
 */
    public function setConfig($config = array())
    {
        if ((!empty($config['appId'])) && (!empty($config['secret']))) {
            $this->setAppId($config['appId']);
            $this->setAppSecret($config['secret']);
        }
    }

/**
 * Set the Application ID.
 *
 * @param string $appId The Application ID
 * @return this
 */
    public function setAppId($appId)
    {
        $this->appId = $appId;
        return $this;
    }

/**
 * Set the App Secret.
 *
 * @param string $appSecret The App Secret
 * @return this
 */
    public function setAppSecret($appSecret)
    {
        $this->appSecret = $appSecret;
        return $this;
    }

/**
 * Sets the access token for api calls
 *
 * @param string $access_token an access token.
 * @return BaseFacebook
 */
    public function setAccessToken($access_token)
    {
        $this->accessToken = $access_token;
        return $this;
    }

/**
 * Facebook App アクセストークンを取得
 *
 * @param array $config Facebook application config
 * @return string
 */
    public function getAccessToken($config = array())
    {
        if (empty($config)) {
            if ($this->accessToken !== null) {
                return $this->accessToken;
            }
        } else {
            $this->setConfig($config);
        }

        $params = array(
            'client_id' => $this->appId,
            'client_secret' => $this->appSecret,
            'grant_type' => 'client_credentials',
        );
        if ($response = $this->apiRequest('/oauth/access_token', $params)) {
            if (is_array($response)) {
                $result = $response;
            } else {
                parse_str($response, $result);
            }

            if (!empty($result['access_token'])) {
                $this->setAccessToken($result['access_token']);
            }
        }
        return $this->accessToken;
    }

/**
 * Facebook の情報を取得
 *
 * @param integer $page_id Facebook Page ID
 * @return array
 */
    public function getFacebookPage($page_id = null)
    {
        if (empty($page_id)) return false;

        $path = '/' . $page_id;
        $params = array(
            'fields' => 'id,name,username,link,picture.type(small).fields(url)',
            'access_token' => $this->getAccessToken()
        );
        $response = $this->apiRequest($path, $params);
        if (!empty($response['id'])) {
            if (!empty($response['picture']['data']['url'])) {
                $response['picture'] = $response['picture']['data']['url'];
            }
            return $response;
        }
        return false;
    }

/**
 * Facebook の投稿記事を取得
 *
 * @param integer $page_id Facebook Page ID
 * @param integer $since Search of created time (timestamp)
 * @param integer $until Search of created time (timestamp)
 * @return array
 */
    public function getFacebookFeed($page_id = null, $since = null, $until = null)
    {
        if (empty($page_id)) return false;

        $path = '/' . $page_id . '/posts';
        $params = array(
            'fields' => 'id,message,created_time,type,link,object_id',
            'since' => $since,
            'until' => $until,
            'access_token' => $this->getAccessToken()
        );
        $response = $this->apiRequest($path, $params);
        if ((is_array($response)) && (!empty($response))) {
            $_data = array();
            foreach ($response['data'] as $i => $data) {
                if (empty($data['message'])) {
                    continue;
                }
                $response['data'][$i]['permalink'] = vsprintf($this->postUrlFormat, explode($this->idDelimiter, $response['data'][$i]['id']));
                $_data[] = $response['data'][$i];
            }
            $response = $_data;
        }
        return $response;
    }

/**
 * 添付画像URLを取得
 *
 * @param string $object_id Attachment ID
 * @return array|boolean
 */
    public function getPhotoUrl($object_id = null)
    {
        if (empty($object_id)) return false;

        $path = '/' . $object_id;
        $params = array(
            'fields' => 'album.fields(name,count,type),source',
            'access_token' => $this->getAccessToken()
        );
        $object = $this->apiRequest($path, $params);
        if (!empty($object['album']['id'])) {
            return array($object['source']);

/*            if ($object['album']['type'] == 'wall') {
                return array($object['source']);
            } else {
                $path = '/' . $object['album']['id'];
                $params = array(
                    'fields' => 'photos.fields(source)',
                    'access_token' => $this->getAccessToken()
                );
                $album = $this->apiRequest($path, $params);
                if (is_array($album['photos']['data'])) {
                    $photos = array();
                    foreach ($album['photos']['data'] as $photo) {
                        $photos[] = $photo['source'];
                    }
                    return $photos;
                }
            }*/
        }
        return false;
    }

/**
 * Request to Facebook API
 *
 * @param string $path Path the Request URL
 * @param array $params Parameter to request
 * @return array
 */
    public function apiRequest($path = null, $params = array())
    {
        $url = $this->requestUrl;
        if ($path) {
            $url .= $path;
        }

        if (!isset($params['locale'])) {
            $params['locale'] = 'ja_JP';
        }

        if (function_exists('curl_init')) {
            $response = $this->curlRequest($url, $params);
        } else {
            $response = $this->fileGetRequest($url, $params);
        }

        $result = json_decode($response, true);
        if (!is_array($result)) {
            return $response;
        }
        // TODO ここでエラーチェックを入れる
        if (!array_key_exists('error', $result)) {
            return $result;
        }
        return false;
    }

/**
 * Request to Facebook API, using cURL
 *
 * @param string $url Request URL
 * @param array $params Parameter to request
 * @return array
 */
    protected function curlRequest($url, $params, $ch = null)
    {
        if (!$ch) {
            $ch = curl_init();
        }

        $url = $url . '?' . http_build_query($params);
        $opts = $this->curlOpts;
        $opts[CURLOPT_URL] = $url;
        curl_setopt_array($ch, $opts);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

/**
 * Request to Facebook API, using file_get_contents
 *
 * @param string $url Request URL
 * @param array $params Parameter to request
 * @return array
 */
    protected function fileGetRequest($url, $params)
    {
        $url = $url . '?' . http_build_query($params);
        return file_get_contents($url);
    }
}
