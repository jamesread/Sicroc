<div class = "controlPanel">
{if hasPriv('USER_MANAGEMENT')}
<h2>Users and Usergroups</h2>
<a href = "?pageIdent=USER_LIST">Users</a>
<a href = "?pageIdent=USERGROUP_LIST">Usergroups</a>
<a href = "?pageIdent=USERGROUP_ASSIGN">Assign User To Group</a>
{/if}

{if hasPriv('PAGE_STRUCTURE')}
<h2>Page Structure</h2>
<a href = "?pageIdent=NAVIGATION_LIST">Navigation</a>
<a href = "?pageIdent=PAGE_LIST">Pages</a>
<a href = "?pageIdent=WIDGET_LIST">Widget Instances</a>
<a href = "?pageIdent=WIDGET_TYPES_LIST">Widget Types</a>
{/if}

{if hasPriv('DATASOURCES')}
<h2>Datasources and Tables</h2>
<a href = "?pageIdent=TABLE_CONFIGURATION_LIST">Table Configurations</a>
{/if}

{if hasPriv('ADMIN')}
<h2>Admin</h2>
<a href = "?pageIdent=SETTINGS">Site Settings</a>
<a href = "setup.php">Rerun Setup</a>
{/if}
</div>
