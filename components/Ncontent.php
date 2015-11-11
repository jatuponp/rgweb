<?php

namespace app\components;

use yii\helpers\Html;
use yii\base\Component;

class Ncontent extends Component {

    private $content;

    public function __construct($param1) {
        // ... initialization before configuration is applied

        $this->content = $param1;
    }

    public function getAllImg() {
        $img = array();
        preg_match_all('/<img\s+.*?src=[\"\']?([^\"\' >]*)[\"\']?[^>]*>/i', $this->content, $img);
        return $img;
    }    

    public function getImg() {
        $img = $this->getAllImg();
        if (!empty($img[1][0])) {
            $tn = $img[1][0];
        }
        return $tn;
    }

    public function getResizeImg($width = '100%') {
        $img = $this->getImg();
        $res = $this->resize_image($img, array('w' => 300, 'h' => 180));
        //print_r($res);
        $html = Html::img($res, ['width' => $width]);
        return $html;
    }

    public function getLimitText($limit = '300', $data = null) {
        $fulltxt = $data;
        if (!$data) {
            $img = $this->getAllImg();
            if (!empty($img[0])) {
                foreach ($img[0] as $txtimg) {
                    $fulltxt = str_replace($txtimg, "", $this->content);
                }
            } else {
                $fulltxt = $this->content;
            }
        }
        $rel1 = preg_replace("/{[^}]*}/", "", $fulltxt);
        $strip = strip_tags($rel1);
        $rel = mb_substr($strip, 0, $limit, 'UTF-8');

        return $rel;
    }
    
    public function getTextRemoveImg($data = null){
        $fulltxt = $data;
        if (!$data) {
            $img = $this->getAllImg();
            if (!empty($img[0])) {
                foreach ($img[0] as $txtimg) {
                    $this->content = str_replace($txtimg, '', $this->content);
                }
            } else {
                $fulltxt = $this->content;
            }
        }
        $rel = preg_replace("/{[^}]*}/", "", $this->content);

        return $rel;
    }

    private function resize_image($imagePath, $opts = null) {
        $imagePath = urldecode($imagePath);
        # start configuration
        $mPath = \Yii::getAlias('@webroot') . '/images/stories/';
        $cacheFolder = $mPath; # path to your cache folder, must be writeable by web server
        $remoteFolder = $cacheFolder . 'images/'; # path to the folder you wish to download remote images into

        $defaults = array('crop' => true, 'scale' => 'true', 'thumbnail' => false, 'maxOnly' => false,
            'canvas-color' => 'transparent', 'output-filename' => false,
            'cacheFolder' => $cacheFolder, 'remoteFolder' => $remoteFolder, 'quality' => 90, 'cache_http_minutes' => 20);

        $opts = array_merge($defaults, $opts);

        $cacheFolder = $opts['cacheFolder'];
        $remoteFolder = $opts['remoteFolder'];

        $path_to_convert = 'convert'; # this could be something like /usr/bin/convert or /opt/local/share/bin/convert
        ## you shouldn't need to configure anything else beyond this point

        $purl = parse_url($imagePath);
        $finfo = pathinfo($imagePath);
        $ext = $finfo['extension'];
        # check for remote image..
        if (isset($purl['scheme']) && ($purl['scheme'] == 'http' || $purl['scheme'] == 'https')):
            # grab the image, and cache it so we have something to work with..
            list($filename) = explode('?', $finfo['basename']);
            $local_filepath = $remoteFolder . $filename;
            $download_image = true;
            if (file_exists($local_filepath)):
                if (filemtime($local_filepath) < strtotime('+' . $opts['cache_http_minutes'] . ' minutes')):
                    $download_image = false;
                endif;
            endif;
            if ($download_image == true):
                $imagePath = str_replace('http://www.nkc.kku.ac.th', 'http://localhost', $imagePath);
                $img = @file_get_contents($imagePath);
                @file_put_contents($local_filepath, $img);
            endif;
            $imagePath = $local_filepath;

        else:
            $imagePath = str_replace('/th/images/', 'D:\home\jatuphol.p\th\web/images/', $imagePath);
        endif;
        //$imagePath = \Yii::getAlias('@app') . '/..' . str_replace($_SERVER['DOCUMENT_ROOT'], '', $imagePath);
        //print_r($imagePath);

        if (file_exists($imagePath) == true):

            $newFile = $mPath . '.tmb/' . $finfo['basename'];

            if (file_exists($newFile) == false) {
                //resize to thumb
                //$chk = $mPath . $finfo['basename'];
                if (file_exists($imagePath) == true){
                    $image = \Yii::$app->image->load($imagePath);
                    $image->resize(300, 180, false);
                    $image->save($newFile);
                }
            }
            $imagePath = $newFile;


            if (file_exists($imagePath) == false):
                return 'image not found';
            endif;
        endif;

        $imagePath = str_replace('D:\home\jatuphol.p\th\web', '/th', $imagePath);
        return $imagePath;
    }

}

?>