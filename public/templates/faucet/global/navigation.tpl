    <hr/>
    <li class="icon-home"><a href="{$smarty.server.SCRIPT_NAME}">Home</a></li>
    {if $smarty.session.AUTHENTICATED|default:"0" == 1}
    <h3>My Account</h3>
    <ul class="toggle">
      <li class="icon-user"><a href="{$smarty.server.SCRIPT_NAME}?page=account&action=edit">Edit Account</a></li>
    </ul>
    </li>
    {/if}
    {if $smarty.session.AUTHENTICATED|default:"0" == 1 && $GLOBAL.userdata.is_admin == 1}
    <h3>Admin Panel</h3>
    <ul class="toggle">
      <li class="icon-gauge"><a href="{$smarty.server.SCRIPT_NAME}?page=admin&action=dashboard">Dashboard</a></li>
      <li class="icon-bell"><a href="{$smarty.server.SCRIPT_NAME}?page=admin&action=monitoring">Monitoring</a></li>
      <li class="icon-money"><a href="{$smarty.server.SCRIPT_NAME}?page=admin&action=wallet">Wallet Info</a></li>
      <li class="icon-exchange"><a href="{$smarty.server.SCRIPT_NAME}?page=admin&action=transactions">Transactions</a></li>
      <li class="icon-cog"><a href="{$smarty.server.SCRIPT_NAME}?page=admin&action=settings">Settings</a></li>
      <li class="icon-doc"><a href="{$smarty.server.SCRIPT_NAME}?page=admin&action=news">News</a></li>
      <li class="icon-pencil"><a href="{$smarty.server.SCRIPT_NAME}?page=admin&action=templates">Templates</a></li>
    </ul>
{/if}
    <h3>Help</h3>
    <ul class="toggle">
      {if !$GLOBAL.website.about.disabled}
      <li class="icon-doc"><a href="{$smarty.server.SCRIPT_NAME}?page=about&action=pool">About</a></li>
      {/if}
      {if !$GLOBAL.website.donors.disabled}
      <li class="icon-money"><a href="{$smarty.server.SCRIPT_NAME}?page=about&action=donors">Donors</a></li>
      {/if}
    </ul>
    <h3>Other</h3>
    <ul class="toggle">
      {if $smarty.session.AUTHENTICATED|default:"0" == 1}
      <li class="icon-off"><a href="{$smarty.server.SCRIPT_NAME}?page=logout">Logout</a></li>
      {else}
      <li class="icon-login"><a href="{$smarty.server.SCRIPT_NAME}?page=login">Login</a></li>
      {/if}
    </ul>
    <ul>
      <hr/>
    </ul>
