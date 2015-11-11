<?php

/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yii\grid;

use Yii;
use Closure;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * ActionColumn is a column for the [[GridView]] widget that displays buttons for viewing and manipulating the items.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class CMSColumn extends Column {

    public $controller;
    public $template = '{published} {up} {down}';
    public $buttons = [];
    public $min = 1;
    public $max = 10;
    public $urlCreator;

    public function init() {
        parent::init();
        $this->initDefaultButtons();
    }

    protected function initDefaultButtons() {
        if (!isset($this->buttons['published'])) {
            $this->buttons['published'] = function ($model, $key, $index, $column) {
                /** @var PublishedColumn $column */
                $key1 = array();
                $key1['id'] = $key;
                $key1['action'] = 'published';
                $key1['page'] = $_REQUEST['page'];
                $url = $column->createUrl($model, $key1, $index, 'index');
                if ($model->published == 1 || $model['published'] == 1) {
                    $_icon = "glyphicon-ok";
                } else {
                    $_icon = "glyphicon-remove";
                }
                return Html::a('<span class="glyphicon ' . $_icon . '"></span>', $url, [
                            'title' => Yii::t('yii', 'Published'),
                            'data-pjax' => "w0",
                ]);
            };
        }
        if (!isset($this->buttons['up'])) {
            $this->buttons['up'] = function ($model, $key, $index, $column) {
                /** @var PublishedColumn $column */
                $key1 = array();
                $key1['action'] = 'order';
                $key1['id'] = $key;
                $key1['ordering'] = $model['ordering'];
                $key1['direction'] = 'up';
                if ($_REQUEST['page'])
                    $key1['page'] = $_REQUEST['page'];

                $this->min = (isset($model['min'])) ? $model['min'] : '';
                if (!$this->min) {
                    $this->min = $model->orderMin($model->langs, $model->cid);
                }
                if ($model['ordering'] == $this->min) {
                    return "&nbsp;&nbsp;&nbsp;&nbsp;";
                } else {
                    $url = $column->createUrl($model, $key1, $index, 'index');
                    return Html::a('<span class="glyphicon glyphicon-arrow-up"></span>', $url, [
                                'title' => Yii::t('yii', 'OrderUp'),
                                //'data-method' => 'post',
                                'data-pjax' => 'w0',
                    ]);
                }
            };
        }
        if (!isset($this->buttons['down'])) {
            $this->buttons['down'] = function ($model, $key, $index, $column) {
                /** @var PublishedColumn $column */
                $key1 = array();
                $key1['action'] = 'order';
                $key1['id'] = $key;
                $key1['ordering'] = $model['ordering'];
                $key1['direction'] = 'down';
                if ($_REQUEST['page'])
                    $key1['page'] = $_REQUEST['page'];

                $this->max = (isset($model['max'])) ? $model['max'] : '';
                if (!$this->max) {
                    $this->max = $model->orderMax($model->langs, $model->cid);
                }
                if ($model['ordering'] == $this->max) {
                    return "&nbsp;&nbsp;&nbsp;&nbsp;";
                } else {
                    $url = $column->createUrl($model, $key1, $index, 'index');
                    return Html::a('<span class="glyphicon glyphicon-arrow-down"></span>', $url, [
                                'title' => Yii::t('yii', 'OrderDown'),
                                //'data-method' => 'post',
                                'data-pjax' => "w0",
                    ]);
                }
            };
        }
    }

    /**
     * @param \yii\db\ActiveRecord $model
     * @param mixed $key the key associated with the data model
     * @param integer $index
     * @param string $action
     * @return string
     */
    public function createUrl($model, $key, $index, $action) {
        if ($this->urlCreator instanceof Closure) {
            return call_user_func($this->urlCreator, $model, $key, $index, $action);
        } else {
            $params = is_array($key) ? $key : ['id' => $key];
            $params[0] = $this->controller ? $this->controller . '/' . $action : $action;

            return Url::toRoute($params);
        }
    }

    /**
     * @inheritdoc
     */
    protected function renderDataCellContent($model, $key, $index) {
        return preg_replace_callback('/\\{(\w+)\\}/', function ($matches) use ($model, $key, $index) {
            $name = $matches[1];
            if (isset($this->buttons[$name])) {
                return call_user_func($this->buttons[$name], $model, $key, $index, $this);
            } else {
                return '';
            }
        }, $this->template);
    }

}
