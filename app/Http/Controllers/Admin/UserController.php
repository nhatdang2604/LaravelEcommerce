<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index() {

        $users = User::paginate(10);
        return view('admin.user.index', compact('users'));
    }

    public function create() {
        return view('admin.user.create');
    }

    private function buildUserArrayFromRequest(Request $request) {
        return [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_as' => $request->role_as,
        ];
    }

    public function store(Request $request) {

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'role_as' => ['required', 'integer'],
        ]);

        User::create($this->buildUserArrayFromRequest($request));
        return redirect('/admin/users')->with('message', 'User Created Successfully');
    }

    public function edit(int $userId) {
        $user = User::findOrFail($userId);
        return view('admin.user.edit', compact('user'));
    }

    public function update(Request $request, int $userId) {

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8'],
            'role_as' => ['required', 'integer'],
        ]);

        User::findOrFail($userId)->update($this->buildUserArrayFromRequest($request));
        return redirect('/admin/users')->with('message', 'User Updated Successfully');
    }

    public function delete(int $userId) {
        User::findOrFail($userId)->delete();
        return redirect('/admin/users')->with('message', 'User Deleted Successfully');
    }
}
