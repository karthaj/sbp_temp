<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>ShopBOX</title>
        <link rel="apple-touch-icon" type="image/x-icon" href="{{ asset('assets/img/favicon.ico') }}" />        
        <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon.ico') }}" />  
        <!-- Bootstrap core CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">


        <!-- Custom styles for this template -->
        <style type="text/css">
            body {
                padding-top: 5rem;
            }

            .starter-template {
                margin-top: auto;
                margin-bottom: auto;
                padding: 3rem 1.5rem;
                text-align: center;
            }
        </style>
		
		<script type="text/javascript"> //<![CDATA[ 
		var tlJsHost = ((window.location.protocol == "https:") ? "https://secure.trust-provider.com/" : "http://www.trustlogo.com/");
		document.write(unescape("%3Cscript src='" + tlJsHost + "trustlogo/javascript/trustlogo.js' type='text/javascript'%3E%3C/script%3E"));
		//]]>
		</script>
    </head>
    <body>
        <main id="app" role="main" class="container">
            <div class="starter-template">
                <img src="{{ asset('assets/img/shopbox.svg') }}" width="300"> 
                <hr><h6>Development Environment</h6><hr>
                <a href="{{ route('admin.login') }}" class="btn btn-info">Login</a>
                <a href="{{ route('admin.register') }}" class="btn btn-info">Get Started</a>
            </div>
        </main>
    <!-- /.container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
    <script src="{{ asset('js/app.js') }}"></script>

    <script>
        axios.get('http://ipg.shopbox.lk/pay', {
            headers: {'Authorization': 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6ImNmOWRjZDAwMDE0Y2NlOGZmMTUyM2M3OTg5ZjYzMmNmOGMxY2Y3ZjIyYzM3MjE1YmQ0MjIzMTEyZGRjNzQ4NzUxNTFjMzM2OGQ5YzNjMmNiIn0.eyJhdWQiOiIxMyIsImp0aSI6ImNmOWRjZDAwMDE0Y2NlOGZmMTUyM2M3OTg5ZjYzMmNmOGMxY2Y3ZjIyYzM3MjE1YmQ0MjIzMTEyZGRjNzQ4NzUxNTFjMzM2OGQ5YzNjMmNiIiwiaWF0IjoxNTI5ODE0MjU3LCJuYmYiOjE1Mjk4MTQyNTcsImV4cCI6MTU2MTM1MDI1Niwic3ViIjoiMTMiLCJzY29wZXMiOlsidmlldy1wYXltZW50cyJdfQ.A_Pbo1VjgSLqmp6eKtN-nITKl0AVmHAGoaq88ryx4CHQb9SLnCI3H0a2ZLzXgrDj8fKoDjgPjIPrqeld4YAPSyvzzlbgJUDhDTgJQ58QARTb8thukwzIl-zLI2Mof_-7n_5iHLkOnUCnKjzSV4FtF6HHNaImVfexNbA7dxqMXFoyHkLKPLJFg5jmWtYJNRyNDRlfZZ9wrcAasbHLAdskr9nfTIzBIb7BBtBZU-k1-xjtg3RMSsgZp1ttHx224yeB5ZcXB3V9AiE6dj44LxUqGuqXWPEkwLcPQMCy2-RZzypsXRP9o3nRmN15XQ0eiGnxByCcqTaIgCkz9-IUtERRG9Lj84cRcj6yaUg8kIN5TMYd6mnSGqmA_dnusvz6HIPVfW-m2BctGKqk9e4cIAPyeY-1FiVIeLN8Jfv_UGckey2IH28oxPpTIecpmdsXVYbDmRdaMyC7LhRFmm9_749Shn0d5YOlv5T23x_tuvDg0Gei7ElNiV69xdEzM4YK5jhNQEB8t5ATj_652t4NtgS8cBRFTk6kbH9Chu6Pp13BNzBSpBIRMDVekwTgS5thP6kUNkaA1ZP2C38_7gfxZfH3yhnTcg7cPsBNvuDQia_PitEJOT4GrQOw6pRZu47JvDjpRLJRM_7Dsx142hXsnVAQf6YLGonowyVS2jELpmhGJbg'},
          })
          .then(function (response) {
            console.log(response);
          })
          .catch(function (error) {
            console.log(error);
          });
    </script>
</html>
