<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    public function index(Request $request): Response
    {
        $users = User::query()
            ->with('roles')
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString()
            ->through(fn (User $user): array => $this->formatUserForList($user));

        return Inertia::render('users/Index', [
            'users' => $users,
        ]);
    }

    public function create(): Response
    {
        $roles = $this->getRolesForSelect();

        return Inertia::render('users/Create', [
            'roles' => $roles,
        ]);
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
        ]);

        $user->assignRole($data['role']);

        return redirect()->route('users.index')->with('success', 'Usuário cadastrado com sucesso.');
    }

    public function edit(User $user): Response
    {
        $roles = $this->getRolesForSelect();

        $userData = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->roles->first()?->name ?? '',
        ];

        return Inertia::render('users/Edit', [
            'user' => $userData,
            'roles' => $roles,
        ]);
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $data = $request->validated();

        $user->update([
            'name' => $data['name'],
            'email' => $data['email'],
        ]);

        if (! empty($data['password'])) {
            $user->update(['password' => $data['password']]);
        }

        $user->syncRoles([$data['role']]);

        return redirect()->route('users.index')->with('success', 'Usuário atualizado com sucesso.');
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')->with('error', 'Você não pode excluir sua própria conta.');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'Usuário excluído com sucesso.');
    }

    /**
     * @return array<string, mixed>
     */
    private function formatUserForList(User $user): array
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->roles->first()?->name ?? '-',
        ];
    }

    /**
     * @return array<int, array{value: string, label: string}>
     */
    private function getRolesForSelect(): array
    {
        return \Spatie\Permission\Models\Role::query()
            ->where('guard_name', 'web')
            ->orderBy('name')
            ->get()
            ->map(fn ($role): array => [
                'value' => $role->name,
                'label' => ucfirst($role->name),
            ])
            ->values()
            ->all();
    }
}
