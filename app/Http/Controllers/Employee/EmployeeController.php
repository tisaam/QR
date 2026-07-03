<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\EmployeePerformance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    public function index()
    {
        // Assuming employees are users with role 'employee' linked to the business
        $employees = User::where('role', 'employee')->where('business_id', Auth::user()->business->id)->paginate(10);
        return view('employees.index', compact('employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string',
        ]);

        $employee = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make('password123'), // Default password, they should change on first login
            'role' => 'employee',
            'status' => 'active',
        ]);

        // Link employee to business (Assuming you added a business_id column to users table, 
        // or handle this via a pivot table employee_business)
        // Auth::user()->business->employees()->attach($employee->id);

        return back()->with('success', 'Employee added.');
    }

    public function performance(User $employee)
    {
        $performances = EmployeePerformance::where('employee_id', $employee->id)
            ->orderBy('date', 'desc')
            ->paginate(15);

        return view('employees.performance', compact('employee', 'performances'));
    }
}