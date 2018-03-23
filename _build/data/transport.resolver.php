<?php
/**
 * Resolver to set system settings
 * 
 * @package mdpafterinstall
 * @subpackage build
 */
$success= true;
$tmp = array(
	'cultureKey' => 'ru'
	,'fe_editor_lang' => 'ru'
	,'publish_default' => 1
	,'upload_maxsize' => '10485760'	
	, 'topmenu_show_descriptions' => 0
	, 'locale' => 'ru_RU.utf-8'
	, 'manager_lang_attribute' => 'ru'
	, 'manager_language' => 'ru'
	, 'automatic_alias' => 1
	, 'friendly_urls' => 1
	, 'global_duplicate_uri_check' => 0
	, 'link_tag_scheme' => 'abs'
	, 'container_suffix' => ''
	, 'friendly_urls_strict' => 1
	, 'use_alias_path' => 1
	, 'request_method_strict' => 1	
	, 'friendly_alias_realtime' => 1
	, 'friendly_alias_translit' => 'russian_yandex'
	, 'friendly_alias_restrict_chars' => 'alphanumeric'	
);
    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
        case xPDOTransport::ACTION_INSTALL:
        case xPDOTransport::ACTION_UPGRADE:
		
			$errorTemplate = $object->xpdo->newObject('modTemplate');
			$errorTemplate->fromArray(array(
					'templatename' => 'Вторичная страница',					
					'content' => '<html>
<head>
<title>[[*longtitle:default=`[[*pagetitle]]`]]</title>
[[Canonical]]
[[*seo_keywords:notempty=`<meta name="keywords" content="[[*seo_keywords]]">`]]
[[*seo_description:notempty=`<meta name="description" content="[[*seo_description]]">`]]
[[*seo_index:ne=`1`:then=`<meta name="robots" content="noindex,nofollow">`]]
</head>
<body>
[[*content]]
</body>
</html>'
				));
			$errorTemplate->save();
			$tmp['error_page'] = $errorTemplate->get('id');
			$tmp['unauthorized_page'] = $errorTemplate->get('id');
			
			$mainTemplate = $object->xpdo->getObject('modTemplate',array('id' => '1'));
			if ($mainTemplate) {
				$mainTemplate->set('templatename','Главная страница');
				$mainTemplate->set('icon','icon-home');
				$mainTemplate->set('content','<html>
<head>
<title>[[*longtitle:default=`[[*pagetitle]]`]]</title>
[[Canonical]]
[[*seo_keywords:notempty=`<meta name="keywords" content="[[*seo_keywords]]">`]]
[[*seo_description:notempty=`<meta name="description" content="[[*seo_description]]">`]]
[[*seo_index:ne=`1`:then=`<meta name="robots" content="noindex,nofollow">`]]
</head>
<body>
[[*content]]
</body>
</html>');
				$categ = $object->xpdo->getObject('modCategory',array('category' => 'SEO'));				
				$templateVars = $categ->getMany('modTemplateVar');
				foreach ($templateVars as $templateVar) {
					$tvt = $object->xpdo->getObject('modTemplateVarTemplate',array('templateid' => '1','tmplvarid' => $templateVar->get('id')));
					if (!$tvt) {
						$tvt = $object->xpdo->newObject('modTemplateVarTemplate');
						$tvt->set('templateid','1');
						$tvt->set('tmplvarid',$templateVar->get('id'));
						$tvt->save();
					}
					$tve = $object->xpdo->getObject('modTemplateVarTemplate',array('templateid' => $errorTemplate->get('id'),'tmplvarid' => $templateVar->get('id')));
					if (!$tve) {
						$tve = $object->xpdo->newObject('modTemplateVarTemplate');
						$tve->set('templateid',$errorTemplate->get('id'));
						$tve->set('tmplvarid',$templateVar->get('id'));
						$tve->save();
					}
				}				
				$mainTemplate->save();
			}
		
			$error404 = $object->xpdo->getObject('modResource',array('alias' => 'error404'));
			if (!$error404) {
				$error404 = $object->xpdo->newObject('modResource');
				$error404->fromArray(array(
					'pagetitle' => 'Страница не найдена',
					'template' => 2,
					'published' => 1,
					'hidemenu' => 1,
					'alias' => 'error404',
					'uri' => 'error404', 
					'content_type' => 1, 
					'menuindex' => 197, 
					'content' => '<p>Ошибка 404. Страница не найдена.</p>'
				));
				$error404->save();
			}
			
			$sitemap = $object->xpdo->getObject('modResource',array('alias' => 'sitemap'));
			if (!$sitemap) {
				$sitemap = $object->xpdo->newObject('modResource');
				$sitemap->fromArray(array(
					'pagetitle' => 'sitemap.xml',
					'template' => 0,
					'published' => 1,
					'hidemenu' => 1,
					'alias' => 'sitemap',
					'uri' => 'sitemap.xml', 
					'content_type' => 2, 
					'richtext' => 0, 
					'menuindex' => 198, 
					'uri_override' => 1, 
					'content' =>'[[!pdoSitemap? &checkPermissions=`list`]]'
				));
				$sitemap->save();
			}
			
			$robots = $object->xpdo->getObject('modResource',array('alias' => 'robots'));
			if (!$robots) {
				$robots_content = "User-agent: *\nDisallow: /manager/\nDisallow: /assets/components/\nAllow: /assets/uploads/\nDisallow: /core/\nDisallow: /connectors/\nDisallow: /index.php\nDisallow: /search\nDisallow: /profile/\nDisallow: *?\nHost: [[++site_url]]\nSitemap: [[++site_url]]sitemap.xml";
				$robots = $object->xpdo->newObject('modResource');
				$robots->fromArray(array(
					'pagetitle' => 'robots.txt',
					'template' => 0,
					'published' => 1,
					'hidemenu' => 1,
					'alias' => 'robots',
					'uri' => 'robots.txt',
					'content_type' => 3,
					'richtext' => 0, 
					'menuindex' => 199, 
					'uri_override' => 1, 
					'content' => $robots_content 
				));
				$robots->save();
			}
		
			foreach ($tmp as $k => $v) {
				$setting = $object->xpdo->getObject('modSystemSetting',array('key' => $k));
				if ($setting) {
					$object->xpdo->log(xPDO::LOG_LEVEL_INFO,'Attempting to set "'.$k.'" setting to "'.$v.'".');
					$setting->set('value',$v);
					$setting->save();
				}
				else {
					$setting = $object->xpdo->newObject('modSystemSetting');
					$setting->set('key',$k);
					$setting->set('value',$v);
					$setting->save();
				}
				unset($setting);
			}
			
			$contType = $object->xpdo->getObject('modContentType',array('name' => 'HTML'));
			if ($contType) {
				$contType->set('file_extensions','');
				$contType->save();
			}
			
            break;
        case xPDOTransport::ACTION_UNINSTALL:
            $success= true;
            break;
    }	
	
unset($tmp);	

return $success;