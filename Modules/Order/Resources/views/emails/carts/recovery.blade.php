
<table width="100%" cellpadding="0" cellspacing="0" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; background-color: rgb(245, 248, 250); margin: 0; padding: 0; width: 100%">
	<tbody>
		<tr> 
			<td align="center" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box">                 
				<table class="x_1698532521content" width="100%" cellpadding="0" cellspacing="0" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; margin: 0; padding: 0; width: 100%"> 
					<tbody>
						<tr> 
							<td class="x_1698532521header" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; padding: 25px 0; text-align: center">         
								<a href="{{ getStoreUrl($cart->store) }}" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: rgb(187, 191, 195); font-size: 19px; font-weight: bold; text-decoration: none; text-shadow: 0 1px 0 white" target="_blank">{{ title_case($cart->store->store_name) }}</a>     
							</td> 
						</tr> 
						<tr> 
							<td class="x_1698532521body" width="100%" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; background-color: rgb(255, 255, 255); border-bottom: 1px solid rgb(237, 239, 242); border-top: 1px solid rgb(237, 239, 242); margin: 0; padding: 0; width: 100%">                      	<table class="x_1698532521inner-body" align="center" width="570" cellpadding="0" cellspacing="0" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; background-color: rgb(255, 255, 255); margin: 0 auto; padding: 0; width: 570px"> 
									<tbody>
										<tr> 
											<td class="x_1698532521content-cell" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; padding: 35px">                                         
												<p style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: rgb(116, 120, 126); font-size: 16px; line-height: 1.5em; margin-top: 0; text-align: left">{{ $cart->store->emails()->where('slug', 'abandoned-cart-notification')->first()->email_body }}</p> 
													<table class="x_1698532521action" align="center" width="100%" cellpadding="0" cellspacing="0" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; margin: 30px auto; padding: 0; text-align: center; width: 100%">
														<tbody>
															<tr> 
																<td align="center" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box">             
																	<table width="100%" border="0" cellpadding="0" cellspacing="0" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box">
																		<tbody>
																			<tr> 
																				<td align="center" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box">            
																					<table border="0" cellpadding="0" cellspacing="0" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box">
																						<tbody>
																							<tr> 
																								<td style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box">    
																									<a href="{{ url('carts/'.$cart->reference.'/recover') }}" class="x_1698532521button x_1698532521button-blue" target="_blank" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; border-radius: 3px; box-shadow: 0 2px 3px rgba(0, 0, 0, 0.16); color: rgb(255, 255, 255); display: inline-block; text-decoration: none; background-color: rgb(48, 151, 209); border-top: 10px solid rgb(48, 151, 209); border-right: 18px solid rgb(48, 151, 209); border-bottom: 10px solid rgb(48, 151, 209); border-left: 18px solid rgb(48, 151, 209)">Complete you order</a>                             </td>                             
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
													<p style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: rgb(116, 120, 126); font-size: 16px; line-height: 1.5em; margin-top: 0; text-align: left">Thanks,<br> {{ title_case($cart->store->store_name) }}</p>                                                                               
											</td>                                 
										</tr> 
									</tbody>
								</table> 
							</td>                     
						</tr> 
						<tr> 
							<td style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box">         
								<table class="x_1698532521footer" align="center" width="570" cellpadding="0" cellspacing="0" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; margin: 0 auto; padding: 0; text-align: center; width: 570px">
									<tbody>
										<tr> 
											<td class="x_1698532521content-cell" align="center" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; padding: 35px">                     
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