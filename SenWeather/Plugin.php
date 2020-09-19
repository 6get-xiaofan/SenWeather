<?php
/**
 * Handsome主题的心知天气插件<br/>添加心知天气到你的blog<br/>Bug反馈：<a target="_blank" href="https://support.qq.com/products/283207?">点击反馈</a>
 *
 * @package SenWeather
 * @author XiaoFans
 * @version 1.0.0
 * @link https://support.qq.com/products/283207?
 */
class SenWeather_Plugin implements Typecho_Plugin_Interface {

    public static function activate(){
        Typecho_Plugin::factory('Widget_Archive')->footer = array('SenWeather_Plugin', 'footer');
    }

    public static function deactivate(){	
	}

	public static function config(Typecho_Widget_Helper_Form $form) {
		$token = new Typecho_Widget_Helper_Form_Element_Text(
			'token',NULL,'68ddc93c-3f29-4935-ac03-d7eafe898099',
			_t('心知天气Token：'),
			_t('非必填项，可以输入自己的也可以使用我们的。'),
		);
		$form->addInput($token);
		$language = new Typecho_Widget_Helper_Form_Element_Select("language", 
			array(
			'zh-Hans' => '简体中文',
			'zh-Hant' => '繁体中文',
			'en' => '英文',
			'ja' => '日语',
			'de' => '德语',
			'fr' => '法语',
			'es' => '西班牙语',
			'pt' => '葡萄牙语',
			'hi' => '印地语（印度官方语言之一）',
			'id' => '印度尼西亚语',
			'ru' => '俄语',
			'th' => '泰语',
			'ar' => '阿拉伯语',
			'auto' => '自适应',
        ), 'zh-Hans', "语言", "显示语言选择");
        $form->addInput($language);
		$unit = new Typecho_Widget_Helper_Form_Element_Radio('unit',
            array(
                'c' => '℃ 摄氏度',
                'f' => '℉ 华氏度',
            ),'c', _t('默认单位'), _t('
                单位不同会导致温度、风速、能见度和气压的单位会发生变化，切换为华氏度风速将以mph（英里每小时）计量，能见度将会以mi（英里）计量，气压将会以[in Hg](英寸汞柱)计量。详情阅：<a href="https://docs.seniverse.com/api/start/unit.html">点击查看</a>     
            '));
        $form->addInput($unit);
		$theme = new Typecho_Widget_Helper_Form_Element_Radio('theme',
            array(
                'auto' => '随天气变化',
                'light' => '白色',
				'dark' => '黑色',
            ),'auto', _t('插件主题'), _t('
                选择插件显示主题     
            '));
        $form->addInput($theme);
		$color = new Typecho_Widget_Helper_Form_Element_Radio('color',
            array(
                'black' => '黑色',
                'white' => '白色',
            ),'black', _t('插件文本颜色'), _t('
                插件文本颜色与导航条背景色冲突后请切换颜色     
            '));
        $form->addInput($color);	
		$tips = new Typecho_Widget_Helper_Form_Element_Text(
			'tips',NULL,'如遇bug和有好的建议，请点击下方按钮反馈',
			_t('意见反馈'),
			_t('<a  href="https://support.qq.com/products/283207?"><button type="button" class="anniu">点击反馈</button></a><style>.anniu{background:#3498db;background-image:-webkit-linear-gradient(top,#3498db,#2980b9);background-image:-moz-linear-gradient(top,#3498db,#2980b9);background-image:-ms-linear-gradient(top,#3498db,#2980b9);background-image:-o-linear-gradient(top,#3498db,#2980b9);background-image:linear-gradient(to bottom,#3498db,#2980b9);-webkit-border-radius:9;-moz-border-radius:9;border-radius:9px;text-shadow:1px 1px 3px#666666;-webkit-box-shadow:0px 1px 2px#666666;-moz-box-shadow:0px 1px 2px#666666;box-shadow:0px 1px 2px#666666;font-family:Georgia;color:#ffffff;font-size:19px;padding:3px 5px 4px 6px;border:solid#1f628d 2px;text-decoration:none}.anniu:hover{background:#3cb0fd;background-image:-webkit-linear-gradient(top,#3cb0fd,#3498db);background-image:-moz-linear-gradient(top,#3cb0fd,#3498db);background-image:-ms-linear-gradient(top,#3cb0fd,#3498db);background-image:-o-linear-gradient(top,#3cb0fd,#3498db);background-image:linear-gradient(to bottom,#3cb0fd,#3498db);text-decoration:none}</style>'),
		);
		$form->addInput($tips);
    }

	public static function personalConfig(Typecho_Widget_Helper_Form $form){
	}


    public static function footer(){
	    $sensettings = Helper::options()->plugin('SenWeather');
        echo <<<HTML
<!--心知天气-->
  <style>
  .EKHJj {
	  color:$sensettings->color
  }
  </style>
  <script>
  var darkbtn = document.createElement('form');
darkbtn.innerHTML = '<div id="tp-weather-widget" class="navbar-form navbar-form-sm navbar-left shift"></div>';
var darkdiv = document.getElementById('searchUrl').parentElement;
darkdiv.insertBefore(darkbtn, document.getElementById('searchUrl'));
  </script>
  <script>
    (function(a,h,g,f,e,d,c,b){b=function(){d=h.createElement(g);c=h.getElementsByTagName(g)[0];d.src=e;d.charset="utf-8";d.async=1;c.parentNode.insertBefore(d,c)};a["SeniverseWeatherWidgetObject"]=f;a[f]||(a[f]=function(){(a[f].q=a[f].q||[]).push(arguments)});a[f].l=+new Date();if(a.attachEvent){a.attachEvent("onload",b)}else{a.addEventListener("load",b,false)}}(window,document,"script","SeniverseWeatherWidget","//cdn.sencdn.com/widget2/static/js/bundle.js?t="+parseInt((new Date().getTime() / 100000000).toString(),10)));
    window.SeniverseWeatherWidget('show', {
      flavor: "slim",
      location: "WX4FBXXFKE4F",
      geolocation: true,
      language: "$sensettings->language",
      unit: "$sensettings->unit",
      theme: "$sensettings->theme",
      token: "$sensettings->token",
      hover: "enabled",
      container: "tp-weather-widget"
    })
  </script>
<!--心知天气-->
HTML;
    }
}
