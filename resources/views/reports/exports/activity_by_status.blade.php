<html>
<body>
<table>
    <thead>
    <tr>
        <th>@lang('translation.activitybystatusreport')</th>
        <th>@lang('translation.from'): {{ $from }}</th>
        <th>@lang('translation.to'): {{ $to }}</th>
    </tr>
    </thead>
    <tbody>
        @foreach ($rows as $salesPersonName => $row)
            <tr>
                <td>@lang('translation.salesperson')</td>
                <td>{{ $salesPersonName }}</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>@lang('translation.status')</td>
                <td>@lang('translation.numberofjobs')</td>
                <td>@lang('translation.cost')</td>
            </tr>
            <tr>
                <td>Pending</td>
                <td>{{ $row['Pending']['total_jobs'] }}</td>
                <td>{{ $row['Pending']['cost'] }}</td>
            </tr>
            <tr>
                <td>Rejected</td>
                <td>{{ $row['Rejected']['total_jobs'] }}</td>
                <td>{{ $row['Rejected']['cost'] }}</td>
            </tr>
            <tr>
                <td>Active</td>
                <td>{{ $row['Active']['total_jobs'] }}</td>
                <td>{{ $row['Active']['cost'] }}</td>
            </tr>
            <tr>
                <td>Completed</td>
                <td>{{ $row['Completed']['total_jobs'] }}</td>
                <td>{{ $row['Completed']['cost'] }}</td>
            </tr>
            <tr>
                <td>Cancelled</td>
                <td>{{ $row['Cancelled']['total_jobs'] }}</td>
                <td>{{ $row['Cancelled']['cost'] }}</td>
            </tr>
            <tr>
                <td>Billed</td>
                <td>{{ $row['Billed']['total_jobs'] }}</td>
                <td>{{ $row['Billed']['cost'] }}</td>
            </tr>
            <tr>
                <td>Paid</td>
                <td>{{ $row['Paid']['total_jobs'] }}</td>
                <td>{{ $row['Paid']['cost'] }}</td>
            </tr>
            <tr>
                <td>Total</td>
                <td>{{ $salesPersonName }}</td>
                <td>{{ $row['global_cost']}}</td>
            </tr>
        @endforeach
    </tbody>
</table>
</body>
</html>
