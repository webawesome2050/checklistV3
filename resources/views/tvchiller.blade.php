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
   <center>    TV: Chiller and Freezer Temperature Daily Verification </center>
        </h1>
  
<!--      <p class="text-center"> High Area: Room temperature is set at 10°C, Air Flow Rate should be >5000L/s </p>
        <p class="text-center"> Notify the production manager immediately if any of the settings are not within limit </p> -->

        <table> 
        <tr>    
            <th>Date</th>
            <th>Time </th>
            <th>Base Area Finished Goods Freezer 1 & Freezer 2 new F1</th>
            <th>Base Area Finished Goods Freezer 1 & Freezer 2 new F2</th>
            <th>Base Area    Main Cool Room </th>
            <th>Base Area  Raw Material Chiller</th>
            <th>Medium Area WIP Freezer </th>
            <th>Medium Area Blast Chiller 1 </th>
            <th>Medium Area Blast Chiller 2 </th>
            <th>Medium Area Cooked WIP Chiller </th>
            <th>Medium Area Raw WIP Chiller</th>
            <th>High Risk Area- Blast Freeze</th>
            <th>High Risk Area- Chiller </th>
            <th>Outer cartooning room</th>
            <th>Factory Lunch Room Fridge</th>
            <th>Office Staff Lunch Room Fridge</th>
            <th>Verified by </th>
        </tr>
            <tbody>
            @foreach ($dataBySections as $data)
                    <tr>    
                        <td>{{$data->date}}</td>
                        <td> {{ \Carbon\Carbon::parse($data->time)->format('H:i:s') }}</td>
                        <td>{{$data->base_area_f1}}</td>
                        <td>{{$data->base_area_f2}}</td>
                        <td>{{$data->base_area_cool_room}}</td>
                        <td>{{$data->base_area_raw_chiller}}</td>
                        <td>{{$data->medium_area_cool_freezer}}</td>
                        <td>{{$data->medium_area_cool_chiller1}}</td>
                        <td>{{$data->medium_area_cool_chiller2}}</td>
                        <td>{{$data->medium_area_cool_cooked_wip_chiller}}</td>
                        <td>{{$data->medium_area_cool_wip_chiller}}</td>
                        <td>{{$data->high_area_cool_freezer}}</td>
                        <td>{{$data->high_area_cool_chiller}}</td>
                        <td>{{$data->outer_cartooning_room}}</td>
                        <td>{{$data->factory_lunch_room_fridge}}</td>
                        <td>{{$data->office_staff_lunch_room_fridge}}</td>
                        <td>{{$data->verified_by}}</td>
                    </tr>
            @endforeach 
        </tbody>
</table>

</body>
</html>