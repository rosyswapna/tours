<?php
/**********************************************************************
    Copyright (C) FrontAccounting, LLC.
	Released under the terms of the GNU General Public License, GPL, 
	as published by the Free Software Foundation, either version 3 
	of the License, or (at your option) any later version.
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  
    See the License here <http://www.gnu.org/licenses/gpl-3.0.html>.
***********************************************************************/
$page_security = 'SA_USERS';
$path_to_root = "..";
include_once($path_to_root . "/includes/session.inc");

if(isset($_GET['NewUser']))
	page(_($help_context = "Add User Account"));
elseif(isset($_GET['EditUser']))
	page(_($help_context = "Edit User Account"));
else
	page(_($help_context = "Users"));



include_once($path_to_root . "/includes/date_functions.inc");
include_once($path_to_root . "/includes/ui.inc");

include_once($path_to_root . "/admin/db/users_db.inc");
include_once($path_to_root . "/includes/db/cnc_session_db.inc");

simple_page_mode(true);
//-------------------------------------------------------------------------------------------------

function can_process() 
{

	/*if (strlen($_POST['user_id']) < 4)
	{
		display_error( _("The user login entered must be at least 4 characters long."));
		set_focus('user_id');
		return false;
	}

	if ($_POST['password'] != "") 
	{
	    	if (strlen($_POST['password']) < 4)
	    	{
	    		display_error( _("The password entered must be at least 4 characters long."));
				set_focus('password');
	    		return false;
	    	}

	    	if (strstr($_POST['password'], $_POST['user_id']) != false)
	    	{
	    		display_error( _("The password cannot contain the user login."));
				set_focus('password');
	    		return false;
	    	}
	}*/
	
	//get username and password from cnc user table on time of table updation

	return true;
}

//-------------------------------------------------------------------------------------------------

if (($Mode=='ADD_ITEM' || $Mode=='UPDATE_ITEM') && check_csrf_token())
{

	if (can_process())
	{
		$cnc_user = get_cnc_user($_POST['cnc_userid']);

	    	if ($selected_id != -1) 
	    	{
	    		update_user_prefs($selected_id,
	    			get_post(array('user_id', 'real_name', 'phone', 'email', 'role_id', 'language',
						'print_profile', 'rep_popup' => 0, 'pos')));

	    		if ($_POST['password'] != "")
	    			update_user_password($selected_id, $_POST['user_id'], md5($_POST['password']));

	    		display_notification_centered(_("The selected user has been updated."));
	    	} 
	    	else 
	    	{
	    		add_user($cnc_user['username'], $_POST['real_name'], $cnc_user['password'],
					$_POST['phone'], $_POST['email'], $_POST['role_id'], $_POST['language'],
					$_POST['print_profile'], check_value('rep_popup'), $_POST['pos']);
				$id = db_insert_id();
				
				update_fa_account_in_users($_POST['cnc_userid'],$id);
				
				
				// use current user display preferences as start point for new user
				$prefs = $_SESSION['wa_current_user']->prefs->get_all();
			
				update_user_prefs($id, array_merge($prefs, get_post(array('print_profile',
					'rep_popup' => 0, 'language'))));

				display_notification_centered(_("A new user has been added."));
	    	}
		$Mode = 'RESET';
	}
}

//-------------------------------------------------------------------------------------------------

if ($Mode == 'Delete' && check_csrf_token())
{
	delete_user($selected_id);
	display_notification_centered(_("User has been deleted."));
	$Mode = 'RESET';
}

//-------------------------------------------------------------------------------------------------
if ($Mode == 'RESET')
{
 	$selected_id = -1;
	$sav = get_post('show_inactive');
	unset($_POST);	// clean all input fields
	$_POST['show_inactive'] = $sav;
}


$result = get_users(check_value('show_inactive'));
start_form();
start_table(TABLESTYLE);

//$th = array(_("User login"), _("Full Name"), _("Phone"),
	//_("E-mail"), _("Last Visit"), _("Access Level"), "", "");
$th = array(_("User login"), _("Full Name"), _("Phone"),
	_("E-mail"), _("Last Visit"), _("Access Level"));

//inactive_control_column($th);
table_header($th);	

$k = 0; //row colour counter

while ($myrow = db_fetch($result)) 
{

	alt_table_row_color($k);

	$last_visit_date = sql2date($myrow["last_visit_date"]);

	/*The security_headings array is defined in config.php */
	$not_me = strcasecmp($myrow["user_id"], $_SESSION["wa_current_user"]->username);

	label_cell($myrow["user_id"]);
	label_cell($myrow["real_name"]);
	label_cell($myrow["phone"]);
	email_cell($myrow["email"]);
	label_cell($last_visit_date, "nowrap");
	label_cell($myrow["role"]);
	
   /* if ($not_me)
		inactive_control_cell($myrow["id"], $myrow["inactive"], 'users', 'id');
	elseif (check_value('show_inactive'))
		label_cell('');

	edit_button_cell("Edit".$myrow["id"], _("Edit"));
    if ($not_me)
 		delete_button_cell("Delete".$myrow["id"], _("Delete"));
	else
		label_cell('');*/
	end_row();

} //END WHILE LIST LOOP

//inactive_control_row($th);
end_table(1);
//-------------------------------------------------------------------------------------------------

if(isset($_GET['NewUser']) || isset($_GET['EditUser'])){
	

	$cnc_user = (isset($_GET['NewUser']))?get_cnc_user($_GET['NewUser']):get_cnc_user($_GET['EditUser']);
	print_r($security_roles);
	
	//print_r($cnc_user);

	start_table(TABLESTYLE2);

	$_POST['real_name'] = trim(@$cnc_user["first_name"]." ".@$cnc_user["last_name"]);
	$_POST['phone'] = @$cnc_user["phone"];
	$_POST['email'] = @$cnc_user["email"];
	$_POST['user_id'] = @$cnc_user["username"];
	

	if(isset($_GET['EditUser'])){
		//editing an existing User		
		$myrow = get_user($selected_id);
		$_POST['id'] = $myrow["id"];
		$_POST['role_id'] = $myrow["role_id"];
		$_POST['language'] = $myrow["language"];
		$_POST['print_profile'] = $myrow["print_profile"];
		$_POST['rep_popup'] = $myrow["rep_popup"];
		$_POST['pos'] = $myrow["pos"];

		hidden('selected_id', $selected_id);
		hidden('user_id');
		
	}else{
		$_POST['role_id'] = SECURITY_ROLE_FRD;
		$_POST['language'] = user_language();
		$_POST['print_profile'] = user_print_profile();
		$_POST['rep_popup'] = user_rep_popup();
		$_POST['pos'] = user_pos();
	}
	
	hidden('cnc_userid',$cnc_user['id']);
	start_row();


		text_row_ex(_("Full Name").":", 'real_name',  50,null,null,null,null,null,true);

		text_row_ex(_("Telephone No.:"), 'phone', 50,null,null,null,null,null,true);

		email_row_ex(_("Email Address:"), 'email', 50,null,null,null,null,null,true);

		//security_roles_list_row(_("Access Level:"), 'role_id', null);
		hidden('role_id') ;

		languages_list_row(_("Language:"), 'language', null);

		pos_list_row(_("User's POS"). ':', 'pos', null);

		print_profiles_list_row(_("Printing profile"). ':', 'print_profile', null,
		_('Browser printing support'));

		check_row(_("Use popup window for reports:"), 'rep_popup', $_POST['rep_popup'],
		false, _('Set this option to on if your browser directly supports pdf files'));

	end_table(1);

	if(isset($_GET['NewUser']))
		submit_continue_center($selected_id == -1, '', 'both');
	
	//submit_continue_center($selected_id == -1);
}else{

}

end_form();
end_page();
?>
