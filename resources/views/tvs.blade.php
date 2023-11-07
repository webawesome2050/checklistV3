<!doctype html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>

    .left-text {
        text-align:left !important;
    } 
body {
            font-family: Arial, sans-serif;
            font-size:10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;

        }
        table, th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
        }
        h1 {
            font-size: 10;
            margin-bottom: 10px;
        }
          @page {
                margin: 20px 15px;
            }

            header {
                position: fixed;
                top: -60px;
                left: 0px;
                right: 0px;
                height: 30px;

                /** Extra personal styles **/
                background-color: #1dbb90;
                color: white;
                text-align: center;
                line-height: 35px;
            }

            header p{
                margin-top: 8px;
            }

            footer {
                position: fixed; 
                bottom: -60px; 
                left: 0px; 
                right: 0px;
                height: 60px; 
                font-size: 20px !important;
                color: white; !important;

                /** Extra personal styles **/
                background-color: #1dbb90;
                text-align: center;
                line-height: 35px;
            }
            
            .text-center {
                text-align:center;
            }
</style>
    <title>PDF</title>
</head>
<body>   
 <h1>  <center>   TV - Storage Temp  </center> </h1> 
 <table> 
    <tr>    
            <th>Date</th>
            <th>Time </th>
            <th>Temporary storage 1</th>
            <th>Temporary storage 2</th>
            <th>Temporary storage 3 </th>
            <th>Verified by </th>
    </tr>
    <tbody>
            @foreach ($dataBySections as $data)
                    <tr>    
                        <td>{{$data->date}}</td>
                        <td> {{ \Carbon\Carbon::parse($data->time)->format('H:i:s') }}</td>
                        <td>{{$data->temp_storage_1}}</td>
                        <td>{{$data->temp_storage_2}}</td>
                        <td>{{$data->temp_storage_2}}</td> 
                        <td>{{$data->verified_by}}</td>
                    </tr>
            @endforeach 
    </tbody>
</table>
</body>
</html>