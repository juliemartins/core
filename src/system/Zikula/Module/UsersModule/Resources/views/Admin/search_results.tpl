{pageaddvar name='javascript' value='zikula.ui'}
{strip}
{pageaddvarblock}
<script type="text/javascript">
    document.observe("dom:loaded", function() {
        {{if $deleteUsers}}
        $('select_all').observe('click', function(e){
            Zikula.toggleInput('users_searchresults', true);
            e.stop()
        });
        $('deselect_all').observe('click', function(e){
            Zikula.toggleInput('users_searchresults', false);
            e.stop()
        });
        {{/if}}
    });
</script>
{/pageaddvarblock}
{/strip}

{adminheader}
<h3>
    <span class="fa fa-list"></span>
    {gt text="Search results"}
</h3>

<form id="users_searchresults" class="form-horizontal" role="form" method="post" action="{modurl modname="Users" type="admin" func="deleteUsers"}">
    <div>
        <input type="hidden" name="csrftoken" value="{insert name='csrftoken'}" />
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    {if $deleteUsers}
                    <th>&nbsp;</th>
                    {/if}
                    <th>{gt text="User name"}</th>
                    {if $modvars.ZConfig.profilemodule}
                    <th>{gt text="Internal name"}</th>
                    {/if}
                    <th>{gt text="E-mail address"}</th>
                    <th class="text-right">{gt text="Actions"}</th>
                </tr>
            </thead>
            <tbody>
                {section name=item loop=$items}
                <tr>
                    {if $deleteUsers}
                    <td>{if ($items[item].uid != 1) && ($items[item].uid != 2)}<input type="checkbox" name="userid[]" value="{$items[item].uid}" />{/if}</td>
                    {/if}
                    <td>{$items[item].uname}</td>
                    {if $modvars.ZConfig.profilemodule}
                    <td>{usergetvar name='realname' uid=$items[item].uid}</td>
                    {/if}
                    <td>
                        {if !empty($items[item].email) && ($items[item].uid != 1)}
                        {$items[item].email}
                        {/if}
                    </td>
					<td class="actions">
						{gt text="Edit '%s'" tag1=$items[item].uname assign='title'}
						{if $actions[item].modifyUrl}<a class="fa fa-pencil tooltips" href="{$actions[item].modifyUrl|safehtml}" title="{$title}"></a>{/if}
						{gt text="Delete '%s'" tag1=$items[item].uname assign='title'}
                        {if $actions[item].deleteUrl}<a class="fa fa-trash-o fa-fw tooltips" href="{$actions[item].deleteUrl|safehtml}" title="{$title}"></a>{/if}
                    </td>
                </tr>
                {/section}
            </tbody>
        </table>

        {if $deleteUsers}
        <p>
            <a href="#" id="select_all">{gt text="Select all"}</a> / <a href="#" id="deselect_all">{gt text="De-select all"}</a>
        </p>
        {/if}

        <div class="form-group">
            <div class="col-lg-offset-3 col-lg-9">
            {if $deleteUsers}
                {button type='submit' class="btn btn-warning"  __alt="Delete selected users" __title="Delete selected users" __text="Delete selected users"}
            {/if}
                <a class="btn btn-default" href="{modurl modname='ZikulaUsersModule' type='admin' func='index'}" title="{gt text='Return to User Administration'}">{gt text='Return to User Administration'}</a>
            </div>
        </div>
    </div>
</form>
{adminfooter}