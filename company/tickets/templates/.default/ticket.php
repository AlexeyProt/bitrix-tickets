<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<content>
    <div class="chat-history">
        <? foreach ($arResult['MESSAGES'] as $message): ?>
            <? if ($message['USER']['IS_OPERATOR']): ?>
                <div class="message">
                    <div class="operator-message">
                        <div class="inner-message rounded">
                            <div class="d-flex header-message">
                                <div class="user-name">Оператор <?= $message['USER']['LOGIN'] ?></div>
                                <div class="date-message"><?= $message['CREATED_DATE'] ?></div>
                            </div>
                            <div class="body-message"><?= $message['DETAIL_TEXT'] ?></div>
                            <div class="footer-message"></div>
                        </div>
                    </div>
                </div>
            <? else: ?>
                <div class="message">
                    <div class="user-message">
                        <div class="inner-message rounded">
                            <div class="d-flex header-message">
                                <div class="user-name">Пользователь <?= $message['USER']['LOGIN'] ?></div>
                                <div class="date-message"><?= $message['CREATED_DATE'] ?></div>
                            </div>
                            <div class="body-message"><?= $message['DETAIL_TEXT'] ?></div>
                            <div class="footer-message"></div>
                        </div>
                    </div>
                </div>
            <? endif; ?>
        <? endforeach; ?>
    </div>
</content>

<div class="form-message-input input-message">
    <form enctype="multipart/form-data" method="post" action="send_message" class="d-flex flex-column">
        <input type="hidden" value="<?= $arResult['ID'] ?>" name="id">
        <div class="form-group d-flex justify-content-left mb-3">
            <div class="form-floating message">
                <textarea name="message" class="form-control text-input" id="messageText" placeholder="Текст сообщения"
                          cols="30" rows="10" required=""></textarea>
            </div>
        </div>
        <div class="form-group d-flex justify-content-left mb-3">
            <input class="inputfile" type="file" name="files[]" multiple="" id="files">
            <div class="buttons-column">
                <a href="<?= $arResult['LIST_PAGE_URL'] ?>"
                   class="btn cancel-button message-button button-primary mb-3"><b>Отмена</b></a>
            </div>
            <? $APPLICATION->IncludeFile($this->GetFolder() . '/includes/filearea.php') ?>
        </div>
    </form>
</div>