<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\nav\NavX;
use app\web\themes\estate\assets\ThemeAsset;

ThemeAsset::register($this);
$this->registerMetaTag(['Keywords' => '']);
$this->registerMetaTag(['description' => '']);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>"/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body class="stretched">
        <?php $this->beginBody() ?>
        <div id="loader">
            <div class="loader-container">
                <img src="<?= $this->theme->baseUrl; ?>/images/ws.gif" alt="" class="loader-site">
            </div>
        </div>
        <div id="wrapper">
            <div class="topbar clearfix">
                <div class="container">
                    <div class="row">
                        <div class="col-md-4 col-sm-4 col-xs-12">
                            <span class="social-icons">
                                <a class="facebook" href="#" title=""><i class="fa fa-facebook"></i></a>
                                <a class="twitter" href="#" title=""><i class="fa fa-twitter"></i></a>
                                <a class="google-plus" href="#" title=""><i class="fa fa-google-plus"></i></a>
                                <a class="pinterest" href="#" title=""><i class="fa fa-pinterest"></i></a>
                                <a class="linkedin" href="#" title=""><i class="fa fa-linkedin"></i></a>
                                <a class="instagram" href="#" title=""><i class="fa fa-instagram"></i></a>
                            </span><!-- end social -->
                        </div><!-- end right -->
                        <div class="col-md-8 col-sm-8 col-xs-12 text-right nopadding">
                            <ul class="topbar-drops list-inline">
                                <li><i class="fa fa-phone"></i> +66 42415600</li>
                                <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-globe"></i> English</a>
                                    <ul class="dropdown-menu" role="menu">
                                        <li><a href="#">English</a></li>
                                        <li><a href="#">Thai</a></li>
                                    </ul>
                                </li>
                                <li><i class="fa fa-sign-in"></i> <a href="<?= Url::to(['admin/']) ?>">Webmaster Login</a></li>
                            </ul><!-- end list-style -->
                        </div><!-- end right -->
                    </div><!-- end row -->
                </div><!-- end container -->
            </div><!-- end topbar -->

            <header class="header yamm">
                <div class="container">
                    <nav class="navbar navbar-default">
                        <div class="navbar-header">
                            <button type="button" data-toggle="collapse" data-target="#navbar-collapse-2" class="navbar-toggle">
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </button>
                            <a href="index.html" class="navbar-brand"><img src="<?= $this->theme->baseUrl; ?>/images/logo.png" alt="WS Estate"></a>
                        </div><!-- end navbar-header -->
                        <div id="navbar-collapse-2" class="navbar-collapse collapse">
                            <?php
                            $_l = Yii::$app->language;
                            if ($_l == 'th_TH') {
                                $type = 1;
                            } else if ($_l == 'en_EN') {
                                $type = 2;
                            } else if ($_l == 'lo_LO') {
                                $type = 3;
                            } else if ($_l == 'vi_VI') {
                                $type = 4;
                            } else {
                                $type = 1;
                            }
                            $menuItems = app\models\Menus::listMenus(0, 0, $type);
                            echo NavX::widget([
                                'encodeLabels' => false,
                                'options'=>['class'=>'nav navbar-nav navbar-left pull-right'],
                                'items' => $menuItems,
                            ]);
                            ?>

<!--                            <ul class="nav navbar-nav navbar-right">
                                <li><a class="btn btn-success" href="list-property.html">Submit Property <i class="fa fa-chevron-circle-right"></i></a></li>
                            </ul>-->
                        </div>
                    </nav>
                </div>
            </header><!-- end header -->

            <section class="section grey">
                <div class="container">
                    <div class="row">
                        <div id="content" class="col-md-8 col-sm-8 col-xs-12">
                            <?= $content ?>
                        </div><!-- end page-wrapper -->

                        <div id="sidebar" class="col-md-4 col-sm-4 col-xs-12">
                            <div class="widget clearfix">
                                <div class="widget-title">
                                    <h3><i class="icon-mail"></i> Newsletter</h3>
                                    <hr>
                                </div><!-- end widget-title -->
                                <div class="newsletter_form clearfix"> 
                                    <p>Enjoy our newsletter to stay updated with the latest news on WS Estate.</p>  
                                    <form class="form-inline" role="form">
                                        <div class="form-group">
                                            <label class="sr-only">Subscribe to Newsletter</label>
                                            <input type="text" class="form-control" placeholder="Enter your email">
                                        </div>
                                        <button type="submit" class="btn btn-primary">GO</button>
                                    </form>
                                    <span class="social-icons">
                                        <a href="#" title=""><i class="fa fa-facebook"></i></a>
                                        <a href="#" title=""><i class="fa fa-twitter"></i></a>
                                        <a href="#" title=""><i class="fa fa-google-plus"></i></a>
                                        <a href="#" title=""><i class="fa fa-pinterest"></i></a>
                                        <a href="#" title=""><i class="fa fa-youtube"></i></a>
                                        <a href="#" title=""><i class="fa fa-yelp"></i></a>
                                        <a href="#" title=""><i class="fa fa-linkedin"></i></a>
                                    </span><!-- end social -->
                                </div><!-- end blog_categories --> 
                            </div><!-- end widget --><br/>

                            <div class="widget clearfix">
                                <div class="widget-title">
                                    <h3><i class="icon-cart"></i> Twitter Stream</h3>
                                    <hr>
                                </div><!-- end widget-title -->
                                <div class="twitter-widget clearfix">   
                                    <ul class="twitter-posts">
                                        <li><a href="#">New year bundle realease! <small>2 min ago - @envato</small></a></li>
                                        <li><a href="#">Pasha going to 1000+ sales! <small>21 min ago - @templatevisual</small></a></li>
                                        <li><a href="#">Please follow us on Envato! <small>1 hrs ago - @envato</small></a></li>
                                    </ul><!-- end recent -->
                                </div><!-- end blog_categories --> 
                            </div><!-- end widget --><br/>

                            <div class="widget clearfix">
                                <div class="widget-title">
                                    <h3><i class="icon-cart"></i> From Gallery</h3>
                                    <hr>
                                </div><!-- end widget-title -->
                                <div class="instagram-widget clearfix">   
                                    <ul>
                                        <li><a href="#"><img src="<?= $this->theme->baseUrl; ?>/upload/recent_01.png" alt=""></a></li>
                                        <li><a href="#"><img src="<?= $this->theme->baseUrl; ?>/upload/recent_02.png" alt=""></a></li>
                                        <li><a href="#"><img src="<?= $this->theme->baseUrl; ?>/upload/recent_03.png" alt=""></a></li>
                                        <li><a href="#"><img src="<?= $this->theme->baseUrl; ?>/upload/recent_04.png" alt=""></a></li>
                                        <li><a href="#"><img src="<?= $this->theme->baseUrl; ?>/upload/recent_05.png" alt=""></a></li>
                                        <li><a href="#"><img src="<?= $this->theme->baseUrl; ?>/upload/recent_06.png" alt=""></a></li>
                                        <li><a href="#"><img src="<?= $this->theme->baseUrl; ?>/upload/recent_07.png" alt=""></a></li>
                                        <li><a href="#"><img src="<?= $this->theme->baseUrl; ?>/upload/recent_08.png" alt=""></a></li>
                                        <li><a href="#"><img src="<?= $this->theme->baseUrl; ?>/upload/recent_01.png" alt=""></a></li>
                                    </ul><!-- end recent -->
                                </div><!-- end blog_categories --> 
                            </div><!-- end widget --><br/>

                            <div class="widget clearfix">
                                <div class="widget-title">
                                    <h3><i class="icon-cart"></i> Categories</h3>
                                    <hr>
                                </div><!-- end widget-title -->
                                <div class="blog_categories clearfix">   
                                    <ul class="nav nav-pills nav-stacked">  
                                        <li><a href="#">Real Estate</a></li>
                                        <li><a href="#">Human Lifes</a></li>
                                        <li><a href="#">Next Projects</a></li>
                                        <li><a href="#">Guess You Like</a></li>
                                        <li><a href="#">Cleranca</a></li>
                                    </ul>
                                </div><!-- end blog_categories --> 
                            </div><!-- end widget -->
                        </div><!-- end sidebar -->
                    </div><!-- end row -->
                </div><!-- end container -->
            </section><!-- end section -->

            <footer class="section footer">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                            <div class="widget clearfix">
                                <div class="widget-title">
                                    <h3><i class="icon-mail"></i> Newsletter</h3>
                                    <hr>
                                </div><!-- end widget-title -->
                                <div class="newsletter_form clearfix"> 
                                    <p>Enjoy our newsletter to stay updated with the latest news on WS Estate.</p>  
                                    <form class="form-inline" role="form">
                                        <div class="form-group">
                                            <label for="newsletter_input" class="sr-only">Subscribe to Newsletter</label>
                                            <input type="text" class="form-control" id="newsletter_input" placeholder="Enter your email">
                                        </div>
                                        <button type="submit" class="btn btn-primary">GO</button>
                                    </form>
                                    <span class="social-icons">
                                        <a href="#" title=""><i class="fa fa-facebook"></i></a>
                                        <a href="#" title=""><i class="fa fa-twitter"></i></a>
                                        <a href="#" title=""><i class="fa fa-google-plus"></i></a>
                                        <a href="#" title=""><i class="fa fa-pinterest"></i></a>
                                        <a href="#" title=""><i class="fa fa-youtube"></i></a>
                                        <a href="#" title=""><i class="fa fa-yelp"></i></a>
                                        <a href="#" title=""><i class="fa fa-linkedin"></i></a>
                                    </span><!-- end social -->
                                </div><!-- end blog_categories --> 
                            </div><!-- end widget -->
                        </div><!-- end col -->

                        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                            <div class="widget clearfix">
                                <div class="widget-title">
                                    <h3><i class="icon-cart"></i> Twitter Stream</h3>
                                    <hr>
                                </div><!-- end widget-title -->
                                <div class="twitter-widget clearfix">   
                                    <ul class="twitter-posts">
                                        <li><a href="#">New year bundle realease! <small>2 min ago - @envato</small></a></li>
                                        <li><a href="#">Pasha going to 1000+ sales! <small>21 min ago - @templatevisual</small></a></li>
                                        <li><a href="#">Please follow us on Envato! <small>1 hrs ago - @envato</small></a></li>
                                    </ul><!-- end recent -->
                                </div><!-- end blog_categories --> 
                            </div><!-- end widget -->
                        </div><!-- end col -->

                        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                            <div class="widget clearfix">
                                <div class="widget-title">
                                    <h3><i class="icon-cart"></i> From Gallery</h3>
                                    <hr>
                                </div><!-- end widget-title -->
                                <div class="instagram-widget clearfix">   
                                    <ul>
                                        <li><a href="#"><img src="upload/recent_01.png" alt=""></a></li>
                                        <li><a href="#"><img src="upload/recent_02.png" alt=""></a></li>
                                        <li><a href="#"><img src="upload/recent_03.png" alt=""></a></li>
                                        <li><a href="#"><img src="upload/recent_04.png" alt=""></a></li>
                                        <li><a href="#"><img src="upload/recent_05.png" alt=""></a></li>
                                        <li><a href="#"><img src="upload/recent_06.png" alt=""></a></li>
                                        <li><a href="#"><img src="upload/recent_07.png" alt=""></a></li>
                                        <li><a href="#"><img src="upload/recent_08.png" alt=""></a></li>
                                        <li><a href="#"><img src="upload/recent_01.png" alt=""></a></li>
                                    </ul><!-- end recent -->
                                </div><!-- end blog_categories --> 
                            </div><!-- end widget -->
                        </div><!-- end col -->

                        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                            <div class="widget clearfix">
                                <div class="widget-title">
                                    <h3><i class="icon-cart"></i> Categories</h3>
                                    <hr>
                                </div><!-- end widget-title -->
                                <div class="blog_categories clearfix">   
                                    <ul>  
                                        <li><a href="#">Real Estate <span>(21)</span></a></li>
                                        <li><a href="#">Human Lifes <span>(12)</span></a></li>
                                        <li><a href="#">Next Projects <span>(33)</span></a></li>
                                        <li><a href="#">Guess You Like <span>(15)</span></a></li>
                                        <li><a href="#">Cleranca <span>(11)</span></a></li>
                                    </ul>
                                </div><!-- end blog_categories --> 
                            </div><!-- end widget -->
                        </div><!-- end col --> 
                    </div><!-- end row -->
                </div><!-- end container -->
            </footer><!-- end footer -->

        </div>
        <?php $this->endBody() ?>
    </body>
</html>

<?php $this->endPage() ?>

<?php header('location: http://www.nongkhaidiscovery.com/site/index'); ?>

