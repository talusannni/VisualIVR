<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ $title ?? 'VisualIVR' }}</title>
        <style>
            body {
                font-family: 'Nunito', sans-serif;
            }
            .pageName:hover{
                background-color: #eaeaea;
                cursor:pointer;
            }
        </style>
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap4.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
        <script src="//cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap.min.js"></script>
        <script src='{{ asset('js/app.js') }}'></script>
        <script src='{{ asset('js/editor.plugin.js') }}'></script>
        <script>
            // global app configuration object
            var config = {
                routes: {
                    createSheet: "{{ route('Sheet.store') }}"
                }
            };
        </script>
    </head>
    <body class="antialiased">
        <!--Main Navigation-->
        <header>
            <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
                <div class="container-fluid">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item active">
                            <a class="navbar-brand" href="#">
                                <img src="{{ asset('logo.png') }}" alt="Logo">
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/project/list') }}">Projects</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Reports</a>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <!--Main Navigation-->

        <div class="container-fluid my-4">
            <div class="mb-5">
                <div class="col" id="root">
                    {{ $slot }}
                </div>
            </div>
        </div>
        <footer class="bg-primary text-white text-center text-lg-start">
            <div class="container">
                <div class="row text-center">
                    <div class="col-md-4 box">
                        <span class="copyright quick-links">Copyright &copy; Shashi kiran <script>document.write(new Date().getFullYear())</script>
                        </span>
                    </div>
                </div>
            </div>
        </footer>
    </body>
</html>
