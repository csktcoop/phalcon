{# comment #}
{{ print.object }}
{{ print['array'] }}
=========================================

{{ javascript_include('js/jquery.js') }}
{{ form('products/save', 'method': 'post') }}
    {{ text_field('name', 'size': 32) }}
    {{ select('type', productTypes, 'using': ['id', 'name']) }}
    {{ submit_button('Send') }}
    {{ TAG() }}
{{ end_form() }}
TAG = [
    check_field
    date_field
    email_field
    end_form
    file_field
    form
    friendly_title
    get_title
    hidden_field
    image
    javascript_include
    link_to
    numeric_field
    password_field
    radio_field
    select
    select_static
    stylesheet_link
    submit_button
    text_area
    text_field
]
===================================

{{ partial('partials/footer', ['links': links]) }}
{% include 'partials/footer' with ['links': links] %}
===================================

OPERATOR = [
    {{ 'hello ' ~ 'world' }}
    {{ 'hello'|FILTER }}
    {{ 'a'..'z' }} {{ 1..10 }}
    {% 'a' in 'abc' %}
    {% var is TEST %}
    {% var is not TEST %}
    {% set var = 'a' ? 'b' : 'c' %}
    {% var++ %}
]
TEST = [
    defined
    divisibleby
    empty
    even
    iterable
    numeric
    odd
    sameas
    scalar
    type {% if value is type('boolean') %}
]
====================================================================

{% for index, item in data %} OR {% for value in numbers if value %}

    {{ loop.index }}
    {{ loop.index0 }}
    {{ loop.revindex }}
    {{ loop.revindex0 }}
    {{ loop.first }}
    {{ loop.last }}
    {{ loop.length }}
    {% continue %}
    {% break %}

{% elsefor %}
    {{ 'data is empty' }}
{% endfor %}
==================

{% if TEST %}

{% elseif TEST %}

{% else %}

{% endif %}
==================

{% switch value %}
    {% case 0 %}
        
        {% break %}
    {% default %}
        
{% endswitch %}
============================

{{ object.property|FILTER }}
FILTER = [
    abs
    capitalize
    convert_encoding {{ 'string'|convert_encoding('utf8', 'latin1') }}
    default
    e
    escape
    escape_attr
    escape_css
    escape_js
    format
    json_encode
    json_decode
    join
    keys
    trim
    left_trim
    right_trim
    length
    lower
    upper
    nl2br
    sort
    stripslashes
    stripslashes
    striptags
    url_encode
]
===================================

{%- macro run_macro(var) %}
    {{ var }}
{%- endmacro %}

{{ run_macro(var) }}

{%- macro msg_macro(message, field, type, class='input-text') %}
    {% return message ~ field ~ type %}
{%- endmacro %}

{{ msg_macro('type': 'Invalid', 'message': 'The name is invalid', 'field': 'name') }}