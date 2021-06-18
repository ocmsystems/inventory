<html>
    <head>
        <style>
            html{
                padding: 0;
                margin: 30px 10px 0px;
                font-size: 9px;
            }
            body{
                padding: 0;
                margin: 0;
                font-family: "Helvetica Neue", sans-serif;
            }
            table{
                border-collapse:collapse;
                border-color: #a8a8a8;
            }
            table td{
                padding: 2px 5px;
            }
            table th{
                padding: 2px;
            }
            table.heading{
                font-size: 10px;
            }
            table.heading td, table.heading th{
                padding-top: 10px;
            }
        </style>
        <title>DAILY SALES REPORT</title>
    </head>

    <body>
        <div style="text-align:center;font-weight: bold;">DAILY SALES REPORT</div>
        <br/>

        <table class="heading">
            <tr style="margin-bottom: 5px;">
                <th style="width:30px;">OUTLET</th>
                <td style="width:5px;">:</td>
                <td style="width:200px;border-bottom: 1px solid #000;">{{$warehouse->name}}</td>

                <th style="width:50px;text-align:right;">DATE</th>
                <td style="width:5px;">:</td>
                <td style="width:90px;border-bottom: 1px solid #000;">{{$input['date']}}</td>
            </tr>

            <tr style="margin-top: 5px;">
                <th style="width:30px;">SELLER</th>
                <td style="width:5px;">:</td>
                <td style="width:300px;border-bottom: 1px solid #000;">{{$user->name}}</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </table>

        <br/>

        <table width="100%" border="1">
            <thead>
                <tr style="background: #a8a8a8;">
                    <th style="width: 80px;">ITEM</th>
                    <th>ITEM DESCRIPTION</th>
                    <th style="width: 40px;">QTY</th>
                    <th style="width: 100px;">PRICE</th>
                    <th style="width: 100px;">AMOUNT</th>
                </tr>
            </thead>
            <tbody>
                @if(count($result) > 0)
                <?php $tot_qty = 0; ?>
                @foreach($result as $item)
                <?php $tot_qty += $item->quantity; ?>
                <tr>
                    <td>{{$item->product->name}}</td>
                    <td>{{$item->product->description}}</td>
                    <td style="text-align: right;">{{$item->quantity}}</td>
                    <td style="text-align: right;">
                        {{number_format($item->original_price, 2)}}
                        @if( !empty($item->discount ) && $item->discount > 0)
                        ({{-number_format($item->discount, 0)}}%)
                        @endif
                    </td>
                    <td style="text-align: right;">{{number_format($item->amount, 2)}}</td>
                </tr>

                @endforeach
                @else
                <tr>
                    <td colspan="5" style="text-align: center;"><i>------------ NO RECORDS ------------</i></td>
                </tr>
                @endif

                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>&nbsp;</td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="4" style="text-align: right;background: #a8a8a8;">TOTAL SALES</th>
                    <th style="text-align: right;padding: 5px 5px;"> {{ number_format($total_amount, 2) }} </th>
                </tr>
            </tfoot>
        </table>

        <script type="text/php">
            if (isset($pdf)) {
                $text = "{PAGE_NUM} / {PAGE_COUNT}";
                $size = 7;
                $font = $fontMetrics->getFont("Verdana");
                $width = $fontMetrics->get_text_width($text, $font, $size) / 2;

                $x = $pdf->get_width() - ($width - 30);
                $y = $pdf->get_height() - 15;
                $pdf->page_text($x, $y, $text, $font, $size);
            }
        </script>
    </body>

</html>