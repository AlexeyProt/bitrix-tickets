<?php

use Bitrix\Iblock\Component\Tools;
use Bitrix\Main\Context;

class Tickets extends \CBitrixComponent
{
    protected $templatePage = '';

    protected $defaultUrlTemplates = [
        "tickets" => "",
        "ticket" => "ticket_#ELEMENT_ID#",
        "ticket_form" => "ticket",
        "login" => "login",
        "send_ticket" => "send_ticket",
        "send_message" => "send_message"
    ];

    public function __construct($component = null)
    {
        parent::__construct($component);
    }

    /**
     * @return array
     */
    protected function getComponentTemplate()
    {
        $componentVariables = ['ELEMENT_ID'];
        $defaultVariableAliases = [];
        $variables = [];
        $urlTemplates = CComponentEngine::MakeComponentUrlTemplates($this->defaultUrlTemplates, $this->arParams["SEF_URL_TEMPLATES"]);
        $variableAliases = CComponentEngine::MakeComponentVariableAliases($defaultVariableAliases, $this->arParams["VARIABLE_ALIASES"]);
        $componentPage = CComponentEngine::ParseComponentPath($this->arParams["SEF_FOLDER"], $urlTemplates, $variables);
        global $APPLICATION;

        if (!$componentPage && $APPLICATION->GetCurDir() == $APPLICATION->GetCurUri()) $componentPage = 'tickets';
        CComponentEngine::InitComponentVariables($componentPage, $componentVariables, $variableAliases, $variables);

        return ['component_page' => $componentPage, 'variables' => $variables];
    }


    /**
     * Устанавливает шаблон для страницы со списком заявок
     * Устанавливает $arResult
     */
    protected function setTicketsTemplate()
    {
        global $APPLICATION;
        global $USER;
        CModule::IncludeModule('iblock');

        $iblockResult = CIBlock::GetList(
            false,
            ['CODE' => 'tickets'],
            false,
        );

        $this->arResult['IBLOCK_ID'] = $iblockResult->Fetch()['ID'];

        $filter = ['IBLOCK_ID' => $this->arResult['IBLOCK_ID'], "ACTIVE" => "Y"];
        // Если пользователь не является администратором или оператором, то получить заявки только текущего пользователя
        if (empty(array_intersect($this->getOperatorGroupIds(), $USER->GetUserGroupArray()))) {
            $filter['PROPERTY_USER'] = $USER->GetID();
        }

        $elements = CIBlockElement::GetList(
            ['ID'],
            $filter,
            false,
            false,
            ['IBLOCK_ID', 'ID', 'NAME', 'DATE_CREATE', 'PROPERTY_STATUS']
        );

        $elements->SetUrlTemplates($APPLICATION->GetCurDir() . $this->arParams["SEF_URL_TEMPLATES"]['ticket']);

        $tickets = [];
        while ($element = $elements->GetNextElement()) {
            $tickets[] = $element->GetFields();
        }

        $this->arResult['ITEMS'] = $tickets;
    }

    /**
     * Устанавливает шаблон для страницы с заявкой
     * Устанавливает $arResult
     */
    protected function setTicketTemplate($ticketId)
    {
        CModule::IncludeModule('iblock');

        $this->templatePage = 'ticket';

        $iblockResult = CIBlock::GetList(
            false,
            ['CODE' => ['tickets', 'messages']],
        );

        $iblockIds = [];
        while ($iblock = $iblockResult->Fetch()) {
            $iblockIds[$iblock['CODE']] = $iblock['ID'];
        }

        $ticketsResult = CIBlockElement::GetList(
            false,
            ['IBLOCK_ID' => $iblockIds['tickets'], 'ID' => $ticketId],
            false,
            false,
            ['IBLOCK_ID', 'ID', 'NAME', 'PROPERTY_STATUS']
        );

        $this->arResult = $ticketsResult->GetNext();
        if (!$this->arResult) $this->show404();

        $messagesResult = CIBlockElement::GetList(
            false,
            ['IBLOCK_ID' => $iblockIds['messages'], 'PROPERTY_TICKET' => $this->arResult['ID']],
            false,
            false,
            ['IBLOCK_ID', 'PROPERTY_TICKET', 'DETAIL_TEXT', 'CREATED_DATE', 'PROPERTY_USER']
        );

        $userIds = [];
        while ($message = $messagesResult->GetNext()) {
            $this->arResult['MESSAGES'][] = $message;
            $userIds[$message['PROPERTY_USER_VALUE']] = $message['PROPERTY_USER_VALUE'];
        }

        $userElements = CUser::GetList(
            false,
            false,
            ['ID' => implode('|', $userIds)]
        );

        $users = [];
        while ($user = $userElements->GetNext()) {
            // Если пользователь администратор или оператор
            if (array_intersect($this->getOperatorGroupIds(), CUser::GetUserGroup($user['ID']))) {
                $user['IS_OPERATOR'] = true;
            }
            $users[$user['ID']] = $user;
        }

        foreach ($this->arResult['MESSAGES'] as &$item) {
            $item['USER'] = $users[$item['PROPERTY_USER_VALUE']];
        }

        $request = Context::getCurrent()->getRequest();

        $this->arResult['LIST_PAGE_URL'] = $request->getRequestedPageDirectory() . '/' . $this->arParams["SEF_URL_TEMPLATES"]['tickets'];
    }

    /**
     * @return int[]
     *
     * Возвращает id групп Администраторы и Операторы
     */
    protected function getOperatorGroupIds()
    {
        $groupsResult = CGroup::GetList(false, false, ['STRING_ID' => 'operators']);

        // id групп Администраторы и Операторы
        $operatorGroupIds = ['1'];
        $operatorGroupIds[] = $groupsResult->GetNext()['ID'];

        return $operatorGroupIds;
    }

    /**
     * Устанавливает шаблон в зависимости от url
     */
    protected function setComponentTemplate()
    {
        $template = $this->getComponentTemplate();

        if ($template['component_page'] == 'login') {
            $this->setLoginTemplate();
            return;
        }

        global $USER;
        if (!$USER->IsAuthorized()) LocalRedirect($this->arParams["SEF_URL_TEMPLATES"]['login']);

        switch ($template['component_page']) {
            case 'tickets':
                $this->setTicketsTemplate();
                break;
            case 'ticket':
                $this->setTicketTemplate($template['variables']['ELEMENT_ID']);
                break;
            case 'ticket_form':
                $this->setTicketFormTemplate();
                break;
            case 'send_ticket':
                $this->setSendTicketTemplate();
                break;
            case 'send_message':
                $this->setSendMessageTemplate();
                break;
            default:
                $this->AbortResultCache();
                $this->show404();
                break;
        }
    }

    protected function show404()
    {
        CModule::IncludeModule('iblock');
        Tools::process404(
            GetMessage("T_NEWS_DETAIL_NF"),
            true,
            true,
            true,
        );
    }

    /**
     * Устанавливает шаблон для обработки отправленного сообщения
     */
    protected function setSendMessageTemplate()
    {
        $this->templatePage = 'sendMessage';
    }

    /**
     * Устанавливает шаблон страницы с формой заявки
     * Устанавливает $arResult
     */
    protected function setTicketFormTemplate()
    {
        $request = Context::getCurrent()->getRequest();
        $this->arResult['LIST_PAGE_URL'] = $request->getRequestedPageDirectory() . '/' . $this->arParams["SEF_URL_TEMPLATES"]['tickets'];
        $this->templatePage = 'ticketForm';
    }

    /**
     * Устанавливает шаблон для обработки отправленной заявки
     * Устанавливает $arResult
     */
    protected function setSendTicketTemplate()
    {
        $request = Context::getCurrent()->getRequest();

        $this->templatePage = 'sendTicket';
        $this->arResult['LIST_PAGE_URL'] = $request->getRequestedPageDirectory() . '/' . $this->arParams["SEF_URL_TEMPLATES"]['tickets'];
    }

    /**
     * Устанавливает шаблон для страницы авторизации
     * Устанавливает $arResult
     */
    protected function setLoginTemplate()
    {
        global $USER;
        $request = Context::getCurrent()->getRequest();
        if ($USER->IsAuthorized()) LocalRedirect($request->getRequestedPageDirectory() . '/' . $this->arParams["SEF_URL_TEMPLATES"]['tickets']);
        $this->templatePage = 'login';

        $this->arResult['LIST_PAGE_URL'] = $request->getRequestedPageDirectory() . '/' . $this->arParams["SEF_URL_TEMPLATES"]['tickets'];
    }

    public function executeComponent()
    {
        $this->setComponentTemplate();

        if ($this->startResultCache())//startResultCache используется не для кеширования html, а для кеширования arResult
        {
            $this->includeComponentTemplate($this->templatePage);
        }

        return $this->arResult;
    }
}
