<html>
<body>
<table>
    <thead>
    <tr>
        <th>@lang('translation.Source')</th>
        <th>@lang('translation.Leads')</th>
        <th>@lang('translation.Converted')</th>
        <th>@lang('translation.sales')</th>
    </tr>
    </thead>
    <tbody>
        @foreach ($rows as $row)
            <tr>
                <td>{{ $row['source'] }}</td>
                <td>{{ $row['leads'] }}</td>
                <td>{{ $row['converted'] }}</td>
                <td>{{ $row['sales_total'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
</body>
</html>
