<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

use Bitrix\Main\Loader;

Loader::includeModule('iblock');

$request = \Bitrix\Main\Context::getCurrent()->getRequest();
$server = \Bitrix\Main\Context::getCurrent()->getServer();

$iblocks = CIBlock::GetList(
    false,
    ['CODE' => ['tickets', 'messages']],
);

$iblockIds = [];
while ($iblock = $iblocks->GetNext()) {
    $iblockIds[$iblock['CODE']] = $iblock['ID'];
}

$name = htmlspecialcharsbx($request->getPost('name'));
$message = htmlspecialcharsbx($request->getPost('message'));
$files = $request->getFile('files');
$propertyEnums = CIBlockPropertyEnum::GetList(false, ["IBLOCK_ID" => $iblockIds['tickets'], 'CODE' => 'STATUS', "XML_ID" => "SENT"]);
$propertySentId = $propertyEnums->GetNext()['ID'];

global $USER;
$arPROPS = array(
    'USER' => $USER->GetID(),
    'STATUS' => $propertySentId
);

$arFields = array(
    "IBLOCK_SECTION_ID" => false, // элемент лежит в корне раздела
    "IBLOCK_ID" => $iblockIds['tickets'],
    'ACTIVE' => 'Y',
    'NAME' => $name,
    'PROPERTY_VALUES' => $arPROPS,
);



$ticket = new \CIBlockElement();
$ticketId = $ticket->Add($arFields);

$messageElement = new \CIBlockElement();

$messageProps = [
    'TICKET' => $ticketId,
     'USER' => $USER->GetID()
];
$messageId = $messageElement->Add([
    "IBLOCK_SECTION_ID" => false, // элемент лежит в корне раздела
    "IBLOCK_ID" => $iblockIds['messages'],
    'ACTIVE' => 'Y',
    'NAME' => $name,
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

LocalRedirect($arResult['LIST_PAGE_URL']);