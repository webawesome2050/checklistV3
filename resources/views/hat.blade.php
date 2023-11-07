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

        /* h4 {
    margin: 0;
}
.w-full {
    width: 100%;
}
.w-half {
    width: 50%;
}
.margin-top {
    margin-top: 1.25rem;
}
.footer {
    font-size: 0.875rem;
    padding: 1rem;
    background-color: rgb(241 245 249);
}
table {
    width: 100%;
    border-spacing: 0;
}
table.products {
    font-size: 0.875rem;
}
table.products tr {
    background-color: rgb(96 165 250);
}
table.products th {
    color: #ffffff;
    padding: 0.5rem;
}
table tr.items {
    background-color: rgb(241 245 249);
}
table tr.items td {
    padding: 0.5rem;
}
.total {
    text-align: right;
    margin-top: 1rem;
    font-size: 0.875rem;
} */

body {
            font-family: Arial, sans-serif;
            font-size:12px;
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
            font-size: 12;
            margin-bottom: 10px;
        }

          /** Define the margins of your page **/
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

<h1>
   <center>     Form HAT: High Area Temperature and Air Pressure Check Record 1263 Ferntree Gully Rd </center>
        </h1>
        <p class="text-center"> High Area: Room temperature is set at 10°C, Air Flow Rate should be >5000L/s </p>
        <p class="text-center"> Notify the production manager immediately if any of the settings are not within limit </p>

        <table> 
        <tr>    
            <th>Date</th>
            <th>Time </th>
            <th>Room Temperature (°C)</th>
            <th>Air Flow Rate (L/sec)</th>
            <th>Room Pressure (Pa) </th>
            <th>Verified by </th>
        </tr>
            <tbody>
            @foreach ($dataBySections as $data)
                    <tr>    
                        <td>{{$data->date}}</td>
                        <td>{{$data->time}}</td>
                        <td>{{$data->room_temperature}}</td>
                        <td>{{$data->air_flow_rate}}</td>
                        <td>{{$data->room_pressure}}</td>
                        <td>{{$data->verified_by}}</td>
                    </tr>
            @endforeach 
        </tbody>
</table>

</body>
</html>