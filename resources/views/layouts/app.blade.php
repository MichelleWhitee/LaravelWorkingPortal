<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS for Sidebar Animation -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    @vite('resources/css/app.css')
</head>
<body>
    <div class="d-flex" id="wrapper">
        @auth
            <!-- Sidebar -->
            <div id="sidebar-wrapper" class="shadow">
                <div class="sidebar-heading fs-4 mt-3 mb-3" style="text-align: center; color: white;">Рабочий портал</div>
                <div class="list-group list-group-flush" style="margin-top: 29px">
                    <a href="{{ route('tasks.index') }}" class="list-group-item list-group-item-action">Задачи</a>
                    <a href="{{ route('discussions.index') }}" class="list-group-item list-group-item-action">Обсуждения</a>
                    <a href="{{ route('employees.index') }}" class="list-group-item list-group-item-action">Сотрудники</a>
                    <a href="{{ route('notes.index') }}" class="list-group-item list-group-item-action">Заметки</a>
                </div>
            </div>
            <!-- /#sidebar-wrapper -->
        @endauth

        <!-- Page Content -->
        <div id="page-content-wrapper">
        @auth
        <nav class="navbar navbar-expand-lg shadow">
                <button class="ml-3 btn btn-primary" style="width: 50px; height: 50px; background-color: transparent; border-color: transparent" id="menu-toggle">
                    <img src="{{ asset('storage/imgs/hamburger.png') }}">
                </button>
            
            <div class="ms-auto d-flex align-items-center">

                    <a href="{{ route('profile.edit') }}" class="me-2">
                        @if(auth()->user()->avatar)
                            <div class="avatar">
                                <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="User Avatar" style="width:65px; height:65px; object-fit: cover;" class="avatar-img rounded-circle">
                            </div>
                        @else
                            <div class="avatar">
                                <img src="{{ asset('storage/avatars/placeholder.png') }}" style="width:65x; height:65px;" class="avatar-img rounded-circle">
                            </div>
                        @endif
                    </a>
                    
                    <a style="color: white" href="{{ route('profile.edit') }}" class="me-2">
                        <span class="mx-3">{{ Auth::user()->name }}</span>
                    </a>
                    
                    <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger" style="font-weight: bolder; margin-right: 30px; margin-left: 30px">Выход</button>
                    </form>
                
                <!--@guest
                    <a href="{{ route('login') }}" class="btn btn-outline-primary">Вход</a>
                @endguest-->
            </div>
        </nav>
        @endauth


            @yield('content')
        </div>
        <!-- /#page-content-wrapper -->
    </div>

    <!-- Bootstrap JS and JavaScript for toggle functionality -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Скрипт для работы кнопки "Toggle menu"
        document.getElementById("menu-toggle").addEventListener("click", function () {
            document.getElementById("wrapper").classList.toggle("toggled");
        });
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Обработка изменения фильтра
            $('.filter-btn').on('click', function() {
                var filter = $(this).data('filter'); // Получаем значение фильтра
                fetchTasks(filter); // Вызываем функцию для получения задач
            });

            function fetchTasks(filter) {
                $.ajax({
                    url: '{{ route('tasks.index') }}', // URL для получения задач
                    method: 'GET',
                    data: { filter: filter }, // Передаем выбранный фильтр
                    headers: {
                        'ngrok-skip-browser-warning': 'true' // Добавляем заголовок
                    },
                    success: function(response) {
                        $('#task-list').html(response); // Обновляем список задач
                    },
                    error: function(xhr) {
                        console.error(xhr); // Логируем ошибку
                    }
                });
            }
        });

</body>
</html>

