<div class="tabs_product">
    <div class="container">
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <a id="tab-A" href="#pane-A" class="nav-link active" data-toggle="tab" role="tab">Description</a>
            </li>
        </ul>
    </div>
</div>

<div class="tab_content_wrapper">
	<div class="container">
	    <div class="tab-content" role="tablist">
	        <div id="pane-A" class="card tab-pane fade active show" role="tabpanel" aria-labelledby="tab-A">
	            <div class="card-header" role="tab" id="heading-A">
	                <h5 class="mb-0">
	                    <a class="collapsed" data-toggle="collapse" href="#collapse-A" aria-expanded="false" aria-controls="collapse-A">
	                        Description
	                    </a>
	                </h5>
	            </div>
	            <div id="collapse-A" class="collapse" role="tabpanel" aria-labelledby="heading-A">
	                <div class="card-body">
	                    <div class="row justify-content-between">
	                        <div class="col-lg-12">{!! $product->description !!}</div>
	                    </div>
	                </div>
	            </div>
	        </div>
	    </div>
	</div>
</div>