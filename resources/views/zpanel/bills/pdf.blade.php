<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Shopbox') }} - Bill</title>
    @if(session('store')->setting->favicon)
    <link rel="apple-touch-icon" href="{{ asset('stores').'/'.session('store')->domain.'/img/'.session('store')->setting->favicon }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('stores').'/'.session('store')->domain.'/img/'.session('store')->setting->favicon }}" />
    @endif
    <style>
      .clearfix:after {
        content: "";
        display: table;
        clear: both;
      }

      a {
        color: #0087C3;
        text-decoration: none;
      }

      body {
        position: relative;
        color: #555555;
        background: #FFFFFF; 
        font-family: Arial, sans-serif; 
        font-size: 14px; 
        font-family: SourceSansPro;
      }

      header {
        padding: 10px 0;
        margin-bottom: 20px;
        border-bottom: 1px solid #AAAAAA;
      }

      #logo {
        float: left;
        margin-top: 8px;
      }

      #logo img {
        height: 70px;
      }

      #company {
        float: right;
        text-align: right;
      }

      #client {
        padding-left: 6px;
        border-left: 6px solid #0087C3;
        float: left;
      }

      #client .to {
        color: #777777;
      }

      h2.name {
        font-size: 1.4em;
        font-weight: normal;
        margin: 0;
      }

      #invoice {
        float: right;
        text-align: right;
      }

      #invoice h1 {
        color: #0087C3;
        font-size: 2.4em;
        line-height: 1em;
        font-weight: normal;
        margin: 0  0 10px 0;
      }

      #invoice .date {
        font-size: 1.1em;
        color: #777777;
      }

      table {
        width: 100%;
        border-collapse: collapse;
        border-spacing: 0;
        margin-bottom: 20px;
      }

      table th,
      table td {
        padding: 20px;
        background: #EEEEEE;
        text-align: center;
        border-bottom: 1px solid #FFFFFF;
      }

      table th {
        white-space: nowrap;        
        font-weight: normal;
      }

      table td {
        text-align: right;
      }

      table td h3{
        color: rgb(4, 117, 170);
        font-size: 1.2em;
        font-weight: normal;
        margin: 0 0 0.2em 0;
      }

      table .no {
        color: #FFFFFF;
        font-size: 1.6em;
        background: rgb(4, 117, 170);
      }

      table .desc {
        text-align: left;
      }

      table .unit {
        background: #DDDDDD;
      }

      table .qty {
      }

      table .total {
        background: rgb(4, 117, 170);
        color: #FFFFFF;
      }

      table td.unit,
      table td.qty,
      table td.total {
        font-size: 1.2em;
      }

      table tbody tr:last-child td {
        border: none;
      }

      table tfoot td {
        padding: 10px 20px;
        background: #FFFFFF;
        border-bottom: none;
        font-size: 1.2em;
        white-space: nowrap; 
        border-top: 1px solid #AAAAAA; 
      }

      table tfoot tr:first-child td {
        border-top: none; 
      }

      table tfoot tr:last-child td {
        color: rgb(4, 117, 170);
        font-size: 1.4em;
        border-top: 1px solid rgb(4, 117, 170); 

      }

      table tfoot tr td:first-child {
        border: none;
      }
      footer {
        color: #777777;
        width: 100%;
        height: 30px;
        position: absolute;
        bottom: 0;
        border-top: 1px solid #AAAAAA;
        padding: 8px 0;
        text-align: center;
      }
      .badge {
        display: inline-block;
        padding: .25em .4em;
        font-size: 75%;
        font-weight: 700;
        line-height: 1;
        text-align: center;
        white-space: nowrap;
        vertical-align: baseline;
        border-radius: .25rem;
        transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;
      }
      .badge-success {
          color: #fff;
          background-color: #28a745;
      }
      .badge-danger {
          color: #fff;
          background-color: #dc3545;
      }
    </style>
  </head>
  <body>
    <table>
      <tbody>
        <tr>
          <td style="text-align: left;">
            <p style="line-height: 1.5;font-size: 1em">
              SHOPBOX (PVT) LTD <br>
              B1501, Royal Park, 115, Lake Drive, <br>
              Rajagiriya, 11017, Sri Lanka. <br>
              +94 753 111 222
            </p>
          </td>
          <td>
            <img src="{{ asset('assets/img/ShopBox_Logo.svg') }}">
          </td>
        </tr>
      </tbody>
    </table>
    <main>
      <div id="details" class="clearfix">
        <table>
          <tbody>
            <tr>
              <td style="text-align: left;">
                  <div class="to">INVOICE TO:</div>
                  <h2 class="name">{{ $billing->address->company }}</h2>
                  <div class="address">
                    {{ $billing->address->address1 }},
                    @if($billing->address->address2)
                    {{ $billing->address->address2 }},
                    @endif
                    {{ $billing->address->city }},
                    @if($billing->address->state)
                    {{ $billing->address->state }},
                    @endif
                    {{ $billing->address->postcode }},{{ $billing->address->country }}
                  </div>
                  <div class="email"><a href="mailto:{{ $billing->store->store_email }}">{{ $billing->store->store_email }}</a></div>
              </td>
              <td style="text-align: left;">
                <h1>BILL #{{ $billing->id }}</h1>
                <div class="date">Date of Bill: {{ $billing->created_at->format('d/m/Y') }}</div>
                <div class="date">Due Date: {{ $billing->store->expiry_date->addDays(5)->format('d/m/Y') }}</div>
                @if($billing->state === 1)
                  <p>Bill Status: <span class="badge badge-success">Paid</span></p>
                @elseif($billing->state === 0)
                  <p>Bill Status: <span class="badge badge-danger">Pending</span></p>
                @endif
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <table border="0" cellspacing="0" cellpadding="0">
        <thead>
          <tr>
            <th class="no">#</th>
            <th class="desc">DESCRIPTION</th>
            <th class="unit">UNIT PRICE</th>
            <th class="qty">QUANTITY</th>
            <th class="total">TOTAL</th>
          </tr>
        </thead>
        <tbody>
          @foreach($billing->items as $item)
          <tr>
            <td class="no">{{ $loop->iteration }}</td>
            <td class="desc">{{ $item->service->name }}</td>
            <td class="unit">LKR {{ number_format($item->amount, 2) }}</td>
            <td class="qty">{{ $item->quantity }}</td>
            <td class="total">LKR {{ number_format($item->amount, 2) }}</td>
          </tr>
          @endforeach
          <tr>
            <td class="no"></td>
            <td class="desc"></td>
            <td class="unit"></td>
            <td class="qty"></td>
            <td class="total"></td>
          </tr>
          <tr>
            <td class="no"></td>
            <td class="desc"></td>
            <td class="unit"></td>
            <td class="qty"></td>
            <td class="total"></td>
          </tr>
          <tr>
            <td class="no"></td>
            <td class="desc"></td>
            <td class="unit"></td>
            <td class="qty"></td>
            <td class="total"></td>
          </tr>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="2"></td>
            <td colspan="2">SUBTOTAL (LKR)</td>
            <td>{{ number_format($billing->amount, 2) }}</td>
          </tr>
          <tr>
            <td colspan="2"></td>
            <td colspan="2">DISCOUNT (LKR)</td>
            <td>{{ number_format($billing->discount_amount, 2) }}</td>
          </tr>
          <tr>
              <td colspan="2"></td>
              <td colspan="2">TAX (LKR)</td>
              <td>{{ number_format($billing->tax, 2) }}</td>
            </tr>
          <tr>
            <td colspan="2"></td>
            <td colspan="2">GRAND TOTAL  (LKR)</td>
            <td>{{ number_format($billing->total_payable) }}</td>
          </tr>
        </tfoot>
      </table>
    </main>
    <footer>
      System Generated invoice and is valid without the signature and seal.
    </footer>
  </body>
</html>