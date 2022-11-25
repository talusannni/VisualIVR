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
            .active{
                background-color: #eaeaea;
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
        <header>
            <!-- Navbar -->
            <nav class="navbar navbar-expand-lg navbar-light" style="background-color: #eaeaea">
                <div class="container-fluid">
                    <div class="collapse navbar-collapse" id="navbarExample01">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
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
                </div>
            </nav>
            <!-- Navbar -->
        </header>
        <div class="relative items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center sm:pt-0">
            <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
                <div class="mt-8 bg-white dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg" id="root">
                    {{ $slot }}
                </div>
            </div>
        </div>
        <footer>
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
