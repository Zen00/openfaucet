{if !$GLOBAL.website.about.disabled && $GLOBAL.config.disable_contactform|default:"0" != 1}    
<p><strong>Visit</strong> the <a href="{$smarty.server.SCRIPT_NAME}?page=about">About</a> page to learn more about {$GLOBAL.config.currency}, or <a href="{$smarty.server.SCRIPT_NAME}?page=contactform">Contact</a> us.</p>
{/if}
{if !$GLOBAL.website.about.disabled && $GLOBAL.config.disable_contactform|default:"0" == 1}    
<p><strong>Visit</strong> the <a href="{$smarty.server.SCRIPT_NAME}?page=about">About</a> page to learn more about {$GLOBAL.config.currency}.</p>
{/if}
{if $GLOBAL.website.about.disabled && $GLOBAL.config.disable_contactform|default:"0" != 1}    
<p>We would love your feedback, please <a href="{$smarty.server.SCRIPT_NAME}?page=contactform">Contact</a> us.</p>
{/if}

<p><strong>OpenFaucet</strong> by Zen00, available on <a href="https://github.com/Zen00/openfaucet">GitHub</a></p>
    <p>Please <strong>Donate</strong> to Zen00 LTC: LUdR1pN1M3eCpi2Gb1DKDzJhzmgqsGiZKk</p>
    <p><strong>Copyright &copy; 2014 Grant Brown</strong>, Theme by <a href="https://github.com/MPOS/php-mpos">TheSerapher</a></p>
    {if $DEBUG > 0}
    <div id="debug">
      {nocache}{include file="system/debugger.tpl"}{/nocache}
    </div>
    {/if}
