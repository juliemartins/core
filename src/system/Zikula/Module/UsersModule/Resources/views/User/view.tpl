{gt text='Log-in and registration' assign='templatetitle'}
{include file='User/menu.tpl'}

<p>{gt text="Please choose an action:"}</p>
<ul>
    <li><a href="{modurl modname='ZikulaUsersModule' type='user' func='login'}">{gt text="Log in"}</a></li>

    {if $reg_allowreg}
    <li><a href="{modurl modname='ZikulaUsersModule' type='user' func='register'}">{gt text="Register new account"}</a></li>
    {else}
    <li>{gt text="Notice: New user registration is currently disabled."}<br />{gt text="Reasons"}:&nbsp;
    {$reg_noregreasons|safetext}</li>
    {/if}

    <li><a href="{modurl modname='ZikulaUsersModule' type='user' func='lostpwduname'}">{gt text="Recover lost user name or password"}</a></li>
</ul>
