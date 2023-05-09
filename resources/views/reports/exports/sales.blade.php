<html>
<body>
<table>
    <thead>
    <tr>
        <th>WorkOrder</th>
        <th>Name</th>
        <th>Sales Person</th>
        <th>Client</th>
        <th>County</th>
        <th>Status</th>
        <th>Created By</th>
        <th>Sale Date</th>
        <th>Sale Amount</th>
    </tr>
    </thead>
    <tbody>
        @foreach ($rows as $row)
            <tr>
                <td>{{ $row->work_order_number }}</td>
                <td>{{ $row->name }}</td>
                <td>{{ $row->sales_person_name }}</td>
                <td>{{ $row->client_name }}</td>
                <td>{{ $row->county }}</td>
                <td>{{ $row->status_name }}</td>
                <td>{{ $row->creator_name }}</td>
                <td>{{ $row->html_sale_date }}</td>
                <td>{{ $row->total_cost }}</td>
            </tr>
        @endforeach
        <tr>
            <td>Total Cost:</td>
            <td>{{ $totalCost }}</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    </tbody>
</table>
</body>
</html>
