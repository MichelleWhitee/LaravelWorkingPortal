@extends('layouts.app')

@section('content')
    <div style="margin-top: 30px;" class="container">
        <!-- Фильтры задач -->
        <div class="mb-3">
            <ul class="nav nav-pills">
                <li class="nav-item shadow">
                    <a class="nav-link {{ $filter == 'active' ? 'active' : '' }}" href="#" data-filter="active" id="filter-active">
                        Активные <span class="shadow badge bg-danger">{{ $counts['active'] }}</span>
                    </a>
                </li>
                <li class="nav-item shadow">
                    <a class="nav-link {{ $filter == 'today' ? 'active' : '' }}" href="#" data-filter="today" id="filter-today">
                        На сегодня <span class="shadow badge bg-secondary">{{ $counts['today'] }}</span>
                    </a>
                </li>
                <li class="nav-item shadow">
                    <a class="nav-link {{ $filter == 'tomorrow' ? 'active' : '' }}" href="#" data-filter="tomorrow" id="filter-toomorrow">
                        На завтра <span class="shadow badge bg-secondary">{{ $counts['tomorrow'] }}</span>
                    </a>
                </li>
                <li class="nav-item shadow">
                    <a class="nav-link {{ $filter == 'completed' ? 'active' : '' }}" href="#" data-filter="completed" id="filter-completed">
                        Выполненные <span class="shadow badge bg-secondary">{{ $counts['completed'] }}</span>
                    </a>
                </li>
                <li class="nav-item shadow">
                    <a class="nav-link {{ $filter == 'all' ? 'active' : '' }}" href="#" data-filter="all" id="filter-all">
                        Все <span class="shadow badge bg-secondary">{{ $counts['all'] }}</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- Кнопка для добавления задачи -->
        @if(auth()->user()->adm == 1)        
            <a href="{{ route('tasks.create') }}" class="btn add-task mb-3 shadow">Добавить задачу</a>
        @endif

        <!-- Таблица с задачами -->
        <table class="table table-hover" id="task-table">
            <thead class="table-light shadow">
                <tr>
                    <th>#</th>
                    <th>Заголовок</th>
                    <th>Исполнитель</th>
                    <th>Статус</th>
                    <th>Срок</th>
                    <th>Обсуждение</th>

                    @if(auth()->user()->adm == 1)
                        <th>Действия</th>
                    @endif
                </tr>
            </thead>
            <tbody id="task-list">
                @include('tasks.task_list', ['tasks' => $tasks]) <!-- Включаем файл со списком задач -->
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>

        $(document).ready(function() {
            // При загрузке страницы выполняем запрос с фильтром "active" по умолчанию
            let initialFilter = localStorage.getItem('currentFilter') || 'active';
            fetchTasks(initialFilter);

            // Обработка клика по фильтрам
            $('.nav-pills .nav-link').on('click', function(e) {
                e.preventDefault(); // Предотвращаем переход по ссылке
                var filter = $(this).data('filter'); // Получаем значение фильтра
                localStorage.setItem('currentFilter', filter);  // Сохраняем текущий фильтр
                fetchTasks(filter); // Вызываем функцию для получения задач
            });
        
            function fetchTasks(filter) {
            $.ajax({
                url: '{{ route('tasks.index') }}',
                method: 'GET',
                data: { filter: filter },
                success: function(response) {
                    $('#task-list').html(response);
                    $('.nav-link').removeClass('active');
                    $('.nav-link[data-filter="' + filter + '"]').addClass('active');
                },
                error: function(xhr) {
                    console.error(xhr);
                    alert('Ошибка при загрузке задач.');
                }
            });
            }

            // Делегируем событие клика для кнопки "Удалить"
            $('#task-list').on('click', '.delete-task', function(e) {
                e.preventDefault(); // Предотвращаем стандартное поведение кнопки
                var taskId = $(this).data('id'); // Получаем ID задачи

                if (confirm('Вы уверены, что хотите удалить эту задачу?')) {
                    $.ajax({
                        url: '/tasks/' + taskId, // URL для удаления задачи
                        method: 'DELETE', // Метод удаления
                        data: {
                            _token: '{{ csrf_token() }}' // Добавляем токен CSRF для защиты
                        },
                        success: function(response) {
                            updateCounters(response.counts);
                            // Удаляем строку задачи из таблицы
                            $('button.delete-task[data-id="' + taskId + '"]').closest('tr').remove();
                        },
                        error: function(xhr) {
                            console.error(xhr); // Логируем ошибку
                            alert('Недостаточно прав для удаления задачи.');
                        }
                    });
                }
            });
            


            $('#task-list').on('change', '.task-status', function () {
                let taskId = $(this).data('id');
                let newStatus = $(this).val();

                $.ajax({
                    url: '/tasks/' + taskId + '/update-status',
                    type: 'PUT',
                    data: {
                        status: newStatus,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        updateCounters(response.counts);
                        let tr = $('button.delete-task[data-id="' + taskId + '"]').closest('tr');
                        tr.fadeOut(function() {
                            fetchTasks(localStorage.getItem('currentFilter') || 'active');
                        });
                    },
                    error: function (xhr) {
                        console.error(xhr);
                        alert('Ошибка при обновлении статуса задачи.');
                    }
                });
            });

            function updateCounters(counts) {
                $('#filter-active .badge').text(counts.active);
                $('#filter-today .badge').text(counts.today);
                $('#filter-tomorrow .badge').text(counts.tomorrow);
                $('#filter-completed .badge').text(counts.completed);
                $('#filter-all .badge').text(counts.all);
            }
        });
    </script>
@endsection
