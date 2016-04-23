<?php
/**
 * GoogleOAuth2Service class file.
 *
 * Register application: https://code.google.com/apis/console/
 *
 * @author Maxim Zemskov <nodge@yandex.ru>
 * @link http://github.com/Nodge/yii2-eauth/
 * @license http://www.opensource.org/licenses/bsd-license.php
 */

namespace nodge\eauth\services;

use nodge\eauth\oauth2\Service;

/**
 * Google provider class.
 *
 * @package application.extensions.eauth.services
 */
class GoogleOAuth2Analytic extends Service
{


    /**
     * Defined scopes - More scopes are listed here:
     * https://developers.google.com/oauthplayground/
     */

    // Basic
    const SCOPE_EMAIL = 'email';
    const SCOPE_PROFILE = 'profile';

    const SCOPE_USERINFO_EMAIL = 'https://www.googleapis.com/auth/userinfo.email';
    const SCOPE_USERINFO_PROFILE = 'https://www.googleapis.com/auth/userinfo.profile';

    // Google+
    const SCOPE_GPLUS_ME = 'https://www.googleapis.com/auth/plus.me';
    const SCOPE_GPLUS_LOGIN = 'https://www.googleapis.com/auth/plus.login';

    // Google Drive
    const SCOPE_DOCUMENTSLIST = 'https://docs.google.com/feeds/';
    const SCOPE_SPREADSHEETS = 'https://spreadsheets.google.com/feeds/';
    const SCOPE_GOOGLEDRIVE = 'https://www.googleapis.com/auth/drive';
    const SCOPE_DRIVE_APPS = 'https://www.googleapis.com/auth/drive.appdata';
    const SCOPE_DRIVE_APPS_READ_ONLY = 'https://www.googleapis.com/auth/drive.apps.readonly';
    const SCOPE_GOOGLEDRIVE_FILES = 'https://www.googleapis.com/auth/drive.file';
    const SCOPE_DRIVE_METADATA_READ_ONLY = 'https://www.googleapis.com/auth/drive.metadata.readonly';
    const SCOPE_DRIVE_READ_ONLY = 'https://www.googleapis.com/auth/drive.readonly';
    const SCOPE_DRIVE_SCRIPTS = 'https://www.googleapis.com/auth/drive.scripts';

    // Adwords
    const SCOPE_ADSENSE = 'https://www.googleapis.com/auth/adsense';
    const SCOPE_ADWORDS = 'https://adwords.google.com/api/adwords/';
    const SCOPE_GAN = 'https://www.googleapis.com/auth/gan'; // google affiliate network...?

    // Google Analytics
    const SCOPE_ANALYTICS = 'https://www.googleapis.com/auth/analytics';
    const SCOPE_ANALYTICS_EDIT = 'https://www.googleapis.com/auth/analytics.edit';
    const SCOPE_ANALYTICS_MANAGE_USERS = 'https://www.googleapis.com/auth/analytics.manage.users';
    const SCOPE_ANALYTICS_READ_ONLY = 'https://www.googleapis.com/auth/analytics.readonly';

    // Other services
    const SCOPE_BOOKS = 'https://www.googleapis.com/auth/books';
    const SCOPE_BLOGGER = 'https://www.googleapis.com/auth/blogger';
    const SCOPE_CALENDAR = 'https://www.googleapis.com/auth/calendar';
    const SCOPE_CONTACT = 'https://www.google.com/m8/feeds/';
    const SCOPE_CHROMEWEBSTORE = 'https://www.googleapis.com/auth/chromewebstore.readonly';
    const SCOPE_GMAIL = 'https://mail.google.com/mail/feed/atom';
    const SCOPE_PICASAWEB = 'https://picasaweb.google.com/data/';
    const SCOPE_SITES = 'https://sites.google.com/feeds/';
    const SCOPE_URLSHORTENER = 'https://www.googleapis.com/auth/urlshortener';
    const SCOPE_WEBMASTERTOOLS = 'https://www.google.com/webmasters/tools/feeds/';
    const SCOPE_TASKS = 'https://www.googleapis.com/auth/tasks';

    // Cloud services
    const SCOPE_CLOUDSTORAGE = 'https://www.googleapis.com/auth/devstorage.read_write';
    const SCOPE_CONTENTFORSHOPPING = 'https://www.googleapis.com/auth/structuredcontent'; // what even is this
    const SCOPE_USER_PROVISIONING = 'https://apps-apis.google.com/a/feeds/user/';
    const SCOPE_GROUPS_PROVISIONING = 'https://apps-apis.google.com/a/feeds/groups/';
    const SCOPE_NICKNAME_PROVISIONING = 'https://apps-apis.google.com/a/feeds/alias/';

    // Old
    const SCOPE_ORKUT = 'https://www.googleapis.com/auth/orkut';
    const SCOPE_GOOGLELATITUDE =
        'https://www.googleapis.com/auth/latitude.all.best https://www.googleapis.com/auth/latitude.all.city';
    const SCOPE_OPENID = 'openid';

    // YouTube
    const SCOPE_YOUTUBE_GDATA = 'https://gdata.youtube.com';
    const SCOPE_YOUTUBE_ANALYTICS_MONETARY = 'https://www.googleapis.com/auth/yt-analytics-monetary.readonly';
    const SCOPE_YOUTUBE_ANALYTICS = 'https://www.googleapis.com/auth/yt-analytics.readonly';
    const SCOPE_YOUTUBE = 'https://www.googleapis.com/auth/youtube';
    const SCOPE_YOUTUBE_READ_ONLY = 'https://www.googleapis.com/auth/youtube.readonly';
    const SCOPE_YOUTUBE_UPLOAD = 'https://www.googleapis.com/auth/youtube.upload';
    const SCOPE_YOUTUBE_PATNER = 'https://www.googleapis.com/auth/youtubepartner';
    const SCOPE_YOUTUBE_PARTNER_EDIT = 'https://www.googleapis.com/auth/youtubepartner-channel-edit';

    // Google Glass
    const SCOPE_GLASS_TIMELINE = 'https://www.googleapis.com/auth/glass.timeline';
    const SCOPE_GLASS_LOCATION = 'https://www.googleapis.com/auth/glass.location';

    protected $name = 'google_oauth';
    protected $title = 'Google';
    protected $type = 'OAuth2';
    protected $jsArguments = ['popup' => ['width' => 500, 'height' => 450]];

    protected $scopes = ['https://www.googleapis.com/auth/analytics.readonly'];
    protected $providerOptions = [
        'authorize' => 'https://accounts.google.com/o/oauth2/auth',
        'access_token' => 'https://accounts.google.com/o/oauth2/token',
    ];

    protected function fetchAttributes()
    {
        //echo 111;
        //Получили список сайтов.
        $info = $this->makeSignedRequest('https://www.googleapis.com/analytics/v3/management/accountSummaries');

        $my_site = [];

        foreach($info['items'] as $site_account){
            foreach($site_account['webProperties'] as $site){
                $my_site[] = [
                    'site_url'=>$site['websiteUrl'],
                    'site_id' => $site['profiles'][0]['id'],
                ];
            }


        }
        $this->attributes['sites'] = $my_site;


        //$info = $this->makeSignedRequest('https://www.googleapis.com/analytics/v3/management/accountSummaries');
        //https://www.googleapis.com/analytics/v3/management/accounts/accountId/webproperties/webPropertyId
        /*
		foreach($my_site as $item){
            $date = [];
            $date['year'] = date('Y');
            $date['month'] = date('m');
            echo 'Site: '.$item['site_url'];
            for($i=0; $i < 12; $i++){
                $end_day =  sprintf("%04d-%02d-%02d", $date['year'], $date['month'], '1');

                $date['month'] = $date['month'] - 1;
                if($date['month'] == 0){
                    $date['month'] = 12;
                    $date['year'] = $date['year'] - 1;
                }
                $start_day = sprintf("%04d-%02d-%02d", $date['year'], $date['month'], '1');
                $info = $this->makeSignedRequest('https://www.googleapis.com/analytics/v3/data/ga?'.
                    'ids=ga:'.$item['site_id'].
                    '&start-date='.$start_day.
                    '&end-date='.$end_day.
                    '&metrics=ga:sessions,ga:pageviews');

                $sessions = $info['totalsForAllResults']['ga:sessions'];
                $pageviews = $info['totalsForAllResults']['ga:pageviews'];

                echo '

				'.$start_day.' - '.$end_day.':
				sessions: '.$sessions.'
				pageviews: '.$pageviews.'
				';

            }

        }

        print_r($date);
        print_r($my_site);
        print_r($global);
        echo '</pre>';*/
        /*$this->attributes['id'] = $info['id'];
        $this->attributes['name'] = $info['name'];

        if (!empty($info['link'])) {
            $this->attributes['url'] = $info['link'];
        }

        if (!empty($info['gender']))
            $this->attributes['gender'] = $info['gender'] == 'male' ? 'M' : 'F';

        if (!empty($info['picture']))
            $this->attributes['photo'] = $info['picture'];

        $this->attributes['first_name'] = $info['given_name']; // first name
        $this->attributes['last_name'] = $info['family_name']; // last name
        $this->attributes['email'] = $info['email'];
*/
    }

    /**
     * Returns the protected resource.
     *
     * @param string $url url to request.
     * @param array $options HTTP request options. Keys: query, data, referer.
     * @param boolean $parseResponse Whether to parse response.
     * @return mixed the response.
     */
    public function makeSignedRequest($url, $options = [], $parseResponse = true)
    {
        if (!isset($options['query']['alt'])) {
            $options['query']['alt'] = 'json';
        }
        return parent::makeSignedRequest($url, $options, $parseResponse);
    }

    /**
     * Returns the error array.
     *
     * @param array $response
     * @return array the error array with 2 keys: code and message. Should be null if no errors.
     */
    protected function fetchResponseError($response)
    {
        if (isset($response['error'])) {
            return [
                'code' => $response['error']['code'],
                'message' => $response['error']['message'],
            ];
        } else {
            return null;
        }
    }
}