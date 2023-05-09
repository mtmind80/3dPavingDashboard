<form method="POST" action="{{ $url }}" accept-charset="UTF-8" id="deleteForm">
    @csrf
    <input name="_method" type="hidden" value="DELETE">
    <input id="form_delete_item_id" name="item_id" type="hidden">
    <input name="http_query" type="hidden" value="{{ http_build_query(\Request::except(['_token'])) }}">
</form>
