<html>
    <head>
        <style>
            html{
                padding: 0;
                margin: 50px 20px 0px;
                font-size: 9px;
            }
            body{
                padding: 0;
                margin: 0;
                font-family: "Helvetica Neue", sans-serif;
            }
            table{
                /* border-collapse:collapse;
                border-color: #a8a8a8; */
            }
            table td{
                padding: 5px 5px;
            }
            table th{
                padding: 5px 0px;
            }
        </style>
        <title>PHYSICAL COUNT</title>
    </head>

    <body>

        <div style="font-weight:bold;font-size: 15px;">NEW BENEHLYN ENTERPRISES</div>
        <div>Suite 701-702 Tytana Plaza, Plaza Lorenzo Ruiz, Binondo, Mla</div>
        <div style="text-align:center;font-size: 14px;font-weight:bold;margin-top: 50px;">PHYSICAL COUNT</div>
        <div style="margin-top:10px;font-size: 11px;">AS OF: {{date("F d, Y")}}</div>
        <div style="margin-top:5px;font-size: 11px; text-transform: uppercase;">WAREHOUSE: {{$warehouse->name}}</div>
        <br/>
        <br/>
        <table width="100%">
            <thead>
                <tr>
                    <th style="width: 90px;">Item ID</th>
                    <th style="width: 250px;">Description</th>
                    <th style="width: 70px;">Balance</th>
                    <th style="width: 70px;">Actual</th>
                    <th style="width: 70px;">Variance</th>
                    <th style="width: 70px;">Cost</th>
                    <th style="width: 70px;">Amount</th>
                </tr>
            </thead>
        </table>
        <hr/>
        <table>
            <tbody>
                @foreach($products as $item => $value)
                <tr>
                    <td style="width: 90px;">{{ $value['product_name'] }}</td>
                    <td style="width: 240px;">{{ $value['product_desc'] }}</td>
                    <td style="width: 70px;text-align:right;">{{ $value['on_hand'] }}</td>
                    {{-- <td style="text-align:right;">{{ number_format($value['quantity'],2) }}</td> --}}
                    <td style="width: 60px;text-align:right;">&nbsp;</td>
                    <td style="width: 60px;text-align:right;">&nbsp;</td>
                    <td style="width: 70px;text-align:right;">{{ number_format(0,2) }}</td>
                    <td style="width: 70px;text-align:right;">{{ number_format(0,2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <hr/>
        <div style="font-size: 11px; margin-top:20px;">Run Date: {{ date("m/d/Y h:i A") }}</div>
        <div style="font-size: 11px;margin-top: 5px;">Prepared By: {{ auth()->user()->name }}</div>


        

        <script type="text/php">
            if (isset($pdf)) {
                $text = "PAGE {PAGE_NUM} of {PAGE_COUNT}";
                $size = 7;
                $font = $fontMetrics->getFont("Verdana");
                $width = $fontMetrics->get_text_width($text, $font, $size) / 2;

                $x = $pdf->get_width() - ($width - 20);
                $y = $pdf->get_height() - 15;
                $pdf->page_text($x, $y, $text, $font, $size);
            }
        </script>
    
    </body>
</html>