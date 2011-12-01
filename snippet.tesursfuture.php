<?php
/**
 * @package resursfuture
 * 
 * **/
$parents = (!empty($parents) || $parents === '0') ? explode(',', $parents) : array($modx->resource->get('id'));
$limit = $modx->getOption('limit',$scriptProperties,1);
$resTpl = $modx->getOption('resTpl',$scriptProperties,'resTpl');
$includeTVs = $modx->getOption('includeTVs',$scriptProperties,false);
$processTVs = $modx->getOption('processTVs',$scriptProperties,false);
$tvPrefix = isset($tvPrefix) ? $tvPrefix : 'tv.';
$bud_dat = strtotime("now");
$c = $modx->newQuery('modResource');
$c->where(array('parent:IN' => $parents,'pub_date:>=' => $bud_dat,'deleted' => 0));
$c->sortby('pub_date','ASC');
$c->limit(1);
$resources = $modx->getIterator('modResource',$c);
foreach ($resources as $resource) {
  $tvs = array();
    if (!empty($includeTVs)) {
        $templateVars =& $resource->getMany('TemplateVars');
        foreach ($templateVars as $tvId => $templateVar) {
            $tvs[$tvPrefix . $templateVar->get('name')] = !empty($processTVs) ? $templateVar->renderOutput($resource->get('id')) : $templateVar->get('value');
        }
    }
  $resursArray = array_merge($resource->toArray(),$tvs);
  $out .= $modx->getChunk($resTpl,$resursArray);
}
return $out;

?>