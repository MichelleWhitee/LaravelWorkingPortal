<?php

namespace App\Http\Controllers;

use App\Models\User; // Импортируйте модель User
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index()
    {
        // Получаем всех сотрудников (или пользователей, если нужно)
        $employees = User::all(); // Можно добавить пагинацию, если сотрудников много

        return view('employees.index', compact('employees'));
    }

    public function edit(User $employee)
    {
        return view('employees.edit', compact('employee'));
    }

    public function update(Request $request, User $employee)
    {
        // Валидация данных
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $employee->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        // Обновление данных пользователя
        $employee->name = $request->name;
        $employee->email = $request->email;

        $employee->save();

        return redirect()->route('employees.index')->with('success', 'Данные пользователя обновлены успешно.');
    }

}
