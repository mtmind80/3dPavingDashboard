<!DOCTYPE html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        @page {
            margin: 0;
        }
        body {
            margin: 0;
            padding: 250px 30px 160px 45px;
            background-image: url({{ $img64 }});
            background-size: cover;
        }
        table {
            width: 100%;
        }
        thead tr {
            background-color: #3a7bea;
            color: #fff;
        }
        thead tr td {
            color: #fff;
        }
        th,
        td {
            vertical-align: middle;
            text-align: center;
        }
        th.tl,
        td.tl {
            text-align: left;
        }
    </style>
</head>
<body>
<table>
    <thead>
    <tr>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Email</th>
        <th>Sales Goals</th>
    </tr>
    </thead>
    <tbody>
        @foreach ($users as $user)
            <tr>
                <td>{{ $user->fname }}</td>
                <td>{{ $user->lname }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->sales_goals }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
</body>
</html>
