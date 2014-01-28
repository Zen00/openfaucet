    <hgroup>
      <h1 class="site_title">{$GLOBAL.website.name|default:"Unknown Faucet"}</h1>
    {if $smarty.session.AUTHENTICATED|default:"0" == 1}  
      <h2 class="section_title">{if $smarty.request.action|escape|default:""}{$smarty.request.action|escape|capitalize}{else}{$smarty.request.page|escape|default:"home"|capitalize}{/if}</h2>
{/if}
    </hgroup>