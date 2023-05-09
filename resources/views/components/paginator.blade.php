<div {{ $attributes->merge(["class"=>"row pagination-container clearfix text-center "]) }}>
    <div class="col-xs-12 col-sm-4">
        <div class="pull-left pagination-info">
            @if ($collection->total() == 0)
                <span>{{ $lang === 'es' ? 'No hay elementos que mostrar' : 'There is no item to show' }}.</span>
            @else
                @if ($collection->perPage() > $collection->total())
                    <span>{{ $lang === 'es' ? 'Mostrando' : 'Showing' }} {!! $collection->total() !!} {{ Str::plural($lang === 'es' ? 'elemento' : 'item', $collection->total()) }}</span>
                @else
                    <span>{{ $lang === 'es' ? 'Mostrando' : 'Showing' }} {{ $collection->perPage() }} {{ $lang === 'es' ? 'de' : 'of' }} {{ $collection->total() .' '. Str::plural($lang === 'es' ? 'elemento' : 'item', $collection->total()) }}</span>
                @endif
            @endif
        </div>
    </div>
    <div class="col-xs-12 col-sm-4 pagination-pages">
        {{ $lang === 'es' ? 'Elementos por página' : 'Items per page' }}:
        <select id="pageItems" class="form-control">
            @foreach ($pageLimits as $pageLimit)
                <option value="{{ route($routeName, array_merge($queryNoPageNeedleArray, ['perPage' => $pageLimit])) }}"{{ Request::input('perPage') == $pageLimit ? ' selected' : '' }}>{{ $pageLimit }}</option>
            @endforeach
            <option value="{{ route($routeName, array_merge($queryNoPageNeedleArray, ['perPage' => $collection->total()])) }}"{{ Request::input('perPage') == $collection->total() ? ' selected' : '' }}>{{ $lang === 'es' ? 'Todo' : 'All' }}</option>
        </select>
    </div>
    <div class="col-xs-12 col-sm-4 pull-right pagination-handlers text-right">
        @if ($collection->lastPage() > 1)
            <ul class="{!! $uClass !!}">
                <li class="{!! $liClass !!} {!! $liFirstClass !!} {!! $liEdgeClass !!} {{ ($collection->currentPage() == 1) ? $disabledClass : '' }}">
                    <a href="{{ $collection->url(1) . $query }}">{!! $firstCaption !!}</a>
                </li>
                @for ($i = 1; $i <= $collection->lastPage(); $i++)
                    @if ($from < $i && $i < $to)
                        <li class="{!! $liClass !!} {!! $liInnerClass !!} {{ ($collection->currentPage() == $i) ? $selectedClass : '' }}">
                            <a href="{{ $collection->url($i) . $query }}">{{ $i }}</a>
                        </li>
                    @endif
                @endfor
                <li class="{!! $liClass !!} {!! $liLastClass !!} {!! $liEdgeClass !!} {{ ($collection->currentPage() == $collection->lastPage()) ? $disabledClass : '' }}">
                    <a href="{{ $collection->url($collection->lastPage()) . $query }}">{!! $lastCaption !!}</a>
                </li>
            </ul>
        @endif
    </div>
</div>






