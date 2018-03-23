<?php

$exists = $chunks = false;
$output = null;
switch ($options[xPDOTransport::PACKAGE_ACTION]) {
	case xPDOTransport::ACTION_INSTALL:
		$exists = $modx->getObject('transport.modTransportPackage', array('package_name' => 'translit'));
		if (!empty($options['attributes']['snippets'])) {
			$snippets = '<ul id="formCheckboxes" style="height:200px;overflow:auto;">';
			foreach ($options['attributes']['snippets'] as $k => $v) {
				$snippets .= '
				<li>
					<label>
						<input type="checkbox" name="update_snippets[]" value="' . $k . '" checked> ' . $k . '
					</label>
				</li>';
			}
			$snippets .= '</ul>';
		}
		break;

	case xPDOTransport::ACTION_UPGRADE:
		$exists = $modx->getObject('transport.modTransportPackage', array('package_name' => 'translit'));		
		break;

	case xPDOTransport::ACTION_UNINSTALL:
		break;
}

$output = '';

if (!$exists) {
	switch ($modx->getOption('manager_language')) {
		case 'ru':
			$output = 'Этот пакет автоматически установит следующие дополнения: <b>translit</b>.';
			break;
		default:
			$output = 'This component is automatically install <b>translit</b>.';
	}
}


if ($snippets) {
	/*
	if (!$exists) {
		$output .= '<br/><br/>';
	}
	*/

	switch ($modx->getOption('manager_language')) {
		case 'ru':
			$output .= '<br>Выберите сниппеты, которые нужно <b>установить</b>:<br/>
				<small>
					<a href="#" onclick="Ext.get(\'formCheckboxes\').select(\'input\').each(function(v) {v.dom.checked = true;});">отметить все</a> |
					<a href="#" onclick="Ext.get(\'formCheckboxes\').select(\'input\').each(function(v) {v.dom.checked = false;});">cнять отметки</a>
				</small><br>
			';
			break;
		default:
			$output .= '<br>Select snippets, which need to <b>install</b>:<br/>
				<small>
					<a href="#" onclick="Ext.get(\'formCheckboxes\').select(\'input\').each(function(v) {v.dom.checked = true;});">select all</a> |
					<a href="#" onclick="Ext.get(\'formCheckboxes\').select(\'input\').each(function(v) {v.dom.checked = false;});">deselect all</a>
				</small><br>
			';
	}

	$output .= $snippets;
}

return $output;