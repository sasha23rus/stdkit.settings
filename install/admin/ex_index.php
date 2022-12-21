<?
require_once $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_before.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_after.php';

use Bitrix\Main\Localization\Loc;
use	Bitrix\Main\HttpApplication;
use Bitrix\Main\Loader;
use Bitrix\Main\Config\Option;

Loc::loadMessages(__FILE__);
Loc::loadMessages($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/options.php");

$request = HttpApplication::getInstance()->getContext()->getRequest();
$module_id = 'stdkit.settings';

//Loader::includeModule($module_id);

$MOD_RIGHT = $APPLICATION->GetGroupRight($module_id);

if($MOD_RIGHT>="R"):

	if ($_SERVER["REQUEST_METHOD"]=="POST" && $_POST["stop_site"]=="Y" &&  check_bitrix_sessid()){
		COption::SetOptionString("main", "site_stopped", "Y");
		CAdminMessage::ShowNote(GetMessage("MAIN_OPTION_PUBL_CLOSES"));
	}

	if ($_SERVER["REQUEST_METHOD"]=="POST" && $_POST["start_site"]=="Y" && check_bitrix_sessid()){
		COption::SetOptionString("main", "site_stopped", "N");
		CAdminMessage::ShowNote(GetMessage("MAIN_OPTION_PUBL_OPENED"));
	}


	$aTabs = array(
		array(
			"DIV" 	  => "edit1",
			"TAB" 	  => Loc::getMessage("KIT_OPTIONS_TAB_NAME"),
			"TITLE"   => Loc::getMessage("KIT_OPTIONS_TAB_NAME"),
		)
	);
	$tabControl = new CAdminTabControl("tabControl", $aTabs);
	?>



	<form class="ksp_options" action="<? echo($APPLICATION->GetCurPage()); ?>?mid=<? echo($module_id); ?>&lang=<? echo(LANG); ?>" method="post">
		<?=bitrix_sessid_post()?>
		<?$tabControl->Begin();?>
		<!-- Включение/отключение САЙТА -->
		<?$tabControl->BeginNextTab();?>
		<tr>
			<td colspan="2" align="left">
				<?if(COption::GetOptionString("main", "site_stopped", "N")=="Y"):?>
					<span style="color:red;"><?echo GetMessage("MAIN_OPTION_PUBL_CLOSES")?></span>
				<?else:?>
					<span style="color:green;"><?echo GetMessage("MAIN_OPTION_PUBL_OPENED")?></span>
				<?endif?>
				<br><br>
			</td>
		</tr>
		<tr>
			<td colspan="2" align="left">
				<?if(COption::GetOptionString("main", "site_stopped", "N")=="Y"):?>
					<input type="hidden" name="start_site" value="Y">
					<input type="submit" name="start_siteb" value="<?echo GetMessage("MAIN_OPTION_PUBL_OPEN")?>">
				<?else:?>
					<input type="hidden" name="stop_site" value="Y">
					<input type="submit" name="stop_siteb" value="<?echo GetMessage("MAIN_OPTION_PUBL_CLOSE")?>">
				<?endif?>
			</td>
		</tr>
		<?$tabControl->EndTab();?>
	</form>

	<?php if ($MOD_RIGHT>="W"): ?>
		<br><br>
		<?
		$aTabs = array(
			array(
				"DIV" 	  => "edit2",
				"TAB" 	  => "Доступ",
				"TITLE"   => "Доступ",
			)
		);
		$tabControl = new CAdminTabControl("tabControl", $aTabs);
		?>

		<form class="ksp_options" action="<? echo($APPLICATION->GetCurPage()); ?>?mid=<? echo($module_id); ?>&lang=<? echo(LANG); ?>" method="post">
			<?=bitrix_sessid_post()?>
			<!-- ДОСТУП ПОЛЬЗОВАТЕЛЕЙ -->
			<?$tabControl->BeginNextTab();?>
			<?require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/admin/group_rights.php");?>
			<?$tabControl->EndTab();?>

			<input type="submit" name="save" value="Сохранить">
		</form>
	<?php endif ?>

	<?
	if($request->isPost() && check_bitrix_sessid()){

		if($request["save"]){
			CMain::DelGroupRight($module_id);  
	        $GROUP = $_REQUEST['GROUPS'];
	        $RIGHT = $_REQUEST['RIGHTS'];
	        
	        foreach($GROUP as $k => $v) {
	            if($k == 0) {
	                COption::SetOptionString($module_id, 'GROUP_DEFAULT_RIGHT', $RIGHT[0], 'Right for groups by default');        
	            } 
	            else {
	                CMain::SetGroupRight($module_id, $GROUP[$k], $RIGHT[$k]);
	            }  
	        }
		}

		LocalRedirect($APPLICATION->GetCurPage()."?mid=".$module_id."&lang=".LANG);
	}
	?>

	<?$tabControl->End();?>
<?endif;?>

<?require_once $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/epilog_admin.php';?>