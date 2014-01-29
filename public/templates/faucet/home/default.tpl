<div class="payout_form">
<form action="{$smarty.server.SCRIPT_NAME}" method="post">
  <input type="hidden" name="page" value="{$smarty.request.page|escape}">
  <input type="hidden" name="action" value="{$smarty.request.action|escape}">
  <input type="hidden" name="do" value="requestPayout">
  <article class="module width_half">
    <header><h3>Request {$GLOBAL.config.payout} {$GLOBAL.config.currency}</h3></header>
    <div class="module_content">
      <fieldset>
        <label>Your Reciept Address</label>
        <input type="text" name="userRecievingAddress" maxlength="64"/>
      </fieldset>
    </div>
    <footer>
      <div class="submit_link">
      {nocache}
        <input type="hidden" name="ctoken" value="{$CTOKEN|escape|default:""}" />
        <input type="hidden" name="ea_token" value="{$smarty.request.ea_token|escape|default:""}">
        <input type="hidden" name="utype" value="request_coin">
        <input type="submit" value="Request {$GLOBAL.config.currency}" class="alt_btn">
      {/nocache}
      </div>
    </footer>
  </article>
</form>
</div>


{section name=news loop=$NEWS}
    <article class="module width_full">
      <header><h3>{$NEWS[news].header}, <font size=\"1px\">posted {$NEWS[news].time|date_format:"%b %e, %Y at %H:%M"}{if $HIDEAUTHOR|default:"0" == 0} by <b>{$NEWS[news].author}</b>{/if}</font></h3></header>
      <div class="module_content">
        {$NEWS[news].content nofilter}
        <div class="clear"></div>
      </div>
    </article>
{/section}
