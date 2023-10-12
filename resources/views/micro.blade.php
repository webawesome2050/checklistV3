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

</style>
    <title>PDF</title>
</head>
<body>   
@foreach ($dataBySections as $sectionName => $sectionData)
    <table>
    <tr>
            <th colspan="4" >{{ $sectionName }}</th>
        </tr>
        <tr>    
            <th>Checklist Item</th>
        
            <th>Micro SPC Swab</th>
            <th>Comments & Corrective Actions</th>
            <th>Is Testing Done</th>
     
        </tr>
        @foreach ($sectionData as $subSectionName => $subSectionData)
            @if (is_array($subSectionData))
            <tr>
            <th class="left-text" colspan="4">{{ $subSectionName }}</th>
          </tr>
                @foreach ($subSectionData as $itemData)
                    <tr>
                        <td>{{ $itemData['Checklist Item'] }}</td>
                     
                        <td class="text-center">
                            <center>
                                {{ $itemData['Micro SPC Swab'] }}
                            </center>
                            </td>
                            <td>{{ $itemData['Comments & Corrective Actions'] }}</td>
                            <td>{{ $itemData['Action Taken'] }}</td>
                     
                    </tr>
                @endforeach
            @else
                <tr>
                    <th colspan="4">{{ $subSectionData }}</th>
                </tr>
            @endif
        @endforeach
    </table>
@endforeach 
</body>
</html>