<div class="search-container">
    <form method="POST" action="{{ $searchRoute.(($queryStringNoPage !== '') ? (strpos($searchRoute, '?') !== false ? '&' : '?').$queryStringNoPage : '') }}" accept-charset="UTF-8" id="searchForm" novalidate="novalidate">
        @csrf
        <span class="field search-input-container">
            <input type="text" id="needle" name="needle" value="{{ $needle }}" class="gui-input search-input">
        </span>
        <button id="button-search" class="btn btn-info search-button">
            <i class="fa fa-search mr10"></i>{{ $buttonLabel ?? 'Search' }}
        </button>
        @if (!empty($needle))
            <button class="btn btn-default reset-button equis" type="button" data-route="{{ $cancelRoute . (($queryStringNoPageNoNeedle !== '') ? (strpos($searchRoute, '?') !== false ? '&' : '?').$queryStringNoPageNoNeedle : '') }}">x</button>
        @endif
    </form>
</div>
