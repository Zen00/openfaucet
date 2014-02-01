<form action="{$smarty.server.SCRIPT_NAME}" method="post">
  <input type="hidden" name="page" value="{$smarty.request.page|escape}">
  <input type="hidden" name="action" value="{$smarty.request.action|escape}">
  <input type="hidden" name="do" value="updateAccount">
  <article class="module width_half">
    <header><h3>Account Details</h3></header>
    <div class="module_content">
      <fieldset>
        <label>Username</label>
        <input type="text" value="{$GLOBAL.userdata.username|escape}" disabled />
      </fieldset>
      <fieldset>
        <label>E-Mail</label>
        {nocache}<input type="text" name="email" value="{$GLOBAL.userdata.email|escape}" size="20" {if $GLOBAL.twofactor.enabled && $GLOBAL.twofactor.options.details && !$DETAILSUNLOCKED}disabled{/if}/>{/nocache}
      </fieldset>
      <fieldset>
        <label>Cold Stash Address</label>
        {nocache}<input type="text" name="paymentAddress" value="{$smarty.request.paymentAddress|default:$GLOBAL.userdata.coin_address|escape}" size="40"  {if $GLOBAL.twofactor.enabled && $GLOBAL.twofactor.options.details && !$DETAILSUNLOCKED}disabled{/if}/>{/nocache}
      </fieldset>
	    
<fieldset>
<label>Total to Stash</label>
<font size="1">Return a selected amount of {$GLOBAL.config.currency} to an offsite wallet</font>
        {nocache}<input type="text" value=""{if $GLOBAL.twofactor.enabled && $GLOBAL.twofactor.options.details && !$DETAILSUNLOCKED}disabled{/if}/>{/nocache}
</fieldset>
      <fieldset>
        <label>4 digit PIN</label>
        <font size="1">The 4 digit PIN you chose when registering</font>
        <input type="password" name="authPin" size="4" maxlength="4">
      </fieldset>
    </div>
    <footer>
      <div class="submit_link">
      {nocache}
        <input type="hidden" name="ctoken" value="{$CTOKEN|escape|default:""}" />
        <input type="hidden" name="ea_token" value="{$smarty.request.ea_token|escape|default:""}">
        <input type="hidden" name="utype" value="account_edit">
        {if $GLOBAL.twofactor.enabled && $GLOBAL.twofactor.options.details}
          {if $DETAILSSENT == 1 && $DETAILSUNLOCKED == 1}
          	<input type="submit" value="Update Account" class="alt_btn">
          {elseif $DETAILSSENT == 0 && $DETAILSUNLOCKED == 1 || $DETAILSSENT == 1 && $DETAILSUNLOCKED == 0}
            <input type="submit" value="Update Account" class="alt_btn" disabled>
          {elseif $DETAILSSENT == 0 && $DETAILSUNLOCKED == 0}
            <input type="submit" value="Unlock" class="alt_btn" name="unlock">
          {/if}
        {else}
          <input type="submit" value="Update Account" class="alt_btn">
        {/if}
        <input type="hidden" name="wf_token" value="{$smarty.request.wf_token|escape|default:""}">
        <input type="hidden" name="ctoken" value="{$CTOKEN|escape|default:""}" />
        <input type="hidden" name="utype" value="withdraw_funds">
        {if $GLOBAL.twofactor.enabled && $GLOBAL.twofactor.options.withdraw}
          {if $WITHDRAWSENT == 1 && $WITHDRAWUNLOCKED == 1}
          	<input type="submit" value="Cash Out" class="alt_btn">
          {elseif $WITHDRAWSENT == 0 && $WITHDRAWUNLOCKED == 1 || $WITHDRAWSENT == 1 && $WITHDRAWUNLOCKED == 0}
            <input type="submit" value="Cash Out" class="alt_btn" disabled>
          {elseif $WITHDRAWSENT == 0 && $WITHDRAWUNLOCKED == 0}
            <input type="submit" value="Unlock" class="alt_btn" name="unlock">
          {/if}
        {else}
          <input type="submit" value="Stash" class="alt_btn">
        {/if}
      {/nocache}
      </div>
    </footer>
  </article>
</form>



<form action="{$smarty.server.SCRIPT_NAME}" method="post"><input type="hidden" name="act" value="updatePassword">
  <input type="hidden" name="page" value="{$smarty.request.page|escape}">
  <input type="hidden" name="action" value="{$smarty.request.action|escape}">
  <input type="hidden" name="do" value="updatePassword">
  <article class="module width_half">
    <header>
      <h3>Change Password</h3>
    </header>
    <div class="module_content">
      <p style="padding-left:30px; padding-redight:30px; font-size:10px;">
      Note: You will be redirected to login on successful completion of a password change
      </p>
      <fieldset>
        <label>Current Password</label>
        {nocache}<input type="password" name="currentPassword" {if $GLOBAL.twofactor.enabled && $GLOBAL.twofactor.options.changepw && !$CHANGEPASSUNLOCKED}disabled{/if}/>{/nocache}
      </fieldset>
      <fieldset>
        <label>New Password</label>
        <p style="padding-right:10px;display:block;margin-top:0px;float:right;color:#999;" id="pw_strength"></p>
        {nocache}<input type="password" name="newPassword" id="pw_field"{if $GLOBAL.twofactor.enabled && $GLOBAL.twofactor.options.changepw && !$CHANGEPASSUNLOCKED}disabled{/if}/>{/nocache}
      </fieldset>
      <fieldset>
        <label>Repeat New Password</label>
        <p style="padding-right:10px;display:block;margin-top:0px;float:right;" id="pw_match"></p>
        {nocache}<input type="password" name="newPassword2" id="pw_field2"{if $GLOBAL.twofactor.enabled && $GLOBAL.twofactor.options.changepw && !$CHANGEPASSUNLOCKED}disabled{/if}/>{/nocache}
      </fieldset>
      <fieldset>
        <label>4 digit PIN</label>
        <input type="password" name="authPin" size="4" maxlength="4" />
      </fieldset>
    </div>
    <footer>
      <div class="submit_link">
      {nocache}
        <input type="hidden" name="cp_token" value="{$smarty.request.cp_token|escape|default:""}">
        <input type="hidden" name="ctoken" value="{$CTOKEN|escape|default:""}" />
        <input type="hidden" name="utype" value="change_pw">
        {if $GLOBAL.twofactor.enabled && $GLOBAL.twofactor.options.changepw}
          {if $CHANGEPASSSENT == 1 && $CHANGEPASSUNLOCKED == 1}
          	<input type="submit" value="Change Password" class="alt_btn">
          {elseif $CHANGEPASSSENT == 0 && $CHANGEPASSUNLOCKED == 1 || $CHANGEPASSSENT == 1 && $CHANGEPASSUNLOCKED == 0}
            <input type="submit" value="Change Password" class="alt_btn" disabled>
          {elseif $CHANGEPASSSENT == 0 && $CHANGEPASSUNLOCKED == 0}
            <input type="submit" value="Unlock" class="alt_btn" name="unlock">
          {/if}
        {else}
          <input type="submit" value="Change Password" class="alt_btn">
        {/if}
      {/nocache}
      </div>
    </footer>
  </article>
</form>


<form action="{$smarty.server.SCRIPT_NAME}" method="post">
  <input type="hidden" name="page" value="{$smarty.request.page|escape}">
  <input type="hidden" name="action" value="{$smarty.request.action|escape}">
  <input type="hidden" name="do" value="genPin">
  <input type="hidden" name="ctoken" value="{$CTOKEN|escape|default:""}" />
	<article class="module width_half">
	  <header>
		  <h3>Reset PIN</h3>
		</header>
		<div class="module_content">
      <fieldset>
		  <label>Current Password</label>
		  <input type="password" name="currentPassword" />
		  </fieldset>
		</div>
		<footer>
      <div class="submit_link">
        <input type="submit" class="alt_btn" value="Reset PIN">
      </div>
    </footer>
  </article>
</form>
