@extends('layouts.admin')

@section('title', 'Gerenciar Usuários')

@section('breadcrumb')
<span class="breadcrumb-item">Painel</span>
<i class="fas fa-chevron-right"></i>
<span class="breadcrumb-item active">Usuários</span>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="text-dark">Gerenciar Usuários</h2>
                <a href="{{ route('users.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i>
                    Novo Usuário
                </a>
            </div>

            <!-- Filtros -->
            <div class="card mb-4">
                <div class="card-header bg-primary-custom text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-filter me-2"></i>
                        Filtros
                    </h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('users.index') }}">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="search" class="form-label">Buscar</label>
                                    <input type="text" class="form-control" id="search" name="search"
                                           value="{{ request('search') }}" placeholder="Nome ou e-mail">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="role" class="form-label">Função</label>
                                    <select class="form-select" id="role" name="role">
                                        <option value="">Todas as funções</option>
                                        <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Administrador</option>
                                        <option value="manager" {{ request('role') === 'manager' ? 'selected' : '' }}>Gerente</option>
                                        <option value="operator" {{ request('role') === 'operator' ? 'selected' : '' }}>Operador</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-select" id="status" name="status">
                                        <option value="">Todos os status</option>
                                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Ativo</option>
                                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inativo</option>
                                        <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspenso</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="department" class="form-label">Departamento</label>
                                    <input type="text" class="form-control" id="department" name="department"
                                           value="{{ request('department') }}" placeholder="Departamento">
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search me-1"></i>
                                Filtrar
                            </button>
                            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-1"></i>
                                Limpar Filtros
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Lista de Usuários -->
            <div class="card">
                <div class="card-header bg-info text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-users me-2"></i>
                            Usuários do Sistema
                        </h5>
                        <div class="badge bg-light text-dark">
                            Total: {{ $users->total() }}
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($users->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 25%">Usuário</th>
                                        <th style="width: 15%">Função</th>
                                        <th style="width: 15%">Status</th>
                                        <th style="width: 15%">Departamento</th>
                                        <th style="width: 15%">Último Login</th>
                                        <th style="width: 15%">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $user->avatar_url }}"
                                                     class="rounded-circle me-3"
                                                     style="width: 40px; height: 40px; object-fit: cover;">
                                                <div>
                                                    <div class="fw-bold">{{ $user->name }}</div>
                                                    <small class="text-muted">{{ $user->email }}</small>
                                                    @if($user->phone)
                                                        <br><small class="text-muted">{{ $user->phone }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{
                                                $user->role === 'admin' ? 'danger' :
                                                ($user->role === 'manager' ? 'warning' : 'info')
                                            }}">
                                                {{ $user->role_name }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge {{ $user->status_badge_class }}">
                                                {{ $user->status_name }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="text-muted">{{ $user->department ?? '-' }}</span>
                                        </td>
                                        <td>
                                            @if($user->last_login_at)
                                                <div class="small">
                                                    <div>{{ $user->last_login_at->format('d/m/Y') }}</div>
                                                    <div class="text-muted">{{ $user->last_login_at->format('H:i') }}</div>
                                                </div>
                                            @else
                                                <span class="text-muted">Nunca</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                <a href="{{ route('users.show', $user) }}"
                                                   class="btn btn-sm btn-outline-info" title="Ver Perfil">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('users.edit', $user) }}"
                                                   class="btn btn-sm btn-outline-primary" title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>

                                                @if(Auth::id() !== $user->id)
                                                    <!-- Botão Ativar/Desativar -->
                                                    <form method="POST" action="{{ route('users.toggle-status', $user) }}" class="d-inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit"
                                                               class="btn btn-sm btn-outline-{{ $user->status === 'active' ? 'warning' : 'success' }}"
                                                               title="{{ $user->status === 'active' ? 'Desativar' : 'Ativar' }}"
                                                               onclick="return confirm('Tem certeza?')">
                                                            <i class="fas fa-{{ $user->status === 'active' ? 'user-slash' : 'user-check' }}"></i>
                                                        </button>
                                                    </form>

                                                    @if(Auth::user()->isAdmin())
                                                        <!-- Botão Excluir -->
                                                        <button type="button" class="btn btn-sm btn-outline-danger"
                                                               title="Excluir"
                                                               onclick="confirmDelete({{ $user->id }}, '{{ addslashes($user->name) }}')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    @endif
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Paginação -->
                        <div class="card-footer">
                            {{ $users->withQueryString()->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Nenhum usuário encontrado</h5>
                            <p class="text-muted">
                                @if(request()->hasAny(['search', 'role', 'status', 'department']))
                                    Tente ajustar os filtros ou limpar todos os filtros
                                @else
                                    Crie o primeiro usuário do sistema
                                @endif
                            </p>
                            <a href="{{ route('users.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i>
                                Criar Primeiro Usuário
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmação de Exclusão -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar Exclusão</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Tem certeza que deseja excluir o usuário <strong id="deleteUserName"></strong>?</p>
                <p class="text-danger">Esta ação não pode ser desfeita.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form id="deleteForm" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Excluir</button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.bg-primary-custom {
    background: linear-gradient(135deg, #004AAD 0%, #0066cc 100%) !important;
}

.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border: 1px solid rgba(0, 0, 0, 0.125);
}

.table th {
    font-weight: 600;
    color: #495057;
    border-bottom: 2px solid #dee2e6;
}

.table tbody tr:hover {
    background-color: #f8f9fa;
}

.badge {
    font-size: 0.75em;
}

.btn-sm {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
}

.d-flex.gap-1 > * {
    margin-right: 0.25rem;
}

.d-flex.gap-1 > *:last-child {
    margin-right: 0;
}
</style>

<script>
function confirmDelete(userId, userName) {
    document.getElementById('deleteUserName').textContent = userName;
    document.getElementById('deleteForm').action = `/users/${userId}`;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>
@endsection
