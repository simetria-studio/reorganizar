<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{


    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Filtros
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('department', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('department')) {
            $query->where('department', $request->department);
        }

        $users = $query->orderBy('name')->paginate(15);

        // Estatísticas
        $stats = [
            'total' => User::count(),
            'active' => User::where('status', 'active')->count(),
            'admins' => User::where('role', 'admin')->count(),
            'managers' => User::where('role', 'manager')->count(),
            'operators' => User::where('role', 'operator')->count(),
        ];

        $departments = User::whereNotNull('department')
            ->distinct()
            ->pluck('department')
            ->sort();

        ActivityLog::log('view', 'User', 'Visualizou listagem de usuários');

        return view('users.index', compact('users', 'stats', 'departments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:20',
            'department' => 'nullable|string|max:100',
            'role' => 'required|in:admin,manager,operator',
            'status' => 'required|in:active,inactive,suspended',
            'password' => 'required|string|min:6|confirmed',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'permissions' => 'nullable|array',
            'permissions.*' => 'string|in:' . implode(',', array_keys(User::PERMISSIONS)),
        ]);

        DB::beginTransaction();
        try {
            // Upload do avatar
            if ($request->hasFile('avatar')) {
                $avatarPath = $request->file('avatar')->store('avatars', 'public');
                $validated['avatar'] = $avatarPath;
            }

            $validated['password'] = Hash::make($validated['password']);
            $validated['password_changed_at'] = now();

            $user = User::create($validated);

            ActivityLog::log('create', 'User', "Criou usuário: {$user->name}", $user->id, null, $validated);

            DB::commit();

            return redirect()->route('users.index')
                ->with('success', 'Usuário criado com sucesso!');

        } catch (\Exception $e) {
            DB::rollback();

            // Remover avatar se foi uploadado
            if (isset($avatarPath)) {
                Storage::disk('public')->delete($avatarPath);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'Erro ao criar usuário: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user->load('activityLogs');

        // Estatísticas do usuário
        $userStats = [
            'login_count' => $user->activityLogs()->where('type', 'login')->count(),
            'last_activity' => $user->activityLogs()->latest()->first(),
            'created_records' => $user->activityLogs()->where('type', 'create')->count(),
            'updated_records' => $user->activityLogs()->where('type', 'update')->count(),
        ];

        // Atividades recentes
        $recentActivities = $user->activityLogs()
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        ActivityLog::log('view', 'User', "Visualizou perfil do usuário: {$user->name}", $user->id);

        return view('users.show', compact('user', 'userStats', 'recentActivities'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $departments = User::whereNotNull('department')
            ->distinct()
            ->pluck('department')
            ->sort();

        return view('users.edit', compact('user', 'departments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $oldValues = $user->toArray();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => 'nullable|string|max:20',
            'department' => 'nullable|string|max:100',
            'role' => 'required|in:admin,manager,operator',
            'status' => 'required|in:active,inactive,suspended',
            'password' => 'nullable|string|min:6|confirmed',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'permissions' => 'nullable|array',
            'permissions.*' => 'string|in:' . implode(',', array_keys(User::PERMISSIONS)),
        ]);

        DB::beginTransaction();
        try {
            // Upload do novo avatar
            if ($request->hasFile('avatar')) {
                // Remover avatar antigo
                if ($user->avatar) {
                    Storage::disk('public')->delete($user->avatar);
                }

                $avatarPath = $request->file('avatar')->store('avatars', 'public');
                $validated['avatar'] = $avatarPath;
            }

            // Atualizar senha apenas se informada
            if ($request->filled('password')) {
                $validated['password'] = Hash::make($validated['password']);
                $validated['password_changed_at'] = now();
            } else {
                unset($validated['password']);
            }

            $user->update($validated);

            ActivityLog::log('update', 'User', "Atualizou usuário: {$user->name}", $user->id, $oldValues, $validated);

            DB::commit();

            return redirect()->route('users.index')
                ->with('success', 'Usuário atualizado com sucesso!');

        } catch (\Exception $e) {
            DB::rollback();

            // Remover novo avatar se foi uploadado
            if (isset($avatarPath)) {
                Storage::disk('public')->delete($avatarPath);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'Erro ao atualizar usuário: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // Não permitir excluir o próprio usuário
        if ($user->id === Auth::id()) {
            return redirect()->back()
                ->with('error', 'Você não pode excluir sua própria conta.');
        }

        // Não permitir excluir se for o último admin
        if ($user->isAdmin() && User::admins()->count() <= 1) {
            return redirect()->back()
                ->with('error', 'Não é possível excluir o último administrador do sistema.');
        }

        DB::beginTransaction();
        try {
            $userName = $user->name;
            $oldValues = $user->toArray();

            // Remover avatar
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            $user->delete();

            ActivityLog::log('delete', 'User', "Excluiu usuário: {$userName}", null, $oldValues, null);

            DB::commit();

            return redirect()->route('users.index')
                ->with('success', 'Usuário excluído com sucesso!');

        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()
                ->with('error', 'Erro ao excluir usuário: ' . $e->getMessage());
        }
    }

    /**
     * Change user password
     */
    public function changePassword(Request $request, User $user)
    {
        $request->validate([
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user->changePassword($request->password);

        ActivityLog::log('update', 'User', "Alterou senha do usuário: {$user->name}", $user->id);

        return redirect()->back()
            ->with('success', 'Senha alterada com sucesso!');
    }

    /**
     * Toggle user status
     */
    public function toggleStatus(User $user)
    {
        // Não permitir desativar o próprio usuário
        if ($user->id === Auth::id()) {
            return redirect()->back()
                ->with('error', 'Você não pode alterar o status da sua própria conta.');
        }

        $oldStatus = $user->status;
        $newStatus = $user->status === 'active' ? 'inactive' : 'active';

        $user->update(['status' => $newStatus]);

        ActivityLog::log('update', 'User', "Alterou status do usuário {$user->name} de {$oldStatus} para {$newStatus}", $user->id);

        return redirect()->back()
            ->with('success', 'Status do usuário alterado com sucesso!');
    }

    /**
     * Update user permissions
     */
    public function updatePermissions(Request $request, User $user)
    {
        $request->validate([
            'permissions' => 'nullable|array',
            'permissions.*' => 'string|in:' . implode(',', array_keys(User::PERMISSIONS)),
        ]);

        $oldPermissions = $user->permissions;
        $user->update(['permissions' => $request->permissions ?? []]);

        ActivityLog::log('update', 'User', "Atualizou permissões do usuário: {$user->name}", $user->id,
            ['permissions' => $oldPermissions],
            ['permissions' => $user->permissions]
        );

        return redirect()->back()
            ->with('success', 'Permissões atualizadas com sucesso!');
    }

    /**
     * Get users for API
     */
    public function apiIndex(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $users = $query->orderBy('name')
            ->limit(20)
            ->get(['id', 'name', 'email', 'role', 'status']);

        return response()->json($users);
    }
}
