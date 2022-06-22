<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var CAllMain $APPLICATION */

$APPLICATION->setTitle('Подача заявки');
?>
<content>
    <form enctype="multipart/form-data" class="request-form" action="send_ticket" method="post">
        <div class="form-group mb-3">
            <div class="form-floating message">
                <input type="text" class="form-control" id="subjectText" name="name" placeholder="Тема запроса" required="">
                <label for="subjectText"><img class="icon" src="<?=$this->GetFolder()?>/images/pencil.svg" alt="" height="16px">Тема запроса</label>
            </div>
            <img src="<?=$this->GetFolder()?>/images/info.svg" class="info" alt="" data-toggle="tooltip" title="помощь по форме здесь">
        </div>
        <div class="request-files-list-wrapper">
            <div class="request-files rounded">
                <div class="request-files-title">
                    <label for="files">
                        <img src="<?=$this->GetFolder()?>/images/staple.svg" class="icon request-files-title-icon" height="40px" alt="">
                    </label>
                    <span>Прикрепленные файлы</span>
                </div>
                <ul class="request-files-list">

                </ul>
            </div>
        </div>
        <div class="form-group mb-3 request-message-wrapper">
            <div class="form-floating message">
                <textarea class="form-control text-input" id="messageText" name="message" placeholder="Текст сообщения" cols="30" rows="10" required=""></textarea>
                <label for="messageText"><img class="icon" src="<?=$this->GetFolder()?>/images/question.svg" alt="" height="16px">Текст
                    сообщения</label>
            </div>
            <img src="<?=$this->GetFolder()?>/images/info.svg" class="info" alt="" data-toggle="tooltip" title="помощь по форме здесь">
        </div>
        <div class="form-group mb-3 request-files-wrapper">
            <input class="inputfile" type="file" name="files[]" multiple="" id="files">
            <label class="btn btn-secondary" for="files"><p> Нажмите, чтобы выбрать файлы или перетащите файлы сюда</p>
                <div id="selectedFiles"></div>
            </label>
            <img src="<?=$this->GetFolder()?>/images/info.svg" class="info" alt="" data-toggle="tooltip" title="помощь по форме здесь">
        </div>
        <div></div>
        <div class="form-buttons">
            <button onclick="location.href='<?=$arResult['LIST_PAGE_URL'] ?>'" type="submit" class="w-100 btn btn-large button-secondary"><b>Отмена</b>
            </button>
            <button type="submit" class="w-100 btn btn-large button-active"><b>Отправить</b></button>
        </div>
    </form>
</content>
