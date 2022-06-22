<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

use Bitrix\Main\Loader;

Loader::includeModule('iblock');

$request = \Bitrix\Main\Context::getCurrent()->getRequest();

$iblocks = CIBlock::GetList(
    false,
    ['CODE' => ['tickets', 'messages']]
);

$iblockIds = [];
while ($iblock = $iblocks->GetNext()) {
    $iblockIds[$iblock['CODE']] = $iblock['ID'];
}


$ticketId = htmlspecialcharsbx($request->getPost('id'));
$message = htmlspecialcharsbx($request->getPost('message'));
$files = $request->getFile('files');
$propertyEnums = CIBlockPropertyEnum::GetList(false, array("IBLOCK_ID" => $iblockIds['tickets'], "XML_ID " => "SENT"));
$propertySentId = $propertyEnums->GetNext()['ID'];

$ticket = new \CIBlockElement();
$ticket->Update($ticketId, ['STATUS' => $propertySentId]);
$ticketResult = CIBlockElement::GetList(false, ['ID' => $ticketId], false, false,  ['ID', 'DETAIL_PAGE_URL']);

global $APPLICATION;
$ticketResult->SetUrlTemplates($APPLICATION->GetCurDir() . $arParams["SEF_URL_TEMPLATES"]['ticket']);

$arResult['DETAIL_PAGE_URL'] = $ticketResult->GetNext()['DETAIL_PAGE_URL'];

$messageElement = new \CIBlockElement();

global $USER;
$messageProps = [
    'TICKET' => $ticketId,
    'USER' => $USER->GetID()
];

$messageId = $messageElement->Add([
    "IBLOCK_SECTION_ID" => false, // элемент лежит в корне раздела
    "IBLOCK_ID" => $iblockIds['messages'],
    'ACTIVE' => 'Y',
    'NAME' => 'Сообщение',
    'PROPERTY_VALUES' => $messageProps,
    'DETAIL_TEXT' => $message
]);

$arFileBx = [];
foreach ($files['tmp_name'] as $key => $file) {
    $fileValue = CFile::MakeFileArray($file);
    $fileValue['name'] = $files['name'][$key];
    $arFileBx[] = array("VALUE" => $fileValue, "DESCRIPTION" => "");
}

if ($arFileBx) {
    CIBlockElement::SetPropertyValuesEx($messageId, $iblockIds['messages'], array('FILES' => $arFileBx));
}

LocalRedirect($arResult['DETAIL_PAGE_URL']);