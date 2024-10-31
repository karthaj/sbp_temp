<div class="col-lg-5 col-md-5 col-ms-12 d-none d-lg-block d-xl-block">
  <div class="card-header text-center">
      <small>Basket</small>
  </div>
  <div class="card card-default">
    <div class="card-block">
      <div class="scrollable">
        <div class="product-card-scrollable">
          <table class="product-table">
            <thead>
                <th></th>
                <th></th>
                <th></th>
            </thead>
            <tbody>
            @foreach($order->details as $item)
              <tr class="product">
                  <td>
                    <div class="product-thumbnail">
                      <img src="{{ $item->image }}" :alt="item.name" class="img-fluid rounded">
                    </div>
                  </td>
                  <td class="product__title">
                      <span>{{ $item->product_name }}</span>
                      <small>{{ $item->product_quantity }} x {{ $currency }} {{ number_format($item->unit_price, 2) }}</small>
                  </td>
                  <td class="product__price">
                      <span>{{ $currency }} {{ number_format($item->total_price, 2) }}</span>
                  </td>
              </tr>
            @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
      
    <div class="col-md-12 pt-3">
         <table class="total-line-table">
        <thead>
          <tr>
            <th></th>
            <th></th>
          </tr>
        </thead>
          <tbody class="total-line-table__tbody">
            <tr class="total-line total-line--subtotal">
              <th class="total-line__name" scope="row">Subtotal</th>
              <td class="total-line__price">
                <span>{{ $currency }} {{ number_format($order->total_products, 2) }}</span>
              </td>
            </tr>
            @if($order->total_discounts && $order->cart_discount)
              <tr class="total-line total-line--discount">
                <th class="total-line__name" scope="row">Discount <span>[{{ $order->cart_discount->name }}] </span></th>
                <td class="total-line__price">
                  <span>
                    -{{ $currency }} {{ number_format($order->total_discounts, 2) }}
                  </span>
                </td>
              </tr>
            @endif
            @if($order->shipper)
              <tr class="total-line total-line--shipping">
                <th class="total-line__name" scope="row">Shipping</th> 
                <td class="total-line__price">
                  @if($order->total_shipping > 0)
                    <span>{{ $currency }} {{ number_format($order->total_shipping, 2) }}</span>
                  @else
                    <span>Free</span>
                  @endif
                </td>
              </tr>
            @endif
            <tr class="total-line total-line--taxes">
              <th class="total-line__name" scope="row">Tax</th>
              <td class="total-line__price">
                <span>{{ $currency }} {{ number_format($order->total_products_wt - $order->total_products, 2) }}</span>
              </td>
            </tr>
            @if($order->surcharge > 0)
              <tr class="total-line">
                <th class="total-line__name" scope="row">Surcharge</th>
                <td class="total-line__price">
                  <span>{{ $currency }} {{ number_format($order->surcharge, 2) }}</span>
                </td>
              </tr>
            @endif
            @if($order->store_credits > 0)
              <tr v-if="store_credits" class="total-line">
                <th class="total-line__name" scope="row">Store Credit</th>
                <td class="total-line__price">
                  <span>-{{ $currency }} {{ number_format($order->store_credits, 2) }}</span>
                </td>
              </tr>
            @endif
          </tbody>
          <tfoot class="total-line-table__footer">
            <tr class="total-line">
              <th class="total-line__name payment-due-label" scope="row">
                <span class="payment-due-label__total">Total</span>
              </th>
              <td class="total-line__price payment-due">
                <span class="payment-due__price">
                  {{ $currency }} {{ number_format($order->total_paid, 2) }}
                </span>
              </td>
            </tr>
          </tfoot>
      </table>
    </div>
  </div>
</div>