<?php
namespace app\components;

use yii\base\Component;
use app\models\Sitecounter;

class counter extends Component {

    public function hitsCounter($module, $moduleid = null) {
        $ip = $_SERVER["REMOTE_ADDR"];
        $agent = $_SERVER["HTTP_USER_AGENT"];
        session_start();
        $session = \Yii::$app->session->getId();

        $country = $this->visitor_country();
        if (!in_array($_SERVER['HTTP_USER_AGENT'], array(
                    'facebookexternalhit/1.1 (+https://www.facebook.com/externalhit_uatext.php)',
                    'facebookexternalhit/1.1 (+http://www.facebook.com/externalhit_uatext.php)',
                    'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)',
                    'Mozilla/5.0 (compatible; bingbot/2.0; +http://www.bing.com/bingbot.htm)',
                ))) {

            $query = Sitecounter::find()->where(['module' => $module]);
            if ($moduleid) {
                $query->andWhere(['module_id' => $moduleid]);
            }
            $query->andWhere(['session_id' => $session ]);
            $chk = $query->count();
            if ($chk == 0) {
                $model = new Sitecounter();
                $model->module = $module;
                $model->module_id = $moduleid;
                $model->ip_address = $ip;
                $model->session_id = $session;
                $model->user_agent = $agent;
                $model->country = $country;
                $model->datetime = date('Y-m-d H:i:s');
                if (!$model->save()) {
                    print_r($model->getErrors());
                    exit();
                }
            }
        }
    }

    public function getHitsCounter($module, $moduleid = null) {
        $query = Sitecounter::find()->where(['module' => $module]);
        if ($moduleid) {
            $query->andWhere(['module_id' => $moduleid]);
        }
        $cnt = $query->count();
        return $cnt;
    }
    
    public function getTotalCounter() {
        $query = Sitecounter::find()->where(['module' => 'site']);
        $cnt = $query->count();
        return $cnt;
    }

    protected function visitor_country() {
        $client = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remote = $_SERVER['REMOTE_ADDR'];
        $result = "Unknown";
        if (filter_var($client, FILTER_VALIDATE_IP)) {
            $ip = $client;
        } elseif (filter_var($forward, FILTER_VALIDATE_IP)) {
            $ip = $forward;
        } else {
            $ip = $remote;
        }

        $ip_data = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip));

        if ($ip_data && $ip_data->geoplugin_countryName != null) {
            $result = $ip_data->geoplugin_countryName;
        }

        return $result;
    }

}

?>