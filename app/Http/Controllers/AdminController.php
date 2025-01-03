<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // Manage Courses - List all courses
    public function manageCourses()
    {
        $courses = Course::with('teacher')->get(); // Include teacher data
        return view('admin.courses.index', compact('courses'));
    }

    // Manage Users - List all users (Teachers, Students)
    public function manageUsers()
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    // Add a new course
    public function createCourse(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'teacher_id' => 'required|exists:users,id',
        ]);

        Course::create($request->all());

        return redirect()->route('admin.manageCourses')->with('success', 'Course created successfully.');
    }

    // Delete a course
    public function deleteCourse($id)
    {
        $course = Course::findOrFail($id);
        $course->delete();

        return redirect()->route('admin.manageCourses')->with('success', 'Course deleted successfully.');
    }

    // Add or manage users (teachers and students)
    public function createUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|in:teacher,student',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        // Additional logic for assigning roles or courses can be added here

        return redirect()->route('admin.manageUsers')->with('success', 'User created successfully.');
    }
}
