<div class="payout_container">
<form action="{$smarty.server.SCRIPT_NAME}" method="post" class="payout_form">
  <article class="module width_full">
    <header><h3>Request {$GLOBAL.config.payout|number_format:"2"|default:"n/a"} {$GLOBAL.config.currency}</h3></header>
    <div class="module_content">
      <fieldset>
        <label>Your Receipt Address</label>
        <input type="text" name="userAddress" maxlength="64"/>
      </fieldset>
    </div>
    <footer>
      <div class="submit_link">
      {nocache}
      <input type="submit" value="Request {$GLOBAL.config.currency}" class="alt_btn">
      {/nocache}
      </div>
    </footer>
  </article>
</form>



{section name=news loop=$NEWS}
    <article class="module width_half" style="text-align:left;">
      <header style><h3>{$NEWS[news].header}, <font size=\"1px\">posted {$NEWS[news].time|date_format:"%b %e, %Y at %H:%M"}{if $HIDEAUTHOR|default:"0" == 0} by <b>{$NEWS[news].author}</b>{/if}</font></h3></header>
      <div class="module_content">
        {$NEWS[news].content nofilter}
        <div class="clear"></div>
      </div>
    </article>
{/section}
</div>