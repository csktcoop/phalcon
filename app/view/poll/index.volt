<header class="page-header">
    <h1 title="page header">
        Search poll
    </h1>
</header>

{{ content() }}

{{ form("poll/index", "autocomplete" : "off", "class" : "form-horizontal") }}

<div class="form-group">
    <label for="fieldContent" class="col-sm-2 control-label">Content</label>
    <div class="col-sm-10">
        {{ text_area("content", "cols": "30", "rows": "4", "class" : "form-control", "id" : "fieldContent") }}
    </div>
</div>


<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        {{ submit_button('Search', 'class': 'btn btn-default') }}
    </div>
</div>

{{ end_form() }}

{% if page.items is defined %}
<div class="page-header">
    <h1>Search result</h1>
</div>

<div class="row table-responsive">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th></th>

            <th>Content</th>
            <th>Author</th>
            <th>Created date</th>
            </tr>
        </thead>
        <tbody>
        {% if page.items is defined %}
        {% for poll in page.items %}
            <tr>
                <td>{{ link_to("poll/edit/"~poll.id, "Edit", "class":"btn btn-default") }} <button class="btn btn-delete" data-href="{{ "delete/"~poll.id }}" data-toggle="modal" data-target="#modal">Delete</button></td>

            <td>{{ poll.content }}</td>
            <td>{{ poll.author }}</td>
            <td>{{ poll.created }}</td>
            </tr>
        {% endfor %}
        {% else %}
        	<tr>
                <td colspan="4">Empty</td>
            </tr>
        {% endif %}
        </tbody>
    </table>
</div>

{% if page.total_pages is defined %}
<div class="row">
    <div class="col-sm-1">
        <p class="pagination" style="line-height: 1.42857;padding: 6px 12px;">
            {{ page.current~"/"~page.total_pages }}
        </p>
    </div>
    {% if page.total_pages > 1 %}
    <div class="col-sm-11">
        <nav>
            <ul class="pagination">
                <li>{{ link_to("poll/search", "First") }}</li>
                <li>{{ link_to("poll/search?page="~page.before, "Previous") }}</li>
                <li>{{ link_to("poll/search?page="~page.next, "Next") }}</li>
                <li>{{ link_to("poll/search?page="~page.last, "Last") }}</li>
            </ul>
        </nav>
    </div>
    {% endif %}
</div>
{% endif  %}

<div class="row">
    <nav>
        <ul class="pager">
            <li>{{ link_to("poll/new", "Create ") }}</li>
            <li>{{ link_to("poll/index", "Go Back") }}</li>
        </ul>
    </nav>
</div>
{% endif %}