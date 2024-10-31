@extends('layouts.pos')

@section('content')		

<div class="container-fluid mb-2 pos-navbar">
    <div class="row no-gutters align-items-center">
        <div class="col-md-4 col-4 brand-logo">
            <img src="{{ asset('assets/img/adiantz.png') }}">
        </div>
        <div class="col-8 text-white">
            <div class="row no-gutters align-items-center mNav">
              <div class="col-3 text-center mb-0">
                   
               </div>
               <div class="col-3 text-center mb-0">
                   <a href="#" class="mBtn" data-toggle="modal" data-target="#cartHold"><i class="aapl-hand"></i> HOLD</a>  
               </div>
               <div class="col-3 text-center mb-0">
                   <a href="#" class="mBtn" data-toggle="modal" data-target="#cartCancel"><i class="aapl-broom"></i> CANCEL</a> 
               </div>
               <div class="col-3 text-center mb-0">
                   <a class="mBtn" href="#"><i class="aapl-exit"></i>LOGOUT</a> 
               </div>
                
            </div>
        </div>
        
    </div>
    
</div>
 

<main role="main" class="container-fluid">
    
    <section class="col-md-12">
            <div class="row mb-0">
                <div class="col-md-6 card mb-0 bg-grayscale d-none d-md-block d-lg-block">
                    <div class="row customer mt-2">
                        <div class="col-md-2 text-center">
                            <i class="aapl-user"></i>
                            <span>customer</span>
                        </div>
                        <div class="col-md-10">
                            <h6>Dhayabaran Krishnan</h6>
                        </div>
                    </div>    
                </div>
                
                <div class="col-md-6 card mb-0 bg-grayscale">
                   <div class="row form-group mt-2">
                       <div class="col-12">
                            <input id="productSearch" class="form-control form-control-sm" type="text" placeholder="BARCODE/SKU/PRODUCT NAME">    
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-6"><em>(500 products)</em></div>
                        <div class="col-6 text-right">
                            <a href="#productCategory" role="button" class="abtn" data-toggle="modal">
                                <span>ALL PRODUCTS</span>
                            </a>
                        </div>
                    </div> 
                </div>                                                                  
            </div>                         
        </section>

    <hr class="hrSmall">

    <section id="middleDiv" class="col-md-12">
        <div class="row">
            <div class="col-md-6 basket d-none d-md-block d-lg-block card">
                <div class="row bg-dark-gray basketHead">

                    <div class="col-2 text-center">QTY</div>
                    <div class="col-5 text-center">ITEM</div>
                    <div class="col-2 text-right">U/PRICE</div>
                    <div class="col-3 text-center">L/TOTAL</div>
                </div>
                
                <div class="row mt-0">
                    <div class="col-12 mt-3">
                        <ul class="list-unstyled">
                            <li class="" >
                                <div class="row no-gutters align-items-center">
                                    <div class="col-2">
                                        <div class="pImg">
                                            <img class="rounded-circle" src="https://dozzart.com/758-large_default/peinture-acrylique-art-brut-98x98-cm.jpg" width="50" alt="SKU">
                                            <a href="#" data-target="#productDetail" data-toggle="modal">
                                                <div class="pQty">100</div>   
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-7">
                                        <p class="word-wrap mb-0">ProductName13ProductName26Product</p>
                                            <div class="row">
                                                <div class="col-7"><span>(<em>SKU1234567890</em>)</span></div>
                                                <div class="col-5 text-right"><span>100,000.00</span></div>
                                            </div>
                                    </div>
                                    <div class="col-3 text-right">
                                        <p><b>1,000,000.00</b></p>
                                    </div>
                                </div><hr>
                            </li>
                        </ul>
                    </div>
                </div>
               
                   
                    
                 
                    
                 
                    
                </div>
                         
            <div class="col-md-6 product">  
                <div class="row">                        
                    <div class="col-lg-3 col-md-4 col-sm-4 col-6 mb-2">
                        <div class="card product-card">
                            <div class="card-img">
                            <a href="#"><img class="card-img-top" src="https://dozzart.com/758-large_default/peinture-acrylique-art-brut-98x98-cm.jpg" alt="SKU"></a>
                            </div>
                            <div class="product-price"><span>100,000.00</span></div>
                            <div class="product-title">Product Name Goes Here</div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-4 col-6 mb-2">
                        <div class="card product-card">
                            <div class="card-img">
                            <a href="#"><img class="card-img-top" src="https://via.placeholder.com/500x500.png?text=ShopBox" alt="SKU"></a>
                            </div>
                            <div class="product-price"><span>100,000.00</span></div>
                            <div class="product-title">Product Name Goes Here</div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-4 col-6 mb-2">
                        <div class="card product-card">
                            <div class="card-img">
                            <a href="#"><img class="card-img-top" src="https://via.placeholder.com/98x98.png?text=ShopBox" alt="SKU"></a>
                            </div>
                            <div class="product-price"><span>100,000.00</span></div>
                            <div class="product-title">Product Name Goes Here</div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-4 col-6 mb-2">
                        <div class="card product-card">
                            <div class="card-img">
                            <a href="#"><img class="card-img-top" src="https://via.placeholder.com/98x98.png?text=ShopBox" alt="SKU"></a>
                            </div>
                            <div class="product-price"><span>100,000.00</span></div>
                            <div class="product-title">Product Name Goes Here</div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-4 col-6 mb-2">
                        <div class="card product-card">
                            <div class="card-img">
                            <a href="#"><img class="card-img-top" src="https://via.placeholder.com/98x98.png?text=ShopBox" alt="SKU"></a>
                            </div>
                            <div class="product-price"><span>100,000.00</span></div>
                            <div class="product-title">Product Name Goes Here</div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-4 col-6 mb-2">
                        <div class="card product-card">
                            <div class="card-img">
                            <a href="#"><img class="card-img-top" src="https://via.placeholder.com/98x98.png?text=ShopBox" alt="SKU"></a>
                            </div>
                            <div class="product-price"><span>100,000.00</span></div>
                            <div class="product-title">Product Name Goes Here</div>
                        </div>
                    </div>
                </div>
             </div>                   
        </div>    
    </section>
    
    
    <section id="footer" class="container-fluid no-gutters">
           <div class="col-md-12">
            <div class="row no-gutters">
                <div class="col-md-2 d-none d-md-block d-lg-block card bg-dark-gray CartTaxes text-center text-white">
                    <b>10,000.000</b>
                    <span>TAXES (LKR)</span>
                </div>
                <div class="col-md-3 d-none d-md-block d-lg-block card bg-dark-gray CartSubtotal text-center text-white">
                    <b>10,000.000</b>
                    <span>SUBTOTAL (LKR)</span>
                </div>
                <div class="col-md-1 d-none d-md-block d-lg-block text-center bg-dark-gray CartItems text-white">
                    <b>1,000</b>
                    <span><i class="aapl-bag2"></i> ITEMS</span>
                </div>
                <div class="col-sm-3 col-3 card bg-black text-center d-md-none">
                    <a class="btn btn-block btn-basket" data-toggle="modal" data-target="#basketItems">
                        <i class="aapl-bag2"></i>
                        <span class="mt-1">BASKET</span>
                    </a>
                </div>
                <div class="col-md-3 col-6 card bg-black CartValue text-center">
                    <b>10,000.000</b>
                    <span>CART VALUE (LKR)</span>
                    
                </div>
                <div class="col-md-3 col-3 card bg-black text-center">
                    <a class="btn btn-checkout" data-toggle="modal" data-target="#checkOut">
                        <i class="aapl-cashier"></i>
                        <span class="mt-1">CHECKOUT</span>
                    </a>
                </div>      
            </div>
            </div>
        </section>

</main><!-- /.container -->

<!-- Cart Cancel Modal -->                 
<div id="cartCancel" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cart Cancel</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    
                    
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Cart Cancel Modal -->
   
<!-- Cart Hold Modal -->                 
<div id="cartHold" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cart Hold</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    
                    
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Cart Hold Modal -->
  
<!-- Basket Items Modal -->                 
<div id="basketItems" class="modal left fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Basket Items</h5>
                
                <button type="btn-xs" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="false">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="row no-gutters">
                    <div class="col-2 mt-2 text-center">
                        <a href="#" data-target="#searchCustomers" data-toggle="modal"><i class="aapl-user"></i>
                        <smal>CHANGE</smal>
                        </a>
                    </div>
                    <div class="col-10 pl-2">
                        
                        <h6 class="word-wrap">Customer name will be printed here</h6>
                        
                    </div>
                </div>
                <hr class="hrSmall">
                
                <hr class="hrSmall">
                
                
                <div class="row mBasketItems mt-2">
                    <div class="col-12 mt-3">
                        <ul class="list-unstyled">
                            <li class="" >
                                <div class="row no-gutters align-items-center">
                                    <div class="col-2">
                                        <div class="pImg">
                                            <img class="rounded-circle" src="https://dozzart.com/758-large_default/peinture-acrylique-art-brut-98x98-cm.jpg" width="50" alt="SKU">
                                            <a href="#" data-target="#productDetail" data-toggle="modal">
                                                <div class="pQty">100</div>   
                                            </a>
                                            
                                        </div>
                                    </div>
                                    <div class="col-7">
                                        <p class="word-wrap mb-0">ProductName13ProductName26ProductName39</p>
                                        <span>(<em>SKU1234567890</em>)</span>
                                        <span class="pl-1">100,000.00</span>
                                    </div>
                                    <div class="col-3">
                                        <p>1,000,000.00</p>
                                    </div>
                                </div><hr>
                                <div class="row no-gutters align-items-center">
                                    <div class="col-2">
                                        <div class="pImg">
                                            <img class="rounded-circle" src="https://dozzart.com/758-large_default/peinture-acrylique-art-brut-98x98-cm.jpg" width="50" alt="SKU">
                                            <a href="#" data-target="#productDetail" data-toggle="modal">
                                                <div class="pQty">100</div>   
                                            </a>
                                            
                                        </div>
                                    </div>
                                    <div class="col-7">
                                        <p class="word-wrap mb-0">ProductName13ProductName26ProductName39</p>
                                        <span>(<em>SKU1234567890</em>)</span>
                                        <span class="pl-1">100,000.00</span>
                                    </div>
                                    <div class="col-3">
                                        <p>1,000,000.00</p>
                                    </div>
                                </div><hr>
                                <div class="row no-gutters align-items-center">
                                    <div class="col-2">
                                        <div class="pImg">
                                            <img class="rounded-circle" src="https://dozzart.com/758-large_default/peinture-acrylique-art-brut-98x98-cm.jpg" width="50" alt="SKU">
                                            <a href="#" data-target="#productDetail" data-toggle="modal">
                                                <div class="pQty">100</div>   
                                            </a>
                                            
                                        </div>
                                    </div>
                                    <div class="col-7">
                                        <p class="word-wrap mb-0">ProductName13ProductName26ProductName39</p>
                                        <span>(<em>SKU1234567890</em>)</span>
                                        <span class="pl-1">100,000.00</span>
                                    </div>
                                    <div class="col-3">
                                        <p>1,000,000.00</p>
                                    </div>
                                </div><hr>
                                <div class="row no-gutters align-items-center">
                                    <div class="col-2">
                                        <div class="pImg">
                                            <img class="rounded-circle" src="https://dozzart.com/758-large_default/peinture-acrylique-art-brut-98x98-cm.jpg" width="50" alt="SKU">
                                            <a href="#" data-target="#productDetail" data-toggle="modal">
                                                <div class="pQty">100</div>   
                                            </a>
                                            
                                        </div>
                                    </div>
                                    <div class="col-7">
                                        <p class="word-wrap mb-0">ProductName13ProductName26ProductName39</p>
                                        <span>(<em>SKU1234567890</em>)</span>
                                        <span class="pl-1">100,000.00</span>
                                    </div>
                                    <div class="col-3">
                                        <p>1,000,000.00</p>
                                    </div>
                                </div><hr>
                                <div class="row no-gutters align-items-center">
                                    <div class="col-2">
                                        <div class="pImg">
                                            <img class="rounded-circle" src="https://dozzart.com/758-large_default/peinture-acrylique-art-brut-98x98-cm.jpg" width="50" alt="SKU">
                                            <div class="pQty">100</div>
                                        </div>
                                    </div>
                                    <div class="col-7">
                                        <p class="word-wrap mb-0">ProductName13ProductName26ProductName39</p>
                                        <span>(<em>SKU1234567890</em>)</span>
                                        <span class="pl-1">100,000.00</span>
                                    </div>
                                    <div class="col-3">
                                        <p>1,000,000.00</p>
                                    </div>
                                </div><hr>
                                <div class="row no-gutters align-items-center">
                                    <div class="col-2">
                                        <div class="pImg">
                                            <img class="rounded-circle" src="https://dozzart.com/758-large_default/peinture-acrylique-art-brut-98x98-cm.jpg" width="50" alt="SKU">
                                            <div class="pQty">100</div>
                                        </div>
                                    </div>
                                    <div class="col-7">
                                        <p class="word-wrap mb-0">ProductName13ProductName26ProductName39</p>
                                        <span>(<em>SKU1234567890</em>)</span>
                                        <span class="pl-1">100,000.00</span>
                                    </div>
                                    <div class="col-3">
                                        <p>1,000,000.00</p>
                                    </div>
                                </div><hr>
                                <div class="row no-gutters align-items-center">
                                    <div class="col-2">
                                        <div class="pImg">
                                            <img class="rounded-circle" src="https://dozzart.com/758-large_default/peinture-acrylique-art-brut-98x98-cm.jpg" width="50" alt="SKU">
                                            <div class="pQty">100</div>
                                        </div>
                                    </div>
                                    <div class="col-7">
                                        <p class="word-wrap mb-0">ProductName13ProductName26ProductName39</p>
                                        <span>(<em>SKU1234567890</em>)</span>
                                        <span class="pl-1">100,000.00</span>
                                    </div>
                                    <div class="col-3">
                                        <p>1,000,000.00</p>
                                    </div>
                                </div><hr>
                                <div class="row no-gutters align-items-center">
                                    <div class="col-2">
                                        <div class="pImg">
                                            <img class="rounded-circle" src="https://dozzart.com/758-large_default/peinture-acrylique-art-brut-98x98-cm.jpg" width="50" alt="SKU">
                                            <div class="pQty">100</div>
                                        </div>
                                    </div>
                                    <div class="col-7">
                                        <p class="word-wrap mb-0">ProductName13ProductName26ProductName39</p>
                                        <span>(<em>SKU1234567890</em>)</span>
                                        <span class="pl-1">100,000.00</span>
                                    </div>
                                    <div class="col-3">
                                        <p>1,000,000.00</p>
                                    </div>
                                </div><hr>
                            </li>
                        </ul>
                    </div>
                </div>
                
               
                <div class="row no-gutters mBasketSummary">
                    <div class="col-12">
                      <div class="row pt-2">
                       <div class="col-1"></div>
                        <div class="col-4">
                            <span>Taxes</span>
                        </div>
                        <div class="col-6 text-right">
                            <span>1,000.000</span>
                        </div>
                        <div class="col-1"></div>
                      </div>
                      <hr class="hrSmall">
                      <div class="row">
                       <div class="col-1"></div>
                        <div class="col-4">
                            <span>Subtotal</span>
                        </div>
                        <div class="col-6 text-right">
                            <span>1,000,000.000</span>
                        </div>
                        <div class="col-1"></div>
                      </div>
                      <hr class="hrSmall">
                      <div class="row pb-2">
                       <div class="col-1"></div>
                        <div class="col-4">
                            <span><b>Grand Total</b></span>
                        </div>
                        <div class="col-6 text-right">
                            <span><b>1,000,000.00</b></span>
                        </div>
                        <div class="col-1"></div>
                      </div>
                    </div>
                </div>
                
   
            </div>
            
        </div>
    </div>
</div> 
<!-- End Basket Items Modal -->
   
<!-- Product Category Modal -->                 
<div id="productCategory" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Product Categories</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-4 mb-2 text-center">
                        <div class="card"><div class="card-img"><a href="#">CATEGORY NAME</a></div></div>
                    </div>
                    <div class="col-4 mb-2 text-center">
                        <div class="card"><div class="card-img"><a href="#">CATEGORY NAME</a></div></div>
                    </div>
                    <div class="col-4 mb-2 text-center">
                        <div class="card"><div class="card-img"><a href="#">CATEGORY NAME</a></div></div>
                    </div>
                    <div class="col-4 mb-2 text-center">
                        <div class="card"><div class="card-img"><a href="#">CATEGORY NAME</a></div></div>
                    </div>
                    <div class="col-4 mb-2 text-center">
                        <div class="card"><div class="card-img"><a href="#">CATEGORY NAME</a></div></div>
                    </div>
                    <div class="col-4 mb-2 text-center">
                        <div class="card"><div class="card-img"><a href="#">CATEGORY NAME</a></div></div>
                    </div>
                    <div class="col-4 mb-2 text-center">
                        <div class="card"><div class="card-img"><a href="#">CATEGORY NAME</a></div></div>
                    </div>
                    <div class="col-4 mb-2 text-center">
                        <div class="card"><div class="card-img"><a href="#">CATEGORY NAME</a></div></div>
                    </div>
                    <div class="col-4 mb-2 text-center">
                        <div class="card"><div class="card-img"><a href="#">CATEGORY NAME</a></div></div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Product Category Modal --> 
     
<!-- Search Customers Modal -->                 
<div id="searchCustomers" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Search Customers</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body bg-grayscale txt-grayscale">
                <div class="row mt-2">
                    <div class="form-group col-12">
                        <label for="selectCustomer">Select Customer</label>
                        <select class="form-control" id="selectCustomer">
                          <option>Walk-In Customer</option>
                          <option>Dummy Customer 1</option>
                          <option>Dummy Customer 2</option>
                          <option>Dummy Customer 3</option>
                          <option>Dummy Customer 4</option>
                          <option>Dummy Customer 5</option>
                        </select>
                      </div>    
                </div>
                
                <div class="row customers">
                    <div class="col-12">
                       <button class="btn btn-block">CHANGE CX</button> 
                    </div>
                </div>
           
               
                
            </div>
            <div class="modal-footer d-none d-sm-block">
                <button type="button" class="btn  btn-block btn-qty">ADD PRODUCT</button>
            </div>
        </div>
    </div>
</div> 
<!-- End Search Customers Modal -->

<!-- Cart Product Detail Modal -->                 
<div id="productDetail" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Product Name</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="row mt-3">
                    <div class="col-md-3 col-3">
                        <button type="button" class="btn btn-qty btn-block"><h3>-</h3></button>
                    </div>
                    <div class="col-md-6  col-6">
                        <input id="qty" type="number" class="form-control">
                    </div>
                    <div class="col-md-3  col-3">
                        <button type="button" class="btn btn-qty btn-block"><h3>+</h3></button>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-12 text-center"><h3>LKR 100,000.00</h3></div>
                    <div class="col-md-12 col-12 text-center">
                        <div class="row text-center">
                            <div class="col-md-6 col-6"><h6>SKU: AXAIZDC</h6></div>
                            <div class="col-md-6 col-6"><h6>05 In Stocks</h6></div>
                        </div>
                    </div>
                </div>
                <hr class="d-block d-sm-none">
                <div class="row d-block d-sm-none">
                    <button type="button" class="btn  btn-block btn-qty">UPDATE PRODUCT</button>
                </div>
                <hr>
                <div class="row text-center">
                    <div class="col-md-6">
                        <img class="card-img-top" src="https://via.placeholder.com/452x452" alt="Product Name">
                    </div><br>
                    <div class="col-md-6">
                        <p class="align-middle">
                            There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable. 
                        </p>
                    </div>
                </div>
            </div>
            <div class="modal-footer d-none d-sm-block">
                <button type="button" class="btn  btn-block btn-qty">ADD PRODUCT</button>
            </div>
        </div>
    </div>
</div> 
<!-- End Cart Product Detail Modal -->  
  
<!-- Checkout Modal -->   
<div id="checkOut" class="modal fade"tabindex="-1" role="dialog" aria-labelledby="checkOut" aria-hidden="true">
    <div class="modal-dialog modal-dialog-slideout" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Order Checkout</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span>
                </button>
            </div>

            <div class="modal-body py-0">
                <div id="rootwizard" class="container-fluid">
                  <!-- Nav tabs -->
                  <ul id="checkOutSteps" class="nav nav-tabs nav-tabs-linetriangle nav-tabs-separator row" role="tablist" data-init-reponsive-tabs="dropdownfx">
                    <li class="nav-item text-center col-4">
                      <a class="active" data-toggle="tab" href="#coupon" role="tab">
                      <i class="aapl-gift tab-icon"></i> <br>
                      <span>Coupon</span>
                      </a>
                    </li>
                    <li class="nav-item text-center col-4">
                      <a class="" data-toggle="tab" href="#shipping" role="tab">
                      <i class="aapl-truck tab-icon"></i> <br>
                      <span>Shipping</span>
                      </a>
                    </li>
                    <li class="nav-item text-center col-4">
                      <a class="" data-toggle="tab"href="#payment" role="tab"><i class="aapl-cash-dollar tab-icon"></i> <br>
                      <span>Payment</span></a>
                    </li>

                  </ul>
                    <!-- Tab panes -->
                    <div class="tab-content col-12">

                        <div class="tab-pane active row slide-left sm-no-padding" id="coupon">
                            <div class="col-12 form-group text-center">
                                <label for="payable">ORDER VALUE</label><br>
                                <label class="text-center strikeout" id="totalPayable" for="payable">9,999,999.000</label>  
                                <label class="text-center" id="discountedPayable" for="payable">9,999,999.000</label>  
                            </div>

                            <div class="col-12 pb-3">
                                <p class="text-center">ENTER COUPON</p>

                                <input type="text" class="form-control form-control-lg text-center" name="payable" id="payable">
                                <button type="submit" class="btn btn-block btn-primary mt-2">VALIDATE</button>

                                <div class="collapse" id="couponCode">    
                                    <input type="text" class="form-control form-control-lg text-center" name="payable" id="payable">
                                    <button type="submit" class="btn btn-block btn-primary mt-2">VALIDATE</button>
                                </div> 
                            </div>    
                        </div>

                        <div class="tab-pane row slide-left sm-no-padding" id="shipping">
                            <div class="col-12 mt-3">
                                <form id="shipping" action="#">
                                    <p>
                                        <input type="radio" id="strPickup" name="radio-shipping" checked>
                                        <label for="strPickup">Store Pick-Up</label>
                                    </p>
                                    <div class="ship-value text-right">0.00</div>
                                    <hr>

                                    <p class="text-center"><a class="" data-toggle="collapse" href="#shippingMethods">MORE OPTIONS..</a></p>

                                    <div class="collapse" id="shippingMethods">
                                        <p>
                                            <input type="radio" id="merStd" name="radio-shipping">
                                            <label for="merStd">Standard Delivery</label>
                                        </p>
                                        <div class="ship-value text-right">100.00</div>
                                        <hr>
                                        <p>
                                            <input type="radio" id="merExp" name="radio-shipping">
                                            <label for="merExp">Express Delivery</label>
                                        </p>
                                        <div class="ship-value text-right">1,000.00</div>
                                        <hr>
                                        <p>
                                            <input type="radio" id="aramexStd" name="radio-shipping">
                                            <label for="aramexStd">Aramex Standard</label>
                                        </p>
                                        <div class="ship-value text-right">1,000.00</div>
                                        <hr>
                                        <p>
                                            <input type="radio" id="aramexExp" name="radio-shipping">
                                            <label for="aramexExp">Aramex Express</label>
                                        </p>
                                        <div class="ship-value text-right">1,000.00</div>
                                        <hr>
                                        <p>
                                            <input type="radio" id="fedex" name="radio-shipping">
                                            <label for="fedex">FedEx Delivery</label>
                                        </p>
                                        <div class="ship-value text-right">1,000.00</div>
                                        <hr>
                                        <p>
                                            <input type="radio" id="dhl" name="radio-shipping">
                                            <label for="dhl">DHL Delivery</label>
                                        </p>
                                        <div class="ship-value text-right">1,000.00</div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="tab-pane row slide-left sm-no-padding" id="payment">
                            <div class="col-12 mt-3">
                                <div class="card card-transparent flex-row">
                                  <ul class="nav nav-tabs nav-tabs-simple nav-tabs-left nav-tabs-custom  bg-white">
                                    <li class="nav-item text-center">
                                      <a href="#" class="active" data-toggle="tab" data-target="#tabCash">
                                          <i class="aapl-cash-dollar"></i>CASH
                                      </a>
                                    </li>
                                    <li class="nav-item text-center">
                                      <a href="#" data-toggle="tab" data-target="#tabCredit">
                                          <i class="aapl-credit-card"></i>CREDIT
                                      </a>
                                    </li>
                                    <li class="nav-item text-center">
                                      <a href="#" data-toggle="tab" data-target="#tabOnline">
                                          <i class="aapl-network"></i>ONLINE
                                      </a>
                                    </li>
                                  </ul>
                                  <div class="tab-content container-fluid  bg-white">
                                    <div class="tab-pane active  text-center" id="tabCash">
                                         <form class="col-12"> 
                                              <div class="form-group">
                                                <label for="payable">PAYABLE AMOUNT</label>
                                                <input type="text" class="form-control autonumeric form-control-lg text-right" name="tender" id="payable" data-a-dec="." data-a-sep="," readonly>
                                              </div>
                                              <div class="form-group">
                                                <label for="tender">TENDERED AMOUNT</label>
                                                <input type="text" class="form-control autonumeric form-control-lg text-right" data-a-dec="." data-a-sep=","  id="tender">
                                              </div>
                                              <div class="form-group">
                                                <label for="balance">BALANCE AMOUNT</label>
                                                <input type="text" class="form-control autonumeric form-control-lg text-right" data-a-dec="." data-a-sep="," id="balance">
                                              </div>
                                            </form>
                                    </div>
                                    <div class="tab-pane" id="tabCredit">
                                        <form class="col-12">
                                            <div class="row">
                                                <div class="form-group col-12">
                                                    <label for="cardType">CARD TYPE</label>
                                                    <select class="form-control form-control-lg text-center" id="cardType">
                                                      <option>VISA</option>
                                                      <option>MASTERCARD</option>
                                                      <option>AMEX</option>
                                                    </select>
                                                  </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-12">
                                                    <label for="payable">LAST 4 DIGIT</label>
                                                    <input type="text" class="form-control autonumeric form-control-lg text-center" name="last4Digit" id="last4Digit">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-6">
                                                    <label for="payable">MONTH</label>
                                                    <input type="text" class="form-control autonumeric form-control-lg text-center" name="month" id="month">
                                                </div>
                                                <div class="form-group col-6">
                                                    <label for="payable">YEAR</label>
                                                    <input type="text" class="form-control autonumeric form-control-lg text-center" name="year" id="year">
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="tab-pane" id="tabOnline">
                                        <form class="col-12 text-center">
                                            <h1>Coming Soon</h1>
                                        </form>  
                                    </div>
                                  </div>
                                </div>
                            </div>    
                        </div>


                        <div class="col-12 wizard-footer no-padding">
                            <ul class="row no-style no-padding pager wizard">
                                <li class="col-6 no-padding previous">
                                    <button class="btn btn-block btnPrev" type="button">
                                        <i class="aapl-arrow-left-circle"></i>
                                        <span>BACK</span>
                                    </button>
                                </li>
                                <li class="col-6 no-padding next">
                                    <button class="btn btn-default btn-block btnNext" type="button">
                                        <i class="aapl-arrow-right-circle"></i>
                                        <span>NEXT</span>
                                    </button>
                                </li>
                                <li class="col-6 no-padding next finish hidden">
                                    <button class="btn btn-default btn-block btnNext" type="button">
                                        <i class="aapl-receipt"></i>
                                        <span>PAY NOW</span>
                                    </button>
                                </li>
                                <li class="col-6 no-padding first hidden">
                                    <button class="btn btn-default btn-block btnPrev" type="button">
                                        <span>First</span>
                                    </button>
                                </li>
                            </ul>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div> 
<!-- End Checkout Modal -->  
  




   


@endsection

@section('page_scripts')

<script>
	
	(function($) {

    'use strict';

    $(document).ready(function() {

        $('#rootwizard').bootstrapWizard({
            onTabShow: function(tab, navigation, index) {
                var $total = navigation.find('li').length;
                var $current = index + 1;

                // If it's the last tab then hide the last button and show the finish instead
                if ($current >= $total) {
                    $('#rootwizard').find('.pager .next').hide();
                    $('#rootwizard').find('.pager .finish').show().removeClass('disabled hidden');
                } else {
                    $('#rootwizard').find('.pager .next').show();
                    $('#rootwizard').find('.pager .finish').hide();
                }

                var li = navigation.find('li a.active').parent();

                var btnNext = $('#rootwizard').find('.pager .next').find('button');
                var btnPrev = $('#rootwizard').find('.pager .previous').find('button');

                // remove fontAwesome icon classes
                function removeIcons(btn) {
                    btn.removeClass(function(index, css) {
                        return (css.match(/(^|\s)fa-\S+/g) || []).join(' ');
                    });
                }

                if ($current > 1 && $current < $total) {

                    var nextIcon = li.next().find('.fa');
                    var nextIconClass = nextIcon.attr('class').match(/fa-[\w-]*/).join();

                    removeIcons(btnNext);
                    btnNext.addClass(nextIconClass + ' btn-animated from-left fa');

                    var prevIcon = li.prev().find('.fa');
                    var prevIconClass = prevIcon.attr('class').match(/fa-[\w-]*/).join();

                    removeIcons(btnPrev);
                    btnPrev.addClass(prevIconClass + ' btn-animated from-left fa');
                } else if ($current == 1) {
                    // remove classes needed for button animations from previous button
                    btnPrev.removeClass('btn-animated from-left fa');
                    removeIcons(btnPrev);
                } else {
                    // remove classes needed for button animations from next button
                    btnNext.removeClass('btn-animated from-left fa');
                    removeIcons(btnNext);
                }
            },
            onNext: function(tab, navigation, index) {
                //console.log("Showing next tab");
            },
            onPrevious: function(tab, navigation, index) {
                //console.log("Showing previous tab");
            },
            onInit: function() {
                $('#rootwizard ul').removeClass('nav-pills');
            }

        });

        $('.remove-item').click(function() {
            $(this).parents('tr').fadeOut(function() {
                $(this).remove();
            });
        });

    });

})(window.jQuery);

</script>

@endsection