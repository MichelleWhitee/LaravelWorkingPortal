@extends('layouts.app')

@section('content')
    <div class="container">
        <p class="fs-3" style="text-align: center; font-weight: bolder; margin: 20px; color: #303645">Сотрудники</p>

        @if($employees->isEmpty())
            <p>Нет сотрудников для отображения.</p>
        @else
            <table class="table table-hover">
                <thead class="table-light shadow">
                    <tr>
                        <th>ID</th>
                        <th>Имя</th>
                        <th>Email</th>
                        <th>Дата регистрации</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($employees as $employee)
                        <tr class="empl-row">
                            <td>{{ $employee->id }}</td>
                            <td onclick="location.href='{{ route('employees.edit', $employee) }}'" style="cursor: pointer;">{{ $employee->name }}</td>
                            <td>{{ $employee->email }}</td>
                            <td>{{ $employee->created_at->format('d.m.Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
