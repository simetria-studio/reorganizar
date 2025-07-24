@extends('layouts.admin')

@section('title', 'Criar Usuário')

@section('breadcrumb')
<span class="breadcrumb-item">Painel</span>
<i class="fas fa-chevron-right"></i>
<span class="breadcrumb-item"><a href="{{ route('users.index') }}">Usuários</a></span>
<i class="fas fa-chevron-right"></i>
<span class="breadcrumb-item active">Criar Usuário</span>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary-custom text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="fas fa-user-plus me-2"></i>
                            Criar Novo Usuário
                        </h4>
                        <a href="{{ route('users.index') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>
                            Voltar à Lista
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

                    <form method="POST" action="{{ route('users.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <!-- Foto do Perfil -->
                            <div class="col-md-3">
                                <div class="text-center mb-4">
                                    <div class="mb-3">
                                        <img id="avatar-preview"
                                             src="https://ui-avatars.com/api/?name=Novo+Usuário&size=150&background=004AAD&color=ffffff"
                                             alt="Avatar Preview"
                                             class="rounded-circle"
                                             style="width: 150px; height: 150px; object-fit: cover;">
                                    </div>
                                    <div class="mb-3">
                                        <label for="avatar" class="form-label">Foto do Perfil</label>
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
                                            <label for="name" class="form-label">Nome Completo <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="name" name="name"
                                                   value="{{ old('name') }}" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="email" class="form-label">E-mail <span class="text-danger">*</span></label>
                                            <input type="email" class="form-control" id="email" name="email"
                                                   value="{{ old('email') }}" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="phone" class="form-label">Telefone</label>
                                            <input type="text" class="form-control" id="phone" name="phone"
                                                   value="{{ old('phone') }}" placeholder="(11) 99999-9999">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="department" class="form-label">Departamento</label>
                                            <input type="text" class="form-control" id="department" name="department"
                                                   value="{{ old('department') }}" placeholder="Ex: TI, RH, Financeiro">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="role" class="form-label">Função <span class="text-danger">*</span></label>
                                            <select class="form-select" id="role" name="role" required>
                                                <option value="">Selecione uma função</option>
                                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>
                                                    Administrador
                                                </option>
                                                <option value="manager" {{ old('role') == 'manager' ? 'selected' : '' }}>
                                                    Gerente
                                                </option>
                                                <option value="operator" {{ old('role') == 'operator' ? 'selected' : '' }}>
                                                    Operador
                                                </option>
                                            </select>
                                            <small class="form-text text-muted">
                                                <strong>Admin:</strong> Acesso total ao sistema<br>
                                                <strong>Gerente:</strong> Gerenciamento de dados<br>
                                                <strong>Operador:</strong> Operações básicas
                                            </small>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                            <select class="form-select" id="status" name="status" required>
                                                <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>
                                                    Ativo
                                                </option>
                                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>
                                                    Inativo
                                                </option>
                                                <option value="suspended" {{ old('status') == 'suspended' ? 'selected' : '' }}>
                                                    Suspenso
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Senha -->
                                <div class="card mt-4">
                                    <div class="card-header">
                                        <h5 class="mb-0">Configuração de Senha</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="password" class="form-label">Senha <span class="text-danger">*</span></label>
                                                    <input type="password" class="form-control" id="password" name="password"
                                                           minlength="6" required>
                                                    <small class="form-text text-muted">Mínimo de 6 caracteres</small>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="password_confirmation" class="form-label">Confirmar Senha <span class="text-danger">*</span></label>
                                                    <input type="password" class="form-control" id="password_confirmation"
                                                           name="password_confirmation" required>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="is_first_login" name="is_first_login"
                                                   value="1" {{ old('is_first_login', '1') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_first_login">
                                                Usuário deve alterar a senha no primeiro login
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Permissões (apenas para admins) -->
                                @if(Auth::user()->isAdmin())
                                <div class="card mt-4">
                                    <div class="card-header">
                                        <h5 class="mb-0">Permissões Especiais</h5>
                                        <small class="text-muted">Permissões específicas além da função padrão</small>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            @foreach(\App\Models\User::PERMISSIONS as $key => $description)
                                                <div class="col-md-4 mb-2">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                               id="permission_{{ $key }}" name="permissions[]" value="{{ $key }}"
                                                               {{ in_array($key, old('permissions', [])) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="permission_{{ $key }}">
                                                            <small>{{ $description }}</small>
                                                        </label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                @endif

                                <!-- Botões -->
                                <div class="d-flex justify-content-between mt-4">
                                    <a href="{{ route('users.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times me-1"></i>
                                        Cancelar
                                    </a>
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-save me-1"></i>
                                        Criar Usuário
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
            document.getElementById('avatar-preview').src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
});

// Atualizar preview do avatar baseado no nome
document.getElementById('name').addEventListener('input', function() {
    const name = this.value || 'Novo Usuário';
    const avatarPreview = document.getElementById('avatar-preview');

    // Só atualizar se não há arquivo selecionado
    if (!document.getElementById('avatar').files.length) {
        const encodedName = encodeURIComponent(name);
        avatarPreview.src = `https://ui-avatars.com/api/?name=${encodedName}&size=150&background=004AAD&color=ffffff`;
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

// Formatação do telefone
document.getElementById('phone').addEventListener('input', function() {
    let value = this.value.replace(/\D/g, '');

    if (value.length <= 11) {
        value = value.replace(/(\d{2})(\d)/, '($1) $2');
        value = value.replace(/(\d{4,5})(\d{4})$/, '$1-$2');
        this.value = value;
    }
});

// Marcar/desmarcar todas as permissões por função
document.getElementById('role').addEventListener('change', function() {
    const role = this.value;
    const checkboxes = document.querySelectorAll('input[name="permissions[]"]');

    // Desmarcar todas primeiro
    checkboxes.forEach(cb => cb.checked = false);

    // Marcar permissões baseadas na função
    if (role === 'admin') {
        checkboxes.forEach(cb => cb.checked = true);
    } else if (role === 'manager') {
        const managerPermissions = ['view_users', 'manage_students', 'manage_schools', 'view_reports'];
        checkboxes.forEach(cb => {
            if (managerPermissions.includes(cb.value)) {
                cb.checked = true;
            }
        });
    } else if (role === 'operator') {
        const operatorPermissions = ['view_students', 'manage_notes'];
        checkboxes.forEach(cb => {
            if (operatorPermissions.includes(cb.value)) {
                cb.checked = true;
            }
        });
    }
});

// Validação do formulário
document.querySelector('form').addEventListener('submit', function(e) {
    const password = document.getElementById('password').value;
    const confirm = document.getElementById('password_confirmation').value;

    if (password !== confirm) {
        e.preventDefault();
        alert('As senhas não coincidem!');
        return false;
    }

    if (password.length < 6) {
        e.preventDefault();
        alert('A senha deve ter pelo menos 6 caracteres!');
        return false;
    }
});
</script>

<style>
.bg-primary-custom {
    background: linear-gradient(135deg, #004AAD 0%, #0066cc 100%) !important;
}

.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border: 1px solid rgba(0, 0, 0, 0.125);
}

.form-control:focus {
    border-color: #004AAD;
    box-shadow: 0 0 0 0.2rem rgba(0, 74, 173, 0.25);
}

.form-select:focus {
    border-color: #004AAD;
    box-shadow: 0 0 0 0.2rem rgba(0, 74, 173, 0.25);
}

.text-danger {
    color: #dc3545 !important;
}

.form-check-input:checked {
    background-color: #004AAD;
    border-color: #004AAD;
}

.btn-success {
    background-color: #28a745;
    border-color: #28a745;
}

.btn-success:hover {
    background-color: #218838;
    border-color: #1e7e34;
}

#avatar-preview {
    border: 3px solid #e9ecef;
    transition: all 0.3s ease;
}

#avatar-preview:hover {
    border-color: #004AAD;
    transform: scale(1.05);
}

.card-header {
    border-bottom: 1px solid rgba(0, 0, 0, 0.125);
}

.small {
    font-size: 0.875em;
}
</style>
@endsection
