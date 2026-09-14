<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title')</title>
  
  <!-- Pastikan kamu menaruh file style.css aslimu di dalam folder 'public' -->
  <link rel="stylesheet" href="{{ asset('style.css') }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css">
</head>
<body>
  <section class="page_404">
    <div class="container">
      <div class="row">
        <div class="col-sm-12 ">
          <div class="col-sm-10 col-sm-offset-1 text-center">
            
            <div class="four_zero_four_bg">
              <h1 class="text-center ">@yield('code')</h1>
            </div>

            <div class="contant_box_404">
              <h3 class="h2">
                @yield('heading')
              </h3>
              <p>@yield('message')</p>
              <a href="{{ url('/') }}" class="link_404">Go to Home</a>
            </div>

          </div>
        </div>
      </div>
    </div>
  </section>
</body>
</html>