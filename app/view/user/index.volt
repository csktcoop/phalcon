<div class="page-header">
    <h1>
        Search user
    </h1>
    <p>
        {{ link_to("user/new", "Create user") }}
    </p>
</div>

{{ content() }}

{{ form("user/search", "method":"post", "autocomplete" : "off", "class" : "form-horizontal") }}

<div class="form-group">
    <label for="fieldId" class="col-sm-2 control-label">Id</label>
    <div class="col-sm-10">
        {{ text_field("id", "size" : 30, "class" : "form-control", "id" : "fieldId") }}
    </div>
</div>

<div class="form-group">
    <label for="fieldUserGroupId" class="col-sm-2 control-label">User Of Group</label>
    <div class="col-sm-10">
        {{ text_field("user_group_id", "size" : 30, "class" : "form-control", "id" : "fieldUserGroupId") }}
    </div>
</div>

<div class="form-group">
    <label for="fieldFirstName" class="col-sm-2 control-label">First Of Name</label>
    <div class="col-sm-10">
        {{ text_field("first_name", "size" : 30, "class" : "form-control", "id" : "fieldFirstName") }}
    </div>
</div>

<div class="form-group">
    <label for="fieldLastName" class="col-sm-2 control-label">Last Of Name</label>
    <div class="col-sm-10">
        {{ text_field("last_name", "size" : 30, "class" : "form-control", "id" : "fieldLastName") }}
    </div>
</div>

<div class="form-group">
    <label for="fieldLogin" class="col-sm-2 control-label">Login</label>
    <div class="col-sm-10">
        {{ text_field("login", "size" : 30, "class" : "form-control", "id" : "fieldLogin") }}
    </div>
</div>

<div class="form-group">
    <label for="fieldPass" class="col-sm-2 control-label">Pass</label>
    <div class="col-sm-10">
        {{ text_field("pass", "size" : 30, "class" : "form-control", "id" : "fieldPass") }}
    </div>
</div>

<div class="form-group">
    <label for="fieldOrdering" class="col-sm-2 control-label">Ordering</label>
    <div class="col-sm-10">
        {{ text_field("ordering", "type" : "numeric", "class" : "form-control", "id" : "fieldOrdering") }}
    </div>
</div>

<div class="form-group">
    <label for="fieldFailedLoginAttempt" class="col-sm-2 control-label">Failed Of Login Of Attempt</label>
    <div class="col-sm-10">
        {{ text_field("failed_login_attempt", "type" : "numeric", "class" : "form-control", "id" : "fieldFailedLoginAttempt") }}
    </div>
</div>

<div class="form-group">
    <label for="fieldLockedDate" class="col-sm-2 control-label">Locked Of Date</label>
    <div class="col-sm-10">
        {{ text_field("locked_date", "size" : 30, "class" : "form-control", "id" : "fieldLockedDate") }}
    </div>
</div>

<div class="form-group">
    <label for="fieldAuthor" class="col-sm-2 control-label">Author</label>
    <div class="col-sm-10">
        {{ text_field("author", "size" : 30, "class" : "form-control", "id" : "fieldAuthor") }}
    </div>
</div>

<div class="form-group">
    <label for="fieldLastEditor" class="col-sm-2 control-label">Last Of Editor</label>
    <div class="col-sm-10">
        {{ text_field("last_editor", "size" : 30, "class" : "form-control", "id" : "fieldLastEditor") }}
    </div>
</div>

<div class="form-group">
    <label for="fieldCreated" class="col-sm-2 control-label">Created</label>
    <div class="col-sm-10">
        {{ text_field("created", "size" : 30, "class" : "form-control", "id" : "fieldCreated") }}
    </div>
</div>

<div class="form-group">
    <label for="fieldModified" class="col-sm-2 control-label">Modified</label>
    <div class="col-sm-10">
        {{ text_field("modified", "size" : 30, "class" : "form-control", "id" : "fieldModified") }}
    </div>
</div>

<div class="form-group">
    <label for="fieldDisable" class="col-sm-2 control-label">Disable</label>
    <div class="col-sm-10">
        {{ text_field("disable", "type" : "numeric", "class" : "form-control", "id" : "fieldDisable") }}
    </div>
</div>


<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        {{ submit_button('Search', 'class': 'btn btn-default') }}
    </div>
</div>

</form>
