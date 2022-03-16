<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
            overflow: hidden;
            background-color: #333;
        }

        #openAgro {
            float: left;
            display: block;
            background-color: white;
            border: 4px solid #333;
            color: #333;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
            border-bottom-right-radius: 25px;
            border-top-left-radius: 25px;
        }

        #userName {
            float: right;
            display: block;
            color: white;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin: 20px;
        }

        th, td {
            text-align: left;
            padding: 8px;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

<ul>
    <li>
        <div id="openAgro"><b style="color: green;">OpenAgro</b> Notifications</div>
    </li>
    <li>
        <div id="userName"><b>User: </b>{{ $user }}</div>
    </li>
</ul>

<div style="overflow-x:auto;">
    <table>
        <tr>
            <th>Datetime</th>
            <th>Message</th>
            <th>Affected cultivations</th>
        </tr>

        @foreach($notifications as $notification)
            <tr>
                <td>{{date('d-m-Y h:i:s', strtotime($notification->created_at))}}</td>
                <td>{{$notification->message}}</td>
                <td> @foreach($notification->cultivations()->get() as $cultivation)
                        {{$cultivation->name}},
                    @endforeach</td>
            </tr>
        @endforeach
    </table>
</div>

<div>
    <p>To see more please visit the website <a style="color: green;" href="http://openagro.eu/">OpenAgro</a></p>
</div>
</body>
</html>
