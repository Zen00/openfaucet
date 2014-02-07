<article class="module width_full">
  <header><h3>Transaction Summary</h3></header>
  <table class="tablesorter" cellspacing="0">
    <thead>
      <tr>
    {foreach $SUMMARY as $type=>$total}
        <th>{$type}</th>
    {/foreach}
      </tr>
    </thead>
    <tbody>
      <tr>
    {foreach $SUMMARY as $type=>$total}
        <td class="right">{$total|number_format:"8"}</td>
    {/foreach}
      </tr>
    </tbody>
  </table>
</article>

<article class="module width_quarter">
  <header><h3>Transaction Filter</h3></header>
  <div class="module_content">
  <form action="{$smarty.server.SCRIPT_NAME}">
    <input type="hidden" name="page" value="{$smarty.request.page|escape}" />
    <input type="hidden" name="action" value="{$smarty.request.action|escape}" />
    <table cellspacing="0" class="tablesorter">
    <tbody>
      <tr>
        <td align="left">
{if $smarty.request.start|default:"0" > 0}
          <a href="{$smarty.server.SCRIPT_NAME}?page={$smarty.request.page|escape}&action={$smarty.request.action|escape}&start={$smarty.request.start|escape|default:"0" - $LIMIT}{if $FILTERS|default:""}{$FILTERS}{/if}"><i class="icon-left-open"></i></a>
{else}
          <i class="icon-left-open"></i>
{/if}
        </td>
        <td align="right">
          <a href="{$smarty.server.SCRIPT_NAME}?page={$smarty.request.page|escape}&action={$smarty.request.action|escape}&start={$smarty.request.start|escape|default:"0" + $LIMIT}{if $FILTERS|default:""}{$FILTERS}{/if}"><i class="icon-right-open"></i></a>
        </td>
      </tr>
    </tbody>
  </table>
    <fieldset>
      <label>Type</label>
      {html_options name="filter[type]" options=$TRANSACTIONTYPES selected=$smarty.request.filter.type|default:""}
    </fieldset>
    <fieldset>
      <label>Status</label>
      {html_options name="filter[status]" options=$TXSTATUS selected=$smarty.request.filter.status|default:""}
    </fieldset>
    </div>
  <footer>
    <div class="submit_link">
      <input type="submit" value="Filter" class="alt_btn">
    </div>
  </footer>
</form>
</article>

<article class="module width_3_quarter">
  <header><h3>Recent Transaction History</h3></header>
    <table cellspacing="0" class="tablesorter" width="100%">
      <thead>
        <tr>
          <th align="center">ID</th>
          <th>Date</th>
          <th>TX Type</th>
          <th align="center">Status</th>
          <th>Payment Address</th>
          <th>TX #</th>
          <th>Amount</th>
        </tr>
      </thead>
      <tbody style="font-size:12px;">
{section transaction $TRANSACTIONS}
        <tr class="{cycle values="odd,even"}">
          <td align="center">{$TRANSACTIONS[transaction].id}</td>
          <td>{$TRANSACTIONS[transaction].timestamp}</td>
          <td>{$TRANSACTIONS[transaction].type}</td>
          <td align="center">
            {if $TRANSACTIONS[transaction].type == 'Donation' OR
                $TRANSACTIONS[transaction].type == 'Debit_MP' OR
                $TRANSACTIONS[transaction].type == 'Debit_SP' OR
                $TRANSACTIONS[transaction].confirmations >= $GLOBAL.confirmations
            }<span class="confirmed">Confirmed</span>
            {else}<span class="unconfirmed">Unconfirmed</span>{/if}
          </td>
          <td><a href="#" onClick="alert('{$TRANSACTIONS[transaction].coin_address|escape}')">{$TRANSACTIONS[transaction].coin_address|truncate:20:"...":true:true}</a></td>
          {if ! $GLOBAL.website.transactionexplorer.disabled}
            <td><a href="{$GLOBAL.website.transactionexplorer.url}{$TRANSACTIONS[transaction].txid|escape}" title="{$TRANSACTIONS[transaction].txid|escape}">{$TRANSACTIONS[transaction].txid|truncate:20:"...":true:true}</a></td>
          {else}
            <td><a href="#" onClick="alert('{$TRANSACTIONS[transaction].txid|escape}')" title="{$TRANSACTIONS[transaction].txid|escape}">{$TRANSACTIONS[transaction].txid|truncate:20:"...":true:true}</a></td>
          {/if}
          <td><font color="{if $TRANSACTIONS[transaction].type == 'Credit' or $TRANSACTIONS[transaction].type == 'Credit_PPS' or $TRANSACTIONS[transaction].type == 'Bonus'}green{else}red{/if}">{$TRANSACTIONS[transaction].amount|number_format:"8"}</td>
        </tr>
{/section}
      </tbody>
    </table>
    <footer><p style="margin-left: 25px; font-size: 9px;"><b>Debit_MP</b> = Manual Payout, <b>Debit_SP</b> = Stash Payout, <b>Donation</b> = Donation to faucet</p></footer>
</article>

