<div class="row">
    <nav>
        <ul class="pager">
            <li class="previous">{{ link_to("poll", "Go Back") }}</li>
        </ul>
    </nav>
</div>

<div class="page-header">
    <h1>
        Create poll
    </h1>
</div>

{{ content() }}

{{ form("poll/create", "method":"post", "autocomplete":"off", "class":"form-horizontal") }}

<div class="form-group">
    <label for="fieldContent" class="col-sm-2 control-label">Content</label>
    <div class="col-sm-10">
        {{ text_area("content", "cols": "30", "rows": "4", "class" : "form-control", "id" : "fieldContent") }}
    </div>
</div>


<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        {{ submit_button('Save', 'class': 'btn btn-default') }}
    </div>
</div>

{{ end_form() }}
