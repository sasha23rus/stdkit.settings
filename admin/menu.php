<?
use Bitrix\Main\Localization\Loc;

AddEventHandler("main", "OnBuildGlobalMenu", "kitGlobalMenu");

function kitGlobalMenu(&$arGlobalMenu, &$arModuleMenu)
{
	IncludeModuleLangFile(__FILE__);
	$moduleName = "stdkit.settings";

	global $APPLICATION;
	$APPLICATION->SetAdditionalCss("/bitrix/css/".$moduleName."/menu.css");


	if($APPLICATION->GetGroupRight($moduleName) > "D")
	{
		$arMenu = array(
			"menu_id" => "kit-menu",
			"items_id" => "menu_references",
			'text' => Loc::getMessage('KIT_MENU_TITLE'),//описание из файла локализации
        	'title' => Loc::getMessage('KIT_MENU_TITLE'),//название из файла локализации
			"sort" => 900,
			"items" => array(
				array(
					"text" => Loc::getMessage('KIT_SUBMENU_TITLE'),
					"sort" => 10,
					// "url" => "/bitrix/admin/settings.php?lang=".LANGUAGE_ID."&mid=".$moduleName."&mid_menu=1",
					"url"=>"/bitrix/admin/".$moduleName."/ex_index.php?lang=".LANG,
					"items_id" => "MOD_main",
				),
			),
		);
		$arGlobalMenu[] = $arMenu;
	}
}
