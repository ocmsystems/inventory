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
        <title>PERIODIC SALES REPORT</title>
    </head>

    <body>
        <div style="text-align:center;font-weight:bold;">PERIODIC SALES REPORT</div>
        <div style="text-align:center;">(from: {{date("M d, Y", strtotime($range[0]))}} - {{date("M d, Y", strtotime($range[1]))}} )</div>
        <br/>
        <table width="100%">
            <thead>
                <tr style="background: #a8a8a8;">
                    <th style="width: 80px;">Transaction Date</th>
                    <th style="width: 100px;">D-SD-C-SC Code</th>
                    <th style="width: 70px;">D-SD-C Desc</th>
                    <th style="width: 250px;">Store Name</th>
                    <th style="width: 50px;">Quantity</th>
                    <th style="width: 90px;">Amount</th>
                    <th style="width: 90px;">MTD Amount</th>
                </tr>
            </thead>
            <tbody>
                @if(count($dates) > 0)
                <?php $mtdAmount = 0; ?>
                @foreach($dates as $item => $value)
                <?php $mtdAmount += $value['amount']; ?>
                <tr>
                    <td>{{$item}}</td>
                    <td></td>
                    <td>TIGER</td>
                    <td>{{ $warehouse->name }}</td>
                    <td style="text-align:right;">{{ number_format($value['quantity'],2) }}</td>
                    <td style="text-align:right;">{{ number_format($value['amount'],2) }}</td>
                    <td style="text-align:right;">{{ number_format($mtdAmount,2) }}</td>
                </tr>
                @endforeach
                @endif
            </tbody>
        </table>

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

