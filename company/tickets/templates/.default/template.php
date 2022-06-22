<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
?>

<content>
    <div class="tickets">
        <div class="tickets-row thead thead-light active">
            <div class="p-2">ID</div>
            <div class="p-2">Тема заявки</div>
            <div class="p-2">Дата заявки</div>
            <div class="p-2">Статус</div>
        </div>

        <div>
            <? foreach ($arResult['ITEMS'] as $arItem): ?>
                <a href="<?= $arItem['DETAIL_PAGE_URL'] ?>" class="tickets-row">
                    <div class="ticket-id p-2"><span class="ticket-id-icon">№ </span><?= $arItem['ID'] ?></div>
                    <div class="ticket-name p-2"><?= $arItem['NAME'] ?></div>
                    <div class="ticket-date p-2"><?= $arItem['DATE_CREATE'] ?></div>
                    <div class="ticket-status-wrapper p-1">
                        <div class="p-1 bg-success ticket-status"><?= $arItem['PROPERTY_STATUS_VALUE'] ?></div>
                    </div>
                </a>
            <? endforeach; ?>
        </div>
    </div>
</content>