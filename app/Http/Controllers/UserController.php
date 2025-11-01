<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule; // <-- Pastikan ini ada

class UserController extends Controller
{
    public function index()
    {
        $data['dataUser'] = User::all();
        return view('admin.user.index', $data);
    }

    public function create()
    {
        return view('admin.user.create');
    }

    public function store(Request $request)
    {
        // Validasi
        $validatedData = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        $data['name'] = $validatedData['name'];
        $data['email'] = $validatedData['email'];
        $data['password'] = Hash::make($validatedData['password']);

        User::create($data);
        return redirect()->route('user.index')->with('success', 'Data User berhasil ditambahkan!');
    }

    public function edit(string $id)
    {
        $data['user'] = User::findOrFail($id);
        return view('admin.user.edit', $data);
    }

    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        // Aturan validasi
        $rules = [
            'name' => 'required|string|max:100',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($user->id),
            ],
        ];

        if ($request->filled('password')) {
            $rules['password'] = 'required|min:8|confirmed';
        }

        $validatedData = $request->validate($rules);

        // Siapkan data
        $data['name'] = $validatedData['name'];
        $data['email'] = $validatedData['email'];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($validatedData['password']);
        }

        $user->update($data);
        return redirect()->route('user.index')->with('success', 'Data User berhasil diperbarui!');
    }

    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('user.index')->with('success', 'Data User berhasil dihapus!');
    }
}
