@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Terms & Conditions</div>
                <div class="card-body">
                    <p style="text-align: justify;">
                        This website is operated by <span style="color:#ff0000;">[STOREOWNER]</span>. Throughout the site, the terms “we”, “us” and “our” refer to <span style="color:#ff0000;">[STOREOWNER]</span>. <span style="color:#ff0000;">[STOREOWNER]</span> offers this website, including all information, tools and services available from this site to you, the user, conditioned upon your acceptance of all terms, conditions, policies and notices stated here.</p>
                    <p style="text-align: justify;">
                        By visiting our site and/ or purchasing something from us, you engage in our “Service” and agree to be bound by the following terms and conditions (“Terms of Service”, “Terms”), including those additional terms and conditions and policies referenced herein and/or available by hyperlink. These Terms of Service apply&nbsp; to all users of the site, including without limitation users who are browsers, vendors, customers, merchants, and/ or contributors of content.</p>
                    <p style="text-align: justify;">
                        Please read these Terms of Service carefully before accessing or using our website. By accessing or using any part of the site, you agree to be bound by these Terms of Service. If you do not agree to all the terms and conditions of this agreement, then you may not access the website or use any services. If these Terms of Service are considered an offer, acceptance is expressly limited to these Terms of Service.</p>
                    <p style="text-align: justify;">
                        Any new features or tools which are added to the current store shall also be subject to the Terms of Service. You can review the most current version of the Terms of Service at any time on this page. We reserve the right to update, change or replace any part of these Terms of Service by posting updates and/or changes to our website. It is your responsibility to check this page periodically for changes. Your continued use of or access to the website following the posting of any changes constitutes acceptance of those changes.</p>
                    <p style="text-align: justify;">
                        Our store is hosted with ShopBox Pvt. Ltd. They provide us with the online e-commerce platform that allows us to sell our products and services to you.</p>
                    <p style="text-align: justify;">
                        &nbsp;</p>
                    <p style="text-align: justify;">
                        SECTION 1 - ONLINE STORE TERMS</p>
                    <p style="text-align: justify;">
                        By agreeing to these Terms of Service, you represent that you are at least the age of majority in your state or province of residence, or that you are the age of majority in your state or province of residence and you have given us your consent to allow any of your minor dependents to use this site.</p>
                    <p style="text-align: justify;">
                        You may not use our products for any illegal or unauthorized purpose nor may you, in the use of the Service, violate any laws in your jurisdiction (including but not limited to copyright laws).</p>
                    <p style="text-align: justify;">
                        You must not transmit any worms or viruses or any code of a destructive nature.</p>
                    <p style="text-align: justify;">
                        A breach or violation of any of the Terms will result in an immediate termination of your Services.</p>
                    <p style="text-align: justify;">
                        &nbsp;</p>
                    <p style="text-align: justify;">
                        SECTION 2 - GENERAL CONDITIONS</p>
                    <p style="text-align: justify;">
                        We reserve the right to refuse service to anyone for any reason at any time.</p>
                    <p style="text-align: justify;">
                        You understand that your content (not including credit card information), may be transferred unencrypted and involve (a) transmissions over various networks; and (b) changes to conform and adapt to technical requirements of connecting networks or devices. Credit card information is always encrypted during transfer over networks.</p>
                    <p style="text-align: justify;">
                        You agree not to reproduce, duplicate, copy, sell, resell or exploit any portion of the Service, use of the Service, or access to the Service or any contact on the website through which the service is provided, without express written permission by us.</p>
                    <p style="text-align: justify;">
                        The headings used in this agreement are included for convenience only and will not limit or otherwise affect these Terms.</p>
                    <p style="text-align: justify;">
                        &nbsp;</p>
                    <p style="text-align: justify;">
                        SECTION 3 - ACCURACY, COMPLETENESS AND TIMELINESS OF INFORMATION</p>
                    <p style="text-align: justify;">
                        We are not responsible if information made available on this site is not accurate, complete or current. The material on this site is provided for general information only and should not be relied upon or used as the sole basis for making decisions without consulting primary, more accurate, more complete or more timely sources of information. Any reliance on the material on this site is at your own risk.</p>
                    <p style="text-align: justify;">
                        This site may contain certain historical information. Historical information, necessarily, is not current and is provided for your reference only. We reserve the right to modify the contents of this site at any time, but we have no obligation to update any information on our site. You agree that it is your responsibility to monitor changes to our site.</p>
                                <hr>

                    <form method="POST" action="{{ route('verification.verify', $token) }}">
                        {{ csrf_field() }}
                        {{ method_field('PATCH') }}
                        <input type="hidden" name="newsletter" value="0">
                        <div class="form-group">
                            <div class="form-check">
                                <input class="form-check-input{{ $errors->has('newsletter') ? ' is-invalid' : '' }}" type="checkbox" value="1">
                                <label class="form-check-label">
                                  Subscribe to newsletter
                                </label>
                                @if ($errors->has('newsletter'))
                                    <div class="invalid-feedback">
                                        <strong>{{ $errors->first('newsletter') }}</strong>
                                    </div>
                                @endif
                            </div>                            
                        </div>

                        <div class="form-group">
                            <div class="form-check">
                                <input class="form-check-input{{ $errors->has('agree') ? ' is-invalid' : '' }}" type="checkbox" name="agree" {{ old('agree') ? 'checked' : '' }} required>
                                <label class="form-check-label">
                                  I agree
                                </label>
                                @if ($errors->has('agree'))
                                    <div class="invalid-feedback">
                                        <strong>{{ $errors->first('agree') }}</strong>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-dark">
                                Login
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection