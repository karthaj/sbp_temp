<table width="100%" cellpadding="0" cellspacing="0" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; background-color: rgb(245, 248, 250); margin: 0; padding: 0; width: 100%">
	<tbody>
		<tr> 
			<td align="center" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box">                 
				<table width="100%" cellpadding="0" cellspacing="0" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; margin: 0; padding: 0; width: 100%"> 
					<tbody>
						<tr> 
							<td style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; padding: 25px 0; text-align: center">         
								<a href="{{ getStoreUrl($return->store) }}" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: rgb(187, 191, 195); font-size: 19px; font-weight: bold; text-decoration: none; text-shadow: 0 1px 0 white" target="_blank">
									 @if($return->store->email_template_customization)
				                        <img src="{{ asset('assets/img/logo_blue.png') }}" width="100" alt="{{ $return->store->store_name }}" />
				                     @else
				                        {{ $return->store->store_name }} 
				                     @endif    
								</a>     
							</td> 
						</tr> 
						<tr> 
							<td width="100%" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; background-color: rgb(255, 255, 255); border-bottom: 1px solid rgb(237, 239, 242); border-top: 1px solid rgb(237, 239, 242); margin: 0; padding: 0; width: 100%">                             
								<table align="center" width="570" cellpadding="0" cellspacing="0" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; background-color: rgb(255, 255, 255); margin: 0 auto; padding: 0; width: 570px"> 
									<tbody>
										<tr> 
											<td style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; padding: 35px">              
												<h1 style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: rgb(47, 49, 51); font-size: 19px; font-weight: bold; margin-top: 0; text-align: left">Return Request Confirmation for Order No {{ $return->order->order_id }}</h1> 
												<p style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: rgb(116, 120, 126); font-size: 16px; line-height: 1.5em; margin-top: 0; text-align: left">{{ $return->store->emails()->where('slug', 'return-confirmation')->first()->email_body }}</p> 
												<div style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box"> 
													<table style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; margin: 30px auto; width: 100%"> <thead style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box">
															<tr> 
																<th style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; border-bottom: 1px solid rgb(237, 239, 242); padding-bottom: 8px; text-align: left"></th> 
																<th style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; border-bottom: 1px solid rgb(237, 239, 242); padding-bottom: 8px"></th> 
															</tr>
														</thead> 
														<tbody style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box">
															<tr> 
																<td style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; font-size: 15px; line-height: 18px; padding: 10px 0; text-align: left"><strong style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box">Reason:</strong></td> 
																<td style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: rgb(116, 120, 126); font-size: 15px; line-height: 18px; padding: 10px 0">{{ $return->reason }}</td> 
															</tr>
														</tbody> 
													</table> 
												</div> 
												<div style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box"> 
													<table style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; margin: 30px auto; width: 100%"> <thead style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box">
															<tr> 
																<th style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; border-bottom: 1px solid rgb(237, 239, 242); padding-bottom: 8px; text-align: left">Items</th> 
																<th style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; border-bottom: 1px solid rgb(237, 239, 242); padding-bottom: 8px">Qty</th> 
															</tr>
														</thead> 
														<tbody style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box">
														@foreach($return->details as $item)
															<tr> 
																<td style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: rgb(116, 120, 126); font-size: 15px; line-height: 18px; padding: 10px 0; text-align: left">{{ $item->orderDetail->product_name }}</td> 
																<td style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: rgb(116, 120, 126); font-size: 15px; line-height: 18px; padding: 10px 0; text-align: center;">{{ $item->quantity }}</td> 
															</tr>
														@endforeach
														</tbody> 
													</table> 
												</div> 
												<p style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: rgb(116, 120, 126); font-size: 16px; line-height: 1.5em; margin-top: 0; text-align: left">Thanks,<br> {{ $return->store->store_name }}</p>                                                                               
											</td>                                 
										</tr> 
									</tbody>
								</table> 
							</td>                     
						</tr> 
						<tr> 
							<td style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box">         
								<table align="center" width="570" cellpadding="0" cellspacing="0" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; margin: 0 auto; padding: 0; text-align: center; width: 570px">
									<tbody>
										<tr> 
											<td align="center" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; padding: 35px">                     
												<p style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; line-height: 1.5em; margin-top: 0; color: rgb(174, 174, 174); font-size: 12px; text-align: center">© {{ date('Y') }} Shopbox. All rights reserved.</p>                 
											</td>             
										</tr>
									</tbody>
								</table> 
							</td> 
						</tr> 
					</tbody>
				</table> 
			</td>         
		</tr>
	</tbody>
</table>