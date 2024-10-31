<div class="col-xl-6 col-lg-6 col-mb-6 col-sm-12 col-xs-12">
    @if(session()->has('success'))
      <div class="alert alert-success mt-4">
        <i class="fa fa-check-circle-o"></i> {{ session('success') }}
      </div>
    @endif
     <div class="b-title b-title_line_right">
       <h2 class="text-uppercase">get in touch with us</h2>
     </div>
     <form action="{{ route('stores.contact', $page) }}" method="post" autocomplete="off">
        {{ csrf_field() }}
     		<div class="clearfix row">
     			<div class="col-xl-6 col-lg-6 col-mb-6 col-sm-12 col-xs-12">
            <div class="form-group">
              <label>Your Name <i style="color: red;">*</i> 
                @if($errors->has('name'))
		             <span id="name-info" class="info">({{ $errors->first('name') }})</span>
                @endif
              </label>
              <input type="text" id="name" name="name">
            </div>
              </div>
              <div class="col-xl-6 col-lg-6 col-mb-6 col-sm-12 col-xs-12">
                <div class="form-group">
                  <label>Your Email <i style="color: red;">*</i>
                    @if($errors->has('email'))
				             <span id="email-info" class="info">({{ $errors->first('email') }})</span>
                    @endif
                  </label>
                  <input type="email" id="email" name="email">
                </div>
              </div>
              <div class="col-xl-12 col-lg-12 col-mb-12 col-sm-12 col-xs-12">
                  <div class="form-group">
                    <label>Subject</label>
                    <input type="text" id="subject" name="subject">
                  </div>
              </div>  
              <div class="col-xl-12 col-lg-12 col-mb-12 col-sm-12 col-xs-12">
                  <div class="form-group">
                    <label>Your Message</label>
                    <textarea name="content" id="content" name="content"></textarea>
                  </div>
              </div>  
              <div class="col-xl-12 col-lg-12 col-mb-12 col-sm-12 col-xs-12">
                  <button type="submit" class="btn btn-bg text-white">SEND A MESSAGE</button>
              </div>  
     		</div>
     	</form> 
  </div>