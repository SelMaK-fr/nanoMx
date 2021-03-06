<?php
/**
 * This file is part of
 * pragmaMx - Web Content Management System.
 * Copyright by pragmaMx Developer Team - http://www.pragmamx.org
 *
 * pragmaMx is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * $Revision: 119 $
 * $Author: PragmaMx $
 * $Date: 2016-03-30 15:35:05 +0200 (Mi, 30. Mrz 2016) $
 *
 *
 */

defined('mxMainFileLoaded') or die('access denied');

if (!mxGetAdminPref('radminuser')) {
    return mxRedirect(adminUrl(), 'Access Denied');
}

/* Sprachdateien auswählen */
mxGetLangfile(__DIR__);
mxGetLangfile('Your_Account');

require_once(PMX_SYSTEM_DIR . DS . 'mx_userfunctions.php');

/**
 * mainusers()
 *
 * @return
 */
function mainusers()
{
    $userconfig = load_class('Userconfig');
    $udata['user_sexus'] = 0;
    $udata['user_stat'] = 1;
    $udata['user_ingroup'] = $userconfig->default_group;
    $udata['user_bday'] = '0000-00-00';
    $newpass = pmx_password_create();
    $options = getAllUsersSelectOptions1();
    pmx_html_passwordchecker();

    include('header.php');
    title(_USERADMIN);
    echo '
		<ul class="nav nav-tabs" role="tablist">
			<li class="nav-item">
				<a  class="nav-link active" data-toggle="tab" href="#userEdit" role="tab">' . _YA_EDITUSER . '</a>
			</li>
			<li class="nav-item">
				<a  class="nav-link" data-toggle="tab" href="#userAdd" role="tab">' . _ADDUSER . '</a>
			</li>
		</ul>

	<div class="tab-content py-1">

        <div class="tab-pane active" id="userEdit" role="tabpanel">
			<form method="post" action="' . adminUrl(PMX_MODULE, 'modify') . '" name="edit_user_form">
                <table class="table table-hover w-50">
                    <thead>
                        <tr>
                            <th>' . _SELECTSTAT . '</th>
                            <th>' . _YA_ADM_USERENAMEGROUP . '</th>
                            <th>' . _YA_USERSTAT . '</th>
                        </tr>
                    </thead>
                    <tbody>';

    if ((!empty($options[0])) && ($userconfig->register_option === 2 || $userconfig->register_option === 4)) {
        echo '
            <tr>
                <td><input type="radio" name="user_stat" value="0" /></td>         
                <td><select class="form-control" name="uid_0">' . implode("", $options[0]) . '</select>&nbsp;(' . count($options[0]) . ')</td>
                <td><span class="badge badge-warning">' . _YA_ADM_NEWUSERS . '</span></td>
            </tr>';
    }

    if (!empty($options[1])) {
        echo '
            <tr>
                <td><input type="radio" name="user_stat" value="1" checked="checked" /></td>
                <td><select class="form-control" name="uid_1">' . implode("", $options[1]) . '</select>&nbsp;(' . count($options[1]) . ')</td>
                <td><span class="badge badge-success">' . _YA_ADM_ACTIVUSERS . '</span></td>
            </tr>';
    }

    if (!empty($options[2])) {
        echo '
            <tr>
                <td><input type="radio" name="user_stat" value="2" /></td>          
                <td><select class="form-control" name="uid_2">' . implode("", $options[2]) . '</select>&nbsp;(' . count($options[2]) . ')</td>
                <td><span class="badge badge-secondary">' . _YA_ADM_DEACTIVUSERS . '</span></td>
            </tr>';
    }

    if (!empty($options[-1])) {
        echo '
            <tr>
                <td><input type="radio" name="user_stat" value="-1" /></td>
                <td><select class="form-control" name="uid_3">' . implode("", $options[-1]) . '</select>&nbsp;(' . count($options[-1]) . ')</td>
                <td><span class="badge badge-danger">' . _YA_REAC_DELETED . '</span></td>
            </tr>';
    }

    echo '
            </tbody>
        </table>
        <div class="container">
            <div class="form-group">
                    <input type="hidden" name="op" value="' . PMX_MODULE . '/modify" />
                    <button type="submit" class="btn btn-primary"  name="umodify"><i class="fa fa-edit fa-lg"></i>&nbsp;' . _MODIFY . '</button>
                    <button type="submit" class="btn btn-danger" name="udelete"><i class="fa fa-trash fa-lg"></i>&nbsp;' . _DELETE . '</button>                   
            </div>       
        </div>
	</form>
    </div>';
 
	// add user
    echo '
		<div class="tab-pane" id="userAdd" role="tabpanel">
			<form action="' . adminUrl(PMX_MODULE, 'add') . '" method="post">
            <div class="container">
		
        	<div class="form-group row">
            	<label for="name" class="col-sm-2 col-form-label">' . _NAME . '</label>
            	<div class="col-sm-3">
					<input type="text" class="form-control" id="name" name="name" value="' . ((empty($udata['name'])) ? '' : htmlspecialchars($udata['name'])) . '" size="60" maxlength="60" />
            	</div>
        	</div>

	        <div class="form-group row">
				<label for="uname" class="col-sm-2 col-form-label">' . _NICKNAME . '</label>
				<div class="col-sm-3">
					<input type="text" class="form-control" id="uname" name="uname" size="30" maxlength="25" />
				</div>
				<span class="form-text text-muted">' . _REQUIRED . '</span>
			</div>
			
	        <div class="form-group row">
				<label for="pass" class="col-sm-2 col-form-label">' . _PASSWORD . '</label>
				<div class="col-sm-2">
					<input type="text" class="form-control password-checker-input" id="pass" name="pass" size="30" value="' . $newpass . '" /> 
				</div>
			</div>			
			
			' . adminuserform($udata) . '
			
			<div class="form-group row">
				<div class="offset-sm-2 col-sm-10">
					<input type="hidden" name="op" value="' . PMX_MODULE . '/add" />
					<button type="submit" class="btn btn-primary"><i class="fa fa-plus fa-lg"></i>&nbsp;' . _ADDUSERBUT . '</button> 
				</div>
			</div>	

            </div>		
			</form>
        </div>

	</div><!-- /tab-content -->';

    include('footer.php');
}

/**
 * modify()
 *
 * @return
 */
function modify()
{
    global $user_prefix, $prefix;

    if (isset($_POST['umodify']) && isset($_POST['user_stat'])) {
        /* Bearbeiten ueber Adminmenue */
        switch (true) {
            case $_POST['user_stat'] == 0 && $_POST['uid_0']:
                $chng_uid = $_POST['uid_0'];
                break;
            case $_POST['user_stat'] == 1 && $_POST['uid_1']:
                $chng_uid = $_POST['uid_1'];
                break;
            case $_POST['user_stat'] == 2 && $_POST['uid_2']:
                $chng_uid = $_POST['uid_2'];
                break;
            case $_POST['user_stat'] == -1 && $_POST['uid_3']:
                $chng_uid = $_POST['uid_3'];
                break;
            default:
                $chng_uid = 0;
        }
    } else {
        /* Bearbeiten ueber Mitgliederliste etc. */
        $chng_uid = (empty($_REQUEST['chng_uid'])) ? 0 : intval($_REQUEST['chng_uid']);
    }

    $udata = mxGetUserDataFromUid($chng_uid);
    if (empty($udata)) {
        return mxRedirect(adminUrl(PMX_MODULE), _USERNOEXIST);
    }

    switch ($udata['user_stat']) {
        case 0:
            $ptitle = _YA_ADM_NEWUSER;
            break;
        case 2:
            $ptitle = _YA_ADM_DEACTCUSER;
            break;
        case -1:
            $ptitle = _YA_REAC_DELETEDUSER;
            $udata['name'] = ''; // deleted entfernen
            break;
        case 1:
        default:
            $ptitle = _USERUPDATE;
            break;
    }

    $userconfig = load_class('Userconfig');

    switch (true) {
        case ($userconfig->register_option === 2) && ($udata['user_stat'] == 0):
            $formpass = pmx_password_create();
            $forchanges = '';
            break;

        case ($userconfig->register_option === 4) && ($udata['user_stat'] == 0):
            $querypss = "SELECT check_thepss 
						FROM `{$user_prefix}_users_temptable` 
						WHERE (uname='" . mxAddSlashesForSQL($udata['uname']) . "' AND email = '" . mxAddSlashesForSQL($udata['email']) . "')";
            $thequerypss_result = sql_query($querypss);
            if (!$thequerypss_result) {
                return mxErrorScreen(_DATABASEERROR . "Get the readable password --> Activationlink mod --> users.php");
            }
            $getthedata = sql_fetch_assoc($thequerypss_result);
            $formpass = base64_decode($getthedata['check_thepss']);
            $delqry1 = "DELETE 
						FROM `{$user_prefix}_users_temptable` 
						WHERE (uname='" . mxAddSlashesForSQL($udata['uname']) . "' AND email = '" . mxAddSlashesForSQL($udata['email']) . "')";
            $delresult1 = sql_query($delqry1);
            if (!$delresult1) {
                return mxErrorScreen(_DATABASEERROR . "Delete temptable - entry --> users.php");
            }
            $forchanges = '';
            break;

        default:
            $formpass = '';
            $forchanges = ' <span class="tiny">' . _FORCHANGES . '</span>';
    }

    unset($chng_uid, $user_stat);

    /* aktuelles Foto ermitteln */
    $pici = load_class('Userpic', $udata);
    $uploadedpic = $pici->exist();

    include('header.php');
    title(_USERADMIN);
    echo '
    	<div class="card">
			<div class="card-block">
				<h3 class="mb-2">' . $ptitle . '  <span class="badge badge-default">' . $udata['uname'] . '</span></h3>';

    if ($uploadedpic) {

        ?>
  
     	<div class="card text-center" style="width: 20rem;">
			<div class="card-block"> 			 

    <p class="text-muted small text-uppercase font-weight-bold"><?php echo _UPIC_UPLOADED ?></p>
    <img alt="uploaded" src="<?php echo $uploadedpic ?>"/>
    &nbsp;&nbsp;<a href="modules.php?name=Your_Account&amp;op=deluserpic&amp;uid=<?php echo $udata['uid'] ?>" class="btn hidden" id="upicdelete"><?php echo _UPIC_DELETEIMG ?></a>
    <noscript>
        <a class="btn" href="#" title="<?php echo _JSSHOULDBEACTIVE ?>"><?php echo _UPIC_DELETEIMG ?></a><br /><span class="tiny">(<?php echo _JSSHOULDBEACTIVE ?>)</span>
    </noscript>
<script type="text/javascript">
/*<![CDATA[*/
  $(document).ready(function() {
    $('#upicdelete').removeClass('hidden');
  });
  $('#upicdelete').click(function() {
    return confirm('<?php echo addslashes(_UPIC_SUREDELETE) ?>');
  });
/*]]>*/
</script>

</div>
</div>

    <?php
    }		
	echo '
			<form action="' . adminUrl(PMX_MODULE, 'update') . '" method="post"> 
				<div class="container">
				<input type="hidden" name="uname" value="' . mxEntityQuotes($udata['uname']) . '" />
				' . adminuserform($udata);
	 
    if ($udata['user_stat'] != -1) {
        pmx_html_passwordchecker();
        echo '

        <div class="form-group row">
            <label for="pass" class="col-sm-2 col-form-label">' . _PASSWORD . '</label>
            <div class="col-sm-3">
            	<input type="password" id="pass" name="pass" value="' . $formpass . '" size="30" class="form-control password-checker-input" />
            	<span class="form-text text-muted">' . $forchanges . '</span>			
            </div>           	
        </div>

        <div class="form-group row">
            <label for="pass2" class="col-sm-2 col-form-label">' . _RETYPEPASSWD . '</label>
            <div class="col-sm-3">
            	<input type="password" id="pass2" name="pass2" value="' . $formpass . '" size="30" class="form-control password-checker-input" />
            	<span class="form-text text-muted">' . $forchanges . '</span>			
            </div>           	
        </div>
		<button type="submit" class="btn btn-primary"><i class="fa fa-check fa-lg"></i>&nbsp;' . _SAVECHANGES . '</button> 
        ';

    } else {
        echo '
				<input type="hidden" name="user_stat" value="1" />
                <button type="submit" class="btn btn-primary" name="ureactivate"><i class="fa fa-check fa-lg"></i>&nbsp;' . _REACTIVATEUSER . '</button>
                ';
    }

    echo '
				<input type="hidden" name="chng_uid" value="' . $udata['uid'] . '" />
				<input type="hidden" name="old_user_stat" value="' . $udata['user_stat'] . '" />
				<input type="hidden" name="op" value="' . PMX_MODULE . '/update" />
			</form>

			 	</div>
			</div>
		</div>';

    include('footer.php');
}

/**
 * update()
 *
 * @param mixed $pvs
 * @return
 */
function update($pvs)
{
    global $user_prefix, $prefix;

    $userconfig = load_class('Userconfig');
    // $proofdate = $userconfig->yaproofdate;
    // # Check des Geburtsdatums beim reaktivieren abschalten
    // if ($userconfig->yaproofdate && isset($pvs['ureactivate']) && isset($pvs['old_user_stat'])) {
    // $userconfig->yaproofdate = 0;
    // }
    $pvs = userCheck($pvs);
    // $userconfig->yaproofdate = $proofdate;
    switch (true) {
        case !is_array($pvs):
            return mxErrorScreen($pvs);
        case (!empty($pvs['pass'])) && ($pvs['pass'] != $pvs['pass2']):
        case (!empty($pvs['pass2'])) && ($pvs['pass'] != $pvs['pass2']):
            return mxErrorScreen(_PASSWDNOMATCH);
        case (!empty($pvs['pass'])) && (strlen($pvs['pass']) < $userconfig->minpass):
            return mxErrorScreen(_YOUPASSMUSTBE . " <b>" . $userconfig->minpass . "</b> " . _CHARLONG);
    }

    $pvs = mxAddSlashesForSQL($pvs);

    extract($pvs);
    $user_ingroup = (empty($user_ingroup)) ? $userconfig->default_group : $user_ingroup;
    $user_sexus = (empty($user_sexus)) ? 0 : (int)$user_sexus;
    $user_stat = (empty($user_stat)) ? 0 : (int)$user_stat;
    $old_user_stat = (empty($old_user_stat)) ? 0 : (int)$old_user_stat;
    // wenn user_stat auf 0 gesetzt wird aber vorher einen anderen Wert hatte, user_stat auf deaktiviert setzen (2)
    $user_stat = ($user_stat != $old_user_stat && $old_user_stat != 0 && $user_stat == 0) ? 2 : $user_stat;

    $setbday = (empty($birthday)) ? "NULL" : "'" . strftime('%Y-%m-%d', $birthday) . "'";

    if (!empty($pass2)) {
        if ($user_stat == 0 && $old_user_stat == 0 && ($userconfig->register_option === 2 || $userconfig->register_option === 4)) {
        } else {
            $salt = pmx_password_salt();
            $pwd = pmx_password_hash($pass2, $salt);
            $fields[] = "`pass` = '" . mxAddSlashesForSQL($pwd) . "'";
            $fields[] = "`pass_salt` = '" . mxAddSlashesForSQL($salt) . "'";
        }
    }

    $fields[] = "`name` = '$name'";
    $fields[] = "`email` = '$email'";
    $fields[] = "`url` = '" . mx_urltohtml(mxCutHTTP($url)) . "'";
    $fields[] = "`user_sexus` = $user_sexus";
    $fields[] = "`user_icq` = '$user_icq'";
    $fields[] = "`user_aim` = '$user_aim'";
    $fields[] = "`user_yim` = '$user_yim'";
    $fields[] = "`user_msnm` = '$user_msnm'";
    $fields[] = "`user_from` = '$user_from'";
    $fields[] = "`user_occ` = '$user_occ'";
    $fields[] = "`user_intrest` = '$user_intrest'";
    $fields[] = "`bio` = '$bio'";
    $fields[] = "`user_sig` = '$user_sig'";
    $fields[] = "`user_ingroup` = $user_ingroup";
    $fields[] = "`user_stat` = $user_stat";
    $fields[] = "`user_bday` = $setbday"; // ohne anführz.
    if (isset($pvs['ureactivate']) && $old_user_stat == -1) {
        $fields[] = "`user_regdate` = ''"; //" . mxGetNukeUserregdate(time()) . "
        $fields[] = "`user_regtime` = " . time();
    }

    $qry = "UPDATE `{$user_prefix}_users` SET " . implode(', ', $fields) . " WHERE uid=" . intval($chng_uid);
    $result = sql_query($qry);

    if ($result) {
        if ($user_stat == 1 && $old_user_stat == 0 && ($userconfig->register_option === 2 || $userconfig->register_option === 4)) {
            $subject = _YA_REG_MAILMSG4 . " " . $uname;
            $message = _HELLO . " " . $uname . "\n\n";
            $message .= _YA_REG_MAILMSG3 . "\n\n";
            $message .= "  -" . _NICKNAME . ":\t " . $uname . "\n";
            $message .= "  -" . _PASSWORD . ":\t " . $pass . "\n";
            $message .= "\n\n" . $GLOBALS['slogan'] . "\n-----------------------------------------------------------\n\n";
            $message .= PMX_HOME_URL . "/modules.php?name=Your_Account\n\n\n\n\n\n\n\n\npowered by: nanoMx " . PMX_VERSION . " (http://www.nanoMx.pro)";
            mxMail($email, $subject, $message);
        }

        /* Modulspezifische Useränderungen durchfuehren */
		$vuid= $chng_uid; // weil $vuid als Array zurückkommt...
        pmx_run_hook('user.edit', $vuid);
    } else {
        return mxErrorScreen(_DATABASEERROR . '<br /><br />' . $qry);
    }

    switch (true) {
        case isset($pvs['ureactivate']) && $old_user_stat == -1:
            $udata = mxGetUserDataFromUid($chng_uid);
            $message = sprintf(_YA_REAC_MESSAGETEXT, $udata['uname'], $GLOBALS['sitename']);
            $message .= "\n\n\n---------------------------------------------------------------------------\n" . _PASSWORDLOST . "\n" . PMX_HOME_URL . "/modules.php?name=Your_Account&amp;op=pass_lost";
            include('header.php');
            title(_USERADMIN);
            echo '
				<div class="card">
					<div class="card-block">
						<p>' . sprintf(_YA_REAC_RESULTOK, htmlspecialchars($uname)) . '</p>
						<p>' . sprintf(_YA_REAC_SENDMESSAGE, htmlspecialchars($uname)) . '</p>
						<form action="' . adminUrl(PMX_MODULE, 'reactivate') . '" method="post">
							<p>' . _YA_REAC_EDITMSGTEXT . '</p>
							<textarea cols="75" rows="8" name="message">' . $message . '</textarea>
							<input type="hidden" name="op" value="' . PMX_MODULE . '/reactivate" />
							<input type="hidden" name="uid" value="' . $chng_uid . '" />
							<input type="submit" class="btn btn-warning" name="sendmail" value="' . _YES . '" />
							<input type="submit"  class="btn btn-default" value="' . _NO . '" />
						</form>
					</div>
				</div>';
            include('footer.php');
            break;

        case $user_stat != $old_user_stat:
            $location = adminUrl(PMX_MODULE);
            return mxRedirect($location, _CHANGESAREOK);

        default:
            $location = adminUrl(PMX_MODULE, 'modify', 'chng_uid=' . $chng_uid); # . '&amp;user_stat=' . $user_stat;
            return mxRedirect($location, _CHANGESAREOK);
    }
}

/**
 * add()
 *
 * @param mixed $pvs
 * @return
 */
function add($pvs)
{
    global $user_prefix, $prefix;

    $userconfig = load_class('Userconfig');

    $pvs = userCheck($pvs);

    switch (true) {
        case !is_array($pvs):
            return mxErrorScreen($pvs);
        case mxCheckNickname($pvs['uname']) !== true:
            return mxErrorScreen(mxCheckNickname($pvs['uname']));
        case empty($pvs['pass']) || (strlen($pvs['pass']) < $userconfig->minpass):
            return mxErrorScreen(_YOUPASSMUSTBE . " <b>" . $userconfig->minpass . "</b> " . _CHARLONG);
    }

    $fields = array();

    $salt = pmx_password_salt();
    $pwd = pmx_password_hash($pvs['pass'], $salt);
    $fields[] = "`pass` = '" . mxAddSlashesForSQL($pwd) . "'";
    $fields[] = "`pass_salt` = '" . mxAddSlashesForSQL($salt) . "'";

    $pvs = mxAddSlashesForSQL($pvs);
    extract($pvs);
    $uname = trim($uname);

    $result = sql_query("SELECT uid 
						FROM `{$user_prefix}_users` 
						WHERE uname='" . mxAddSlashesForSQL($uname) . "'");
    list($new_uid) = sql_fetch_row($result);
    if (!empty($new_uid)) {
        return mxErrorScreen(_USERYESEXIST);
    }

    $user_ingroup = (empty($user_ingroup)) ? $userconfig->default_group : $user_ingroup;
    $fields[] = "`user_ingroup` = $user_ingroup";
    $user_sexus = (empty($user_sexus)) ? 0 : (int)$user_sexus;
    $fields[] = "`user_sexus` = $user_sexus";
    $user_stat = (empty($user_stat)) ? 0 : (int)$user_stat;
    $fields[] = "`user_stat` = $user_stat";

    $setbday = (empty($birthday)) ? "NULL" : "'" . strftime('%Y-%m-%d', $birthday) . "'";
    $fields[] = "`user_bday` = $setbday";

    $user_regdate = "";//mxGetNukeUserregdate();
    $user_regtime = time();
    $fields[] = "`user_regtime` = $user_regtime";

    $fields[] = "`name` = '$name'";
    $fields[] = "`uname` = '$uname'";
    $fields[] = "`email` = '$email'";
    $fields[] = "`url` = '" . mx_urltohtml(mxCutHTTP($url)) . "'";
    $fields[] = "`user_regdate` = '$user_regdate'";
    $fields[] = "`user_icq` = '$user_icq'";
    $fields[] = "`user_aim` = '$user_aim'";
    $fields[] = "`user_yim` = '$user_yim'";
    $fields[] = "`user_msnm` = '$user_msnm'";
    $fields[] = "`user_from` = '$user_from'";
    $fields[] = "`user_occ` = '$user_occ'";
    $fields[] = "`user_intrest` = '$user_intrest'";
    $fields[] = "`bio` = '$bio'";
    $fields[] = "`user_sig` = '$user_sig'";

    $qry = "INSERT INTO `{$user_prefix}_users` SET " . implode(', ', $fields);
    $result = sql_query($qry);
    if (!$result) {
        return mxErrorScreen('Database error, cannot add to users-table <br />(' . sql_error() . ')', "Error");
    }
    $uid = sql_insert_id();

    /* Modulspezifische Useranfügungen durchfuehren */
    pmx_run_hook('user.add', $uid);

    mxRedirect(adminUrl(PMX_MODULE, 'modify', 'chng_uid=' . $uid . '&user_stat=' . $user_stat), _CHANGESAREOK);
}

/**
 * delete()
 *
 * @return
 */
function delete()
{
    global $user_prefix, $prefix;

    if (isset($_POST['udelete']) && isset($_POST['user_stat'])) {
        /* Loeschen ueber Adminmenue */
        switch (true) {
            case $_POST['user_stat'] == 0 && $_POST['uid_0']:
                $chng_uid = $_POST['uid_0'];
                break;
            case $_POST['user_stat'] == 1 && $_POST['uid_1']:
                $chng_uid = $_POST['uid_1'];
                break;
            case $_POST['user_stat'] == 2 && $_POST['uid_2']:
                $chng_uid = $_POST['uid_2'];
                break;
            case $_POST['user_stat'] == -1 && $_POST['uid_3']:
                $chng_uid = $_POST['uid_3'];
                break;
            default:
                $chng_uid = 0;
        }
    } else {
        /* Loeschen ueber Mitgliederliste etc. */
        $chng_uid = (empty($_REQUEST['chng_uid'])) ? 0 : intval($_REQUEST['chng_uid']);
    }

    $udata = mxGetUserDataFromUid($chng_uid);

    if (empty($udata['uid']) || $udata['user_stat'] == -1 || $udata['uid'] === 1) {
        return mxRedirect(adminUrl(PMX_MODULE), _USERNOEXIST);
    }

    include('header.php');
    title(_USERADMIN);
    echo '
		<h2>' . _DELETEUSER . '</h2>
		<div class="card">
			<div class="card-block">
				<div class="alert alert-warning">
					<p>' . _SURE2DELETE . '&nbsp;<strong>' . htmlspecialchars($udata['uname']) . '</strong>?</p>
					<form action="' . adminUrl(PMX_MODULE, 'delete_confirm') . '" method="post">
						<input type="hidden" name="op" value="' . PMX_MODULE . '/delete_confirm" />
						<input type="hidden" name="del_uid" value="' . $udata['uid'] . '" />
						<input type="submit" class="btn btn-warning" name="action" value="' . _YES . '" /> &nbsp;
						<input type="submit" class="btn btn-default" value="' . _NO . '" />
					</form>
				</div>
			</div>
		</div>';
    include('footer.php');
}

/**
 * delete_confirm()
 *
 * @param mixed $pvs
 * @return
 */
function delete_confirm($pvs)
{
    global $user_prefix, $prefix;

    extract($pvs);
    if (!isset($pvs['action']) || $pvs['action'] != _YES) {
        mainusers();
        return;
    }

    $uid = (empty($del_uid)) ? 0 : intval($del_uid);
    if (!$uid || $uid === 1) {
        return mxRedirect(adminUrl(PMX_MODULE), _USERNOEXIST);
    }

    /* Modulspezifische Loeschungen durchfuehren */
    pmx_run_hook('user.delete', $uid);

    mxRedirect(adminUrl(PMX_MODULE), _DELETEAREOK);
}

/**
 * adminuserform()
 *
 * @param array $udata
 * @return
 */
function adminuserform($udata = array())
{
    global $user_prefix, $prefix;
    $udata['user_age'] = (empty($udata['user_age'])) ? '?' : $udata['user_age'];

    $out = '
        <div class="form-group row">
            <label for="email" class="col-sm-2 col-form-label">' . _EMAIL . '</label>
            <div class="col-sm-4">
				<input type="text" class="form-control" id="email" name="email" value="' . ((empty($udata['email'])) ? '' : htmlspecialchars($udata['email'])) . '" size="60" maxlength="100" /> 
            </div>
			<span class="form-text text-muted">' . _REQUIRED . '</span>            
        </div>';
    if ($udata['user_stat'] != -1) {
        $out .= '
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">' . _YA_USERSTAT . '</label>
            <div class="col-sm-1">
				<select class="custom-select mb-2 mr-sm-2 mb-sm-0" name="user_stat">' . getUserStatOptions($udata['user_stat']) . '</select> 
            </div>
            	<span class="form-text text-muted">' . _REQUIRED . '</span>
        </div>';
    }
    $out .= '
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">' . _YA_INGROUP . '</label>
            <div class="col-sm-1">
				<select class="custom-select mb-2 mr-sm-2 mb-sm-0" name="user_ingroup">' . getAllAccessLevelSelectOptions($udata['user_ingroup']) . '</select>			
            </div>
            	<span class="form-text text-muted">' . _REQUIRED . '</span>
        </div>';
    return $out;
    // ToDo: Die Textareas sollten mittels JavaScript eine (angezeigte = Restzeichen) Laengenbegrenzung haben (wie im SMF-Profil).
}

/**
 * reactivate()
 *
 * @return
 */
function reactivate()
{
    if (!isset($_POST['sendmail']) || $_POST['sendmail'] != _YES) {
        return mxRedirect(adminUrl(PMX_MODULE));
    }

    $udata = mxGetUserDataFromUid($_POST['uid']);
    if (empty($udata)) {
        return mxRedirect(adminUrl(PMX_MODULE), _USERNOEXIST);
    }

    if (empty($_POST['message'])) {
        return mxRedirect(adminUrl(PMX_MODULE), sprintf(_YA_REAC_SENDMSGERR, $udata['uname']), 5);
    }

    $subject = sprintf(_YA_REAC_MSGSUBJECT, $GLOBALS['sitename']);
    $message = mxStripSlashes($_POST['message']);

    $ok = mxMail($udata['email'], $subject, $message);
    if ($ok) {
        /* Modulspezifische Useränderungen durchfuehren */
        pmx_run_hook('user.reactivate', $chng_uid);
        return mxRedirect(adminUrl(PMX_MODULE), sprintf(_YA_REAC_SENDMSGOK, $udata['uname']));
    } else {
        return mxRedirect(adminUrl(PMX_MODULE), sprintf(_YA_REAC_SENDMSGERR, $udata['uname']), 8);
    }
}

/**
 * getAllUsersSelectOptions1()
 *
 * @return
 */
function getAllUsersSelectOptions1()
{
    global $user_prefix, $prefix;

    $useroptions = array();
    $qry = "SELECT u.uname, u.uid, u.user_ingroup, u.user_stat, ga.access_title
        FROM `{$user_prefix}_users` AS u
        LEFT JOIN `${prefix}_groups_access` AS ga
        ON u.user_ingroup = ga.access_id
        WHERE u.uname <> 'Anonymous'
        ORDER BY u.uname";
    $result = sql_query($qry);
    if ($result) {
        while (list($uname, $uid, $level, $stat, $grp) = sql_fetch_row($result)) {
            $view = (empty($grp) || $level == 1) ? $uname : $uname . '&nbsp;&raquo;&nbsp;' . $grp;
            $useroptions[$stat][] = '<option value="' . $uid . '">' . $view . '</option>';
        }
    }

    $useroptions = (count($useroptions)==0) ? '<option value="0">No Users available</option>' : $useroptions;
    return $useroptions;
}

/**
 * getUserStatOptions()
 *
 * @param mixed $user_stat
 * @return
 */
function getUserStatOptions($user_stat)
{
    $out = "";
    if (empty($user_stat)) {
        // nur Anzeigen bei neuen Usern
        $out .= "<option value=0" . (($user_stat == 0) ? ' selected="selected" class="current"' : '') . ">" . _YA_USERSTAT_0 . "</option>\n"; # neu, noch nicht aktiviert
    }
    // immer anzeigen
    $out .= "<option value=1" . (($user_stat == 1) ? ' selected="selected" class="current"' : '') . ">" . _YA_USERSTAT_1 . "</option>\n"; # aktiviert
    if (!empty($user_stat)) {
        // nicht Anzeigen bei neuen Usern
        $out .= "<option value=2" . (($user_stat == 2) ? ' selected="selected" class="current"' : '') . ">" . _YA_USERSTAT_2 . "</option>\n"; # deaktiviert
    }
    return $out;
}

switch ($op) {
    case PMX_MODULE . '/modify':
        if (isset($_POST['udelete'])) {
            delete();
        } else {
            modify();
        }
        break;

    case PMX_MODULE . '/update':
        update($_POST);
        break;

    case PMX_MODULE . '/delete':
        delete();
        break;

    case PMX_MODULE . '/delete_confirm':
        delete_confirm($_POST);
        break;

    case PMX_MODULE . '/add':
        add($_POST);
        break;

    case PMX_MODULE . '/reactivate':
        reactivate();
        break;

    default:
        mainusers();
        break;
}

?>