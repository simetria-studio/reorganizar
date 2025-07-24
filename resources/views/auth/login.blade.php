@extends('layouts.app', ['hideNavbar' => true])

@section('content')
<div class="login-page-container">
    <!-- Background Animation -->
    <div class="background-animation">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
        <div class="shape shape-4"></div>
    </div>

        <div class="container-fluid h-100 d-flex align-items-center justify-content-center">
        <div class="login-form-container">
            <div class="login-card">
                <div class="card-header-custom">
                    <div class="logo-container mb-4">
                        <img src="{{ asset('img/logo.png') }}" alt="Logo" class="login-logo">
                    </div>
                    <h1 class="system-title">Certificado Digital Escolar</h1>
                    <h2 class="login-title">Bem-vindo de volta!</h2>
                    <p class="login-subtitle">Faça login para acessar sua conta</p>
                </div>

                        <div class="card-body-custom">
                            @if($errors->any())
                                <div class="alert alert-danger alert-modern" role="alert">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <div>
                                        @foreach($errors->all() as $error)
                                            <div>{{ $error }}</div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if(session('status'))
                                <div class="alert alert-success alert-modern" role="alert">
                                    <i class="fas fa-check-circle me-2"></i>
                                    {{ session('status') }}
                                </div>
                            @endif

                            <form method="POST" action="{{ route('login') }}" class="login-form">
                                @csrf

                                <div class="form-group-modern">
                                    <div class="input-container">
                                        <i class="fas fa-envelope input-icon"></i>
                                        <input type="email"
                                               class="form-control-modern @error('email') is-invalid @enderror"
                                               id="email"
                                               name="email"
                                               value="{{ old('email') }}"
                                               required
                                               autocomplete="email"
                                               autofocus
                                               placeholder=" ">
                                        <label for="email" class="form-label-modern">E-mail</label>
                                        <div class="input-border"></div>
                                    </div>
                                    @error('email')
                                        <div class="invalid-feedback-modern">
                                            <i class="fas fa-exclamation-circle me-1"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="form-group-modern">
                                    <div class="input-container">
                                        <i class="fas fa-lock input-icon"></i>
                                        <input type="password"
                                               class="form-control-modern @error('password') is-invalid @enderror"
                                               id="password"
                                               name="password"
                                               required
                                               autocomplete="current-password"
                                               placeholder=" ">
                                        <label for="password" class="form-label-modern">Senha</label>
                                        <div class="input-border"></div>
                                        <button type="button" class="password-toggle" onclick="togglePassword()">
                                            <i class="fas fa-eye" id="toggleIcon"></i>
                                        </button>
                                    </div>
                                    @error('password')
                                        <div class="invalid-feedback-modern">
                                            <i class="fas fa-exclamation-circle me-1"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="form-check-modern">
                                    <input type="checkbox"
                                           class="form-check-input-modern"
                                           id="remember"
                                           name="remember"
                                           {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label-modern" for="remember">
                                        Lembrar-me
                                    </label>
                                </div>

                                <button type="submit" class="btn-login">
                                    <span class="btn-text">Entrar</span>
                                    <div class="btn-loader">
                                        <div class="spinner"></div>
                                    </div>
                                    <i class="fas fa-arrow-right btn-icon"></i>
                                </button>

                                @if (Route::has('password.request'))
                                    <div class="forgot-password">
                                        <a href="{{ route('password.request') }}" class="forgot-link">
                                            <i class="fas fa-key me-1"></i>
                                            Esqueceu sua senha?
                                        </a>
                                                                    </div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.login-page-container {
    min-height: 100vh;
    position: relative;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    overflow: hidden;
}

.background-animation {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    overflow: hidden;
    z-index: 1;
}

.shape {
    position: absolute;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.1);
    animation: float 20s infinite ease-in-out;
}

.shape-1 {
    width: 200px;
    height: 200px;
    top: 10%;
    left: 10%;
    animation-delay: 0s;
}

.shape-2 {
    width: 150px;
    height: 150px;
    top: 70%;
    right: 10%;
    animation-delay: 5s;
}

.shape-3 {
    width: 100px;
    height: 100px;
    bottom: 20%;
    left: 20%;
    animation-delay: 10s;
}

.shape-4 {
    width: 300px;
    height: 300px;
    top: 30%;
    right: 30%;
    animation-delay: 15s;
}

@keyframes float {
    0%, 100% { transform: translateY(0px) rotate(0deg); }
    33% { transform: translateY(-30px) rotate(120deg); }
    66% { transform: translateY(20px) rotate(240deg); }
}



.login-form-container {
    width: 100%;
    max-width: 500px;
    position: relative;
    z-index: 2;
    padding: 2rem;
}

.login-card {
    background: rgba(255, 255, 255, 0.95);
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    animation: slideInRight 0.8s ease-out;
}

@keyframes slideInRight {
    from {
        opacity: 0;
        transform: translateX(50px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.card-header-custom {
    padding: 3.5rem 2.5rem 2rem;
    text-align: center;
    background: linear-gradient(135deg, rgba(0, 74, 173, 0.05) 0%, rgba(200, 34, 34, 0.05) 100%);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

.login-logo {
    height: 120px;
    max-width: 120px;
    object-fit: contain;
    margin: 0 auto 1.5rem;
    display: block;
}

.system-title {
    color: var(--primary-color);
    font-weight: 800;
    font-size: 2.2rem;
    margin-bottom: 1rem;
    text-shadow: 0 2px 4px rgba(0, 74, 173, 0.1);
    text-align: center;
}



.login-title {
    color: #333;
    font-weight: 600;
    margin-bottom: 0.8rem;
    font-size: 1.6rem;
    text-align: center;
}

.login-subtitle {
    color: #6c757d;
    margin-bottom: 0;
    text-align: center;
    font-size: 1rem;
}

.card-body-custom {
    padding: 1rem 2.5rem 2.5rem;
}

.alert-modern {
    border: none;
    border-radius: 12px;
    padding: 1rem 1.25rem;
    display: flex;
    align-items: center;
    margin-bottom: 1.5rem;
    backdrop-filter: blur(10px);
}

.alert-danger {
    background: rgba(220, 53, 69, 0.1);
    color: #dc3545;
    border-left: 4px solid #dc3545;
}

.alert-success {
    background: rgba(25, 135, 84, 0.1);
    color: #198754;
    border-left: 4px solid #198754;
}

.form-group-modern {
    margin-bottom: 1.5rem;
}

.input-container {
    position: relative;
}

.form-control-modern {
    width: 100%;
    padding: 1rem 1rem 1rem 3rem;
    border: 2px solid #e9ecef;
    border-radius: 12px;
    font-size: 1rem;
    background: #f8f9fa;
    transition: all 0.3s ease;
    outline: none;
}

.form-control-modern:focus {
    border-color: var(--primary-color);
    background: #fff;
    box-shadow: 0 0 0 0.2rem rgba(0, 74, 173, 0.1);
}

.form-control-modern:focus + .form-label-modern,
.form-control-modern:not(:placeholder-shown) + .form-label-modern {
    transform: translateY(-1.5rem) scale(0.85);
    color: var(--primary-color);
}

.input-icon {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: #6c757d;
    z-index: 2;
    transition: color 0.3s ease;
}

.form-control-modern:focus ~ .input-icon {
    color: var(--primary-color);
}

.form-label-modern {
    position: absolute;
    left: 3rem;
    top: 50%;
    transform: translateY(-50%);
    background: transparent;
    padding: 0 0.5rem;
    color: #6c757d;
    pointer-events: none;
    transition: all 0.3s ease;
    z-index: 1;
}

.input-border {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 0;
    height: 2px;
    background: var(--primary-color);
    transition: width 0.3s ease;
}

.form-control-modern:focus ~ .input-border {
    width: 100%;
}

.password-toggle {
    position: absolute;
    right: 1rem;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: #6c757d;
    cursor: pointer;
    transition: color 0.3s ease;
    z-index: 2;
}

.password-toggle:hover {
    color: var(--primary-color);
}

.invalid-feedback-modern {
    color: #dc3545;
    font-size: 0.875rem;
    margin-top: 0.5rem;
    display: flex;
    align-items: center;
}

.form-check-modern {
    margin-bottom: 2rem;
    display: flex;
    align-items: center;
}

.form-check-input-modern {
    width: 1.25rem;
    height: 1.25rem;
    margin-right: 0.75rem;
    accent-color: var(--primary-color);
}

.form-check-label-modern {
    color: #6c757d;
    font-size: 0.95rem;
    cursor: pointer;
}

.btn-login {
    width: 100%;
    padding: 1rem 2rem;
    background: linear-gradient(135deg, var(--primary-color) 0%, #0066cc 100%);
    border: none;
    border-radius: 12px;
    color: white;
    font-weight: 600;
    font-size: 1.1rem;
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    margin-bottom: 1.5rem;
}

.btn-login:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(0, 74, 173, 0.3);
}

.btn-login:active {
    transform: translateY(0);
}

.btn-text {
    transition: opacity 0.3s ease;
}

.btn-loader {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.spinner {
    width: 20px;
    height: 20px;
    border: 2px solid rgba(255, 255, 255, 0.3);
    border-top: 2px solid white;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.btn-icon {
    margin-left: 0.5rem;
    transition: transform 0.3s ease;
}

.btn-login:hover .btn-icon {
    transform: translateX(5px);
}

.forgot-password {
    text-align: center;
}

.forgot-link {
    color: var(--primary-color);
    text-decoration: none;
    font-size: 0.95rem;
    transition: all 0.3s ease;
}

.forgot-link:hover {
    color: #0066cc;
    text-decoration: underline;
}

/* Logo visibility improvements */
.login-logo {
    /* Garantir que a logo seja sempre visível */
    opacity: 1;
    visibility: visible;
    display: block;
    filter: drop-shadow(0 4px 8px rgba(0, 74, 173, 0.2));
    transition: all 0.3s ease;
    animation: logoEntrance 1s ease-out;
}

.login-logo:hover {
    transform: scale(1.05);
    filter: drop-shadow(0 6px 12px rgba(0, 74, 173, 0.3));
}

@keyframes logoEntrance {
    0% {
        opacity: 0;
        transform: translateY(-20px) scale(0.8);
    }
    100% {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

@media (max-width: 768px) {
    .login-form-container {
        max-width: 90%;
        padding: 1rem;
    }

    .card-header-custom {
        padding: 2rem 1.5rem 1rem;
    }

    .card-body-custom {
        padding: 1rem 1.5rem 2rem;
    }

    .login-logo {
        height: 90px;
        max-width: 90px;
    }

        .system-title {
        font-size: 1.8rem;
    }

    .login-title {
        font-size: 1.4rem;
    }
}
</style>

<script>
function togglePassword() {
    const passwordField = document.getElementById('password');
    const toggleIcon = document.getElementById('toggleIcon');

    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        toggleIcon.classList.remove('fa-eye');
        toggleIcon.classList.add('fa-eye-slash');
    } else {
        passwordField.type = 'password';
        toggleIcon.classList.remove('fa-eye-slash');
        toggleIcon.classList.add('fa-eye');
    }
}

// Loading animation on form submit
document.querySelector('.login-form').addEventListener('submit', function() {
    const btn = document.querySelector('.btn-login');
    const btnText = document.querySelector('.btn-text');
    const btnLoader = document.querySelector('.btn-loader');
    const btnIcon = document.querySelector('.btn-icon');

    btnText.style.opacity = '0';
    btnIcon.style.opacity = '0';
    btnLoader.style.opacity = '1';
    btn.style.pointerEvents = 'none';
});

// Smooth animations on load
document.addEventListener('DOMContentLoaded', function() {
    const inputs = document.querySelectorAll('.form-control-modern');

    inputs.forEach(input => {
        if (input.value) {
            input.nextElementSibling.style.transform = 'translateY(-1.5rem) scale(0.85)';
            input.nextElementSibling.style.color = 'var(--primary-color)';
        }
    });
});
</script>

<!-- Font Awesome -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
@endsection
