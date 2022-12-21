<?php
//подключаем основные классы для работы с модулем
use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Bitrix\Main\Entity\Base;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
//в данном модуле создадим адресную книгу, и здесь мы подключаем класс, который создаст нам эту таблицу
use Module\Adress\AdressTable;
Loc::loadMessages(__FILE__);
//в названии класса пишем название директории нашего модуля, только вместо точки ставим нижнее подчеркивание
class stdkit_settings extends CModule
{
    public function __construct()
    {
        $arModuleVersion = array();
                //подключаем версию модуля (файл будет следующим в списке)
        include __DIR__ . '/version.php';
                //присваиваем свойствам класса переменные из нашего файла
        if (is_array($arModuleVersion) && array_key_exists('VERSION', $arModuleVersion)) {
            $this->MODULE_VERSION = $arModuleVersion['VERSION'];
            $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        }
                //пишем название нашего модуля как и директории
        $this->MODULE_ID = 'stdkit.settings';
        // название модуля
        $this->MODULE_NAME = Loc::getMessage('KIT_MODULE_NAME');
        //описание модуля
        $this->MODULE_DESCRIPTION = Loc::getMessage('KIT_MODULE_DESCRIPTION');
        //используем ли индивидуальную схему распределения прав доступа, мы ставим N, так как не используем ее
        $this->MODULE_GROUP_RIGHTS = 'Y';
        //название компании партнера предоставляющей модуль
        $this->PARTNER_NAME = Loc::getMessage('KIT_MODULE_PARTNER_NAME');
        $this->PARTNER_URI = 'https://stdkit.ru';//адрес вашего сайта
    }

    public function InstallFiles(){
        CopyDirFiles(__DIR__."/assets/styles", Application::getDocumentRoot()."/bitrix/css/".$this->MODULE_ID."/", true, true );
        CopyDirFiles(__DIR__."/assets/images", Application::getDocumentRoot()."/bitrix/images/".$this->MODULE_ID."/", true, true );
        CopyDirFiles(__DIR__."/admin/", Application::getDocumentRoot()."/bitrix/admin/".$this->MODULE_ID."/", true, true );
        return true;
    }
        //здесь мы описываем все, что делаем до инсталляции модуля, мы добавляем наш модуль в регистр и вызываем метод создания таблицы
    public function doInstall(){
        $this->InstallFiles();
        ModuleManager::registerModule($this->MODULE_ID);
    }
        //вызываем метод удаления таблицы и удаляем модуль из регистра
    public function doUninstall(){
        $this->uninstallDB();
        ModuleManager::unRegisterModule($this->MODULE_ID);
    }
        //вызываем метод создания таблицы из выше подключенного класса
    //public function installDB(){ return false; }
        //вызываем метод удаления таблицы, если она существует
    public function uninstallDB(){ return true; }

    public function UnInstallFiles(){
        Directory::deleteDirectory(
            Application::getDocumentRoot()."/bitrix/css/".$this->MODULE_ID
        );
        Directory::deleteDirectory(
            Application::getDocumentRoot()."/bitrix/images/".$this->MODULE_ID
        );
        Directory::deleteDirectory(
            Application::getDocumentRoot()."/bitrix/admin/".$this->MODULE_ID
        );
        return true;
    }

    public function GetModuleRightList() //Метод возвращает права доступа к модулю, права обозначаются буквами, по приниципу A<Z, Т.е. права уровня A ниже чем права уровня Z, данные права используются при установке прав доступа для групп пользователей.
    {
        $arr = array(
            "reference_id" => array("D","R","W"),
            "reference" => array(
                GetMessage("KIT_DENIED"),
                GetMessage("KIT_OPENED"),
                GetMessage("KIT_FULL"))
            );
        return $arr;
    }
}