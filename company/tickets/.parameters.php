<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$arComponentParameters = array(
    "GROUPS" => array(),
    "PARAMETERS" => array(
        "VARIABLE_ALIASES" => Array(
            "" => Array("NAME" => 'Страница списка заявок'),
            "ticket_" => Array("NAME" => 'Страница заявки'),
            "ticket" => Array("NAME" => 'Страница отправки заявки'),
            "login" => Array("NAME" => 'Страница авторизации'),
            "send_ticket" => Array("NAME" => 'Отправление заявки'),
            "send_message" => Array("NAME" => 'Отправление сообщения'),
        ),
        "SEF_MODE" => Array(
            "tickets" => array(
                "NAME" => "Страница списка заявок",
                "DEFAULT" => "",
                "VARIABLES" => array(),
            ),
            "ticket" => array(
                "NAME" => "Страница заявки",
                "DEFAULT" => "ticket_#ELEMENT_ID#",
                "VARIABLES" => array("ELEMENT_ID"),
            ),
            "ticket_form" => array(
                "NAME" => "Страница отправки заявки",
                "DEFAULT" => "ticket",
                "VARIABLES" => array(),
            ),
            "login" => array(
                "NAME" => "Страница авторизации",
                "DEFAULT" => "login",
                "VARIABLES" => array(),
            ),
            "send_ticket" => array(
                "NAME" => "Отправление заявки",
                "DEFAULT" => "send_ticket",
                "VARIABLES" => array(),
            ),
            "send_message" => array(
                "NAME" => "Отправление сообщения",
                "DEFAULT" => "send_message",
                "VARIABLES" => array(),
            )
    )
));
?>