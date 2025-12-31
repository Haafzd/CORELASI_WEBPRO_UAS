<!doctype html>
<html lang="id" data-theme="corelasi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>@yield('title','CORELASI')</title>


  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">


  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">


  <script src="https://cdn.tailwindcss.com"></script>
  <script>

    tailwind.config = {
      theme: {
        extend: {
          colors: {
            core: {
              primary: '#707FDD',
              bg: '#F6F7FB',
              ink: '#1F2937'
            }
          },
          boxShadow: {
            soft: '0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1)'
          }
        }
      }
    }
  </script>


  <link rel="stylesheet" href="{{ asset('assets/css/corelasi-ui.css') }}">


  @if (file_exists(public_path('build/manifest.json')))
    @vite(['resources/css/app.css','resources/js/app.js'])
  @endif
</head>
<body class="bg-core-bg text-core-ink">
  <div class="min-h-screen d-flex">
    @include('partials.sidebar')

    <div class="flex-grow-1 d-flex flex-column min-w-0 lg:ml-[280px] transition-all duration-300">
      @include('partials.header')

      <main class="px-4 px-md-5 py-4">
        @yield('content')
      </main>

      @include('partials.footer')
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

  @stack('scripts')
</body>
</html>
