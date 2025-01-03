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
            'teacher_id' => 'required|exists:users,id', // Ensure teacher exists
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
            'password' => 'required|string|min:6|confirmed', // Ensure password confirmation
            'role' => 'required|in:teacher,student', // Ensure valid role
        ]);

        // Create the user with the role assigned
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role, // Assign role
        ]);

        // You can also associate the user with courses or other data as needed

        return redirect()->route('admin.manageUsers')->with('success', 'User created successfully.');
    }

    // Edit user details
    public function editUser($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    // Update user details
    public function updateUser(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id, // Unique except for current user
            'role' => 'required|in:teacher,student,admin', // Allow updating role
        ]);

        $user = User::findOrFail($id);
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role, // Update role
        ]);

        return redirect()->route('admin.manageUsers')->with('success', 'User updated successfully.');
    }

    // Delete a user
    public function deleteUser($id)
    {
        $user = User::findOrFail($id);

        // Prevent deletion of admin users
        if ($user->role === 'admin') {
            return back()->withErrors(['error' => 'Cannot delete an admin user.']);
        }

        $user->delete();

        return redirect()->route('admin.manageUsers')->with('success', 'User deleted successfully.');
    }
}
