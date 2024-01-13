<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

?>
<li>Дата запроса: <?=date('d/m/Y H:i:s');?></li>
</ul>
<a href="<?=$_SERVER["REQUEST_URI"]?>">Обновить</a>