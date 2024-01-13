<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die();

use Bitrix\Main\Context,
Bitrix\Main\Type\DateTime,
Bitrix\Main\Loader,
Bitrix\Iblock,
Bitrix\Main\Entity;

$arParams["SET_TITLE_BROWSER"] = (isset($arParams["SET_TITLE_BROWSER"]) && $arParams["SET_TITLE_BROWSER"] === 'N' ? 'N' : 'Y');
$arParams["IBLOCK_TYPE"] = trim($arParams["IBLOCK_TYPE"]);
$arParams["IBLOCK_ID"] = trim($arParams["IBLOCK_ID"]);
$arParams["VIEW_RULE"] = trim($arParams["VIEW_RULE"]);

if ($arParams["VIEW_RULE"] == '')
	$arParams["VIEW_RULE"] = "0";

if ($arParams["FIELD_CODE"] == '')
	$arParams["FIELD_CODE"] = "NAME, ID";

$arParams["FIELD_CODE"] = array_map('trim', explode(',', $arParams["FIELD_CODE"]));
$arParams["FIELD_CODE"] = array_filter($arParams["FIELD_CODE"]);


$arResult = &$this->arResult;

$arParams["VIEW_RULE"] == '1' ? $arSort["ID"] = "DESC" : $arSort["RAND"] = "ASC";


//проверяем обновляется ли через ajax страница если да то обновляем содержимое.
if (Context::getCurrent()->getRequest()->isAjaxRequest()) {

	$this->AbortResultCache();

	$entity = \Bitrix\Iblock\ElementTable::getEntity();
	$query = new Entity\Query($entity);
	$query->setSelect(['ID', 'NAME']);
	$query->setFilter(['IBLOCK_ID' => $arParams["IBLOCK_ID"]]);
	if (!empty($arSort["RAND"])) {
		$query->registerRuntimeField(
			new Entity\ExpressionField('RAND', 'RAND()')
		);
	}
	$query->setOrder($arSort);
	$query->setLimit(1);
	$result = $query->exec();
	$arResult = $result->Fetch();

	$arResult["FIELDS"] = $arParams["FIELD_CODE"];
	$arResult["CACHE_DMY"] = date('d/m/Y H:i:s');

	$this->includeComponentTemplate();

} else {
	//кешируем область, время жизни 180 сек
	if ($this->StartResultCache()) {

		$entity = \Bitrix\Iblock\ElementTable::getEntity();
		$query = new Entity\Query($entity);
		$query->setSelect(['ID', 'NAME']);
		$query->setFilter(['IBLOCK_ID' => $arParams["IBLOCK_ID"]]);
		if (!empty($arSort["RAND"])) {
			$query->registerRuntimeField(
				new Entity\ExpressionField('RAND', 'RAND()')
			);
		}
		$query->setOrder($arSort);
		$query->setLimit(1);
		$result = $query->exec();
		$arResult = $result->Fetch();

		$arResult["FIELDS"] = $arParams["FIELD_CODE"];
		$arResult["CACHE_DMY"] = date('d/m/Y H:i:s');
		$this->includeComponentTemplate();
	}
}

if ($arParams["SET_TITLE_BROWSER"] == "Y")
	$APPLICATION->SetTitle($arResult["NAME"]);



