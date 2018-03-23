<?php
$resourceId = $modx->resource->get('id');
if (!$resourceId) { return ''; }

/** @var string|array $args */
$args = '';
if (!empty($scriptProperties['args'])) {
  $args = $scriptProperties['args'];
  if (strpos(ltrim($args), '{') === 0) {
    $args = $modx->fromJSON($args);
    $args = (is_array($args)) ? $args : '';

    foreach ($args as $k => $v) {
      if (is_string($k) && !trim($k) && is_string($v) && !trim($v)) {
        unset($args[$k]);
      }
    }
  }
}

$canonicalUrl = $modx->makeUrl($resourceId, '', $args, 'full');

return '<link rel="canonical" href="'. $canonicalUrl .'" />';