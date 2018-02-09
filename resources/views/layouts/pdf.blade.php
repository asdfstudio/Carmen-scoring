<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <!--<link rel="stylesheet" href="/css/carmen.css">-->
    <style>
    .page-break {
        page-break-after: always;
    }

    table {
      width: 100%;
      border: 1px solid #ccc;
      margin: 0;
      margin-bottom: 10px;
      padding: 0;
      border-collapse: collapse;
    }

    table tr {
      border: 1px solid #ccc;
      margin: 0;
      padding: 0;
    }

    table td, table th {
      border: 1px solid #ccc;
      margin: 0;
      padding: 10px;
      text-align: left;
    }

    table th {
      background: #ddd;
    }

    ul {
      list-style: none;
      margin: 0;
      padding: 0;
    }
     ul li {
       border: 1px solid #ccc;
       display: block;
       padding: 10px;
       margin-bottom: -1px;
     }

     span.choir {
       float: left;
     }
     span.details {
       float: right;
     }
    </style>
</head>
<body>
  @yield('content')
</body>
</html>
