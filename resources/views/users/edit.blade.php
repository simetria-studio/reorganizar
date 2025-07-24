@extends('layouts.admin')

@section('title', 'Editar Perfil')

@section('breadcrumb')
<span class="breadcrumb-item">Painel</span>
<i class="fas fa-chevron-right"></i>
<span class="breadcrumb-item"><a href="{{ route('users.index') }}">Usuários</a></span>
<i class="fas fa-chevron-right"></i>
<span class="breadcrumb-item active">Editar Perfil</span>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary-custom text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="fas fa-user-edit me-2"></i>
                            Editar Perfil - {{ $user->name }}
                        </h4>
                        <a href="{{ route('users.show', $user) }}" class="btn btn-light btn-sm">
                            <i class="fas fa-eye me-1"></i>
                            Ver Perfil
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('users.update', $user) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <!-- Foto do Perfil -->
                            <div class="col-md-3">
                                <div class="text-center mb-4">
                                    <div class="mb-3">
                                        <img src="{{ $user->avatar_url }}"
                                             alt="Avatar"
                                             class="rounded-circle"
                                             style="width: 150px; height: 150px; object-fit: cover;">
                                    </div>
                                    <div class="mb-3">
                                        <label for="avatar" class="form-label">Alterar Foto</label>
                                        <input type="file" class="form-control" id="avatar" name="avatar" accept="image/*">
                                        <small class="form-text text-muted">JPG, PNG ou GIF. Máximo 2MB.</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Informações Pessoais -->
                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Nome Completo</label>
                                            <input type="text" class="form-control" id="name" name="name"
                                                   value="{{ old('name', $user->name) }}" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="email" class="form-label">E-mail</label>
                                            <input type="email" class="form-control" id="email" name="email"
                                                   value="{{ old('email', $user->email) }}" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="phone" class="form-label">Telefone</label>
                                            <input type="text" class="form-control" id="phone" name="phone"
                                                   value="{{ old('phone', $user->phone) }}">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="department" class="form-label">Departamento</label>
                                            <input type="text" class="form-control" id="department" name="department"
                                                   value="{{ old('department', $user->department) }}">
                                        </div>
                                    </div>

                                    @if(Auth::user()->isAdmin() && Auth::id() !== $user->id)
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="role" class="form-label">Função</label>
                                            <select class="form-select" id="role" name="role" required>
                                                <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>
                                                    Administrador
                                                </option>
                                                <option value="manager" {{ old('role', $user->role) == 'manager' ? 'selected' : '' }}>
                                                    Gerente
                                                </option>
                                                <option value="operator" {{ old('role', $user->role) == 'operator' ? 'selected' : '' }}>
                                                    Operador
                                                </option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="status" class="form-label">Status</label>
                                            <select class="form-select" id="status" name="status" required>
                                                <option value="active" {{ old('status', $user->status) == 'active' ? 'selected' : '' }}>
                                                    Ativo
                                                </option>
                                                <option value="inactive" {{ old('status', $user->status) == 'inactive' ? 'selected' : '' }}>
                                                    Inativo
                                                </option>
                                                <option value="suspended" {{ old('status', $user->status) == 'suspended' ? 'selected' : '' }}>
                                                    Suspenso
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    @else
                                    <input type="hidden" name="role" value="{{ $user->role }}">
                                    <input type="hidden" name="status" value="{{ $user->status }}">
                                    @endif
                                </div>

                                <!-- Alterar Senha -->
                                <div class="card mt-4">
                                    <div class="card-header">
                                        <h5 class="mb-0">Alterar Senha</h5>
                                        <small class="text-muted">Deixe em branco se não deseja alterar</small>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="password" class="form-label">Nova Senha</label>
                                                    <input type="password" class="form-control" id="password" name="password"
                                                           minlength="6">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="password_confirmation" class="form-label">Confirmar Senha</label>
                                                    <input type="password" class="form-control" id="password_confirmation"
                                                           name="password_confirmation">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Botões -->
                                <div class="d-flex justify-content-between mt-4">
                                    <a href="{{ route('users.show', $user) }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left me-1"></i>
                                        Cancelar
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-1"></i>
                                        Salvar Alterações
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Preview da imagem antes do upload
document.getElementById('avatar').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.querySelector('img').src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
});

// Validação da confirmação de senha
document.getElementById('password_confirmation').addEventListener('input', function() {
    const password = document.getElementById('password').value;
    const confirm = this.value;

    if (password && confirm && password !== confirm) {
        this.setCustomValidity('As senhas não coincidem');
    } else {
        this.setCustomValidity('');
    }
});
</script>
@endsection
