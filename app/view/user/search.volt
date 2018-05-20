<div class="row">
    <nav>
        <ul class="pager">
            <li class="previous">{{ link_to("user/index", "Go Back") }}</li>
            <li class="next">{{ link_to("user/new", "Create ") }}</li>
        </ul>
    </nav>
</div>

<div class="page-header">
    <h1>Search result</h1>
</div>

{{ content() }}

<div class="row">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Id</th>
            <th>User Of Group</th>
            <th>First Of Name</th>
            <th>Last Of Name</th>
            <th>Login</th>
            <th>Pass</th>
            <th>Ordering</th>
            <th>Failed Of Login Of Attempt</th>
            <th>Locked Of Date</th>
            <th>Author</th>
            <th>Last Of Editor</th>
            <th>Created</th>
            <th>Modified</th>
            <th>Disable</th>

                <th></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        {% if page.items is defined %}
        {% for user in page.items %}
            <tr>
                <td>{{ user.id }}</td>
            <td>{{ user.user_group_id }}</td>
            <td>{{ user.first_name }}</td>
            <td>{{ user.last_name }}</td>
            <td>{{ user.login }}</td>
            <td>{{ user.pass }}</td>
            <td>{{ user.ordering }}</td>
            <td>{{ user.failed_login_attempt }}</td>
            <td>{{ user.locked_date }}</td>
            <td>{{ user.author }}</td>
            <td>{{ user.last_editor }}</td>
            <td>{{ user.created }}</td>
            <td>{{ user.modified }}</td>
            <td>{{ user.disable }}</td>

                <td>{{ link_to("user/edit/"~user.id, "Edit") }}</td>
                <td>{{ link_to("user/delete/"~user.id, "Delete") }}</td>
            </tr>
        {% endfor %}
        {% endif %}
        </tbody>
    </table>
</div>

<div class="row">
    <div class="col-sm-1">
        <p class="pagination" style="line-height: 1.42857;padding: 6px 12px;">
            {{ page.current~"/"~page.total_pages }}
        </p>
    </div>
    <div class="col-sm-11">
        <nav>
            <ul class="pagination">
                <li>{{ link_to("user/search", "First") }}</li>
                <li>{{ link_to("user/search?page="~page.before, "Previous") }}</li>
                <li>{{ link_to("user/search?page="~page.next, "Next") }}</li>
                <li>{{ link_to("user/search?page="~page.last, "Last") }}</li>
            </ul>
        </nav>
    </div>
</div>
