<?php

$tvs = array();

$tmp = array(
	'seo_h1' => array(
		'name' => 'seo_h1',
		'caption' => 'Заголовок h1',
		'description' => '',
		'type' => 'text',
		'default_text' => '[[*pagetitle]]',
		'elements' => '',
		'input_properties' => 'a:5:{s:10:"allowBlank";s:4:"true";s:9:"maxLength";s:0:"";s:9:"minLength";s:0:"";s:5:"regex";s:0:"";s:9:"regexText";s:0:"";}',
		'category' => '1',
	),
	'seo_description' => array(
		'name' => 'seo_description',
		'caption' => 'meta description',
		'description' => 'описание страницы',
		'type' => 'text',
		'default_text' => '',
		'elements' => '',
		'input_properties' => 'a:5:{s:10:"allowBlank";s:4:"true";s:9:"maxLength";s:0:"";s:9:"minLength";s:0:"";s:5:"regex";s:0:"";s:9:"regexText";s:0:"";}',
		'category' => '1',
	),
	'seo_keywords' => array(
		'name' => 'seo_keywords',
		'caption' => 'meta keywords',
		'description' => 'ключевые слова',
		'type' => 'text',
		'default_text' => '',
		'elements' => '',
		'input_properties' => 'a:5:{s:10:"allowBlank";s:4:"true";s:9:"maxLength";s:0:"";s:9:"minLength";s:0:"";s:5:"regex";s:0:"";s:9:"regexText";s:0:"";}',
		'category' => '1',
	),
	'seo_index' => array(
		'name' => 'seo_index',
		'caption' => 'Индексировать страницу?',		
		'description' => '',
		'type' => 'checkbox',
		'default_text' => '1',
		'elements' => 'да==1',
		'input_properties' => 'a:2:{s:10:"allowBlank";s:4:"true";s:7:"columns";s:1:"1";}',
		'category' => '1',
	),
);

foreach ($tmp as $k => $v) {
	/* @avr modSnippet $snippet */
	$tv = $modx->newObject('modTemplateVar');
	$tv->fromArray(array(		
		'name' => $k,
		'caption' => @$v['caption'],
		'description' => @$v['description'],
		'type' => @$v['type'],
		'default_text' => @$v['default_text'],
		'elements' => @$v['elements'],
		'input_properties' => @$v['input_properties'],		
	), '', true, true);	
	$tvs[] = $tv;
}

unset($tmp, $properties);
return $tvs;