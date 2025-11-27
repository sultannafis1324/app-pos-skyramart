<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SkyraMart New Password</title>
    <link rel="icon" href="{{ asset('images/Skyra-L1.png') }}" type="image/png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #87CEEB 0%, #6AB0E6 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            animation: fadeIn 0.8s ease-out;
            transition: transform 0.3s ease;
            width: 100%;
            max-width: 480px;
        }
        
        .card:hover {
            transform: translateY(-5px);
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .form-input {
            transition: all 0.3s ease;
            border: 1px solid #e2e8f0;
        }
        
        .form-input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(59, 130, 246, 0.4);
        }
        
        .btn-primary:active {
            transform: translateY(0);
        }
        
        .btn-primary::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }
        
        .btn-primary:hover::after {
            left: 100%;
        }
        
        .btn-secondary {
            transition: all 0.3s ease;
        }
        
        .btn-secondary:hover {
            background-color: #f1f5f9;
            transform: translateX(-3px);
        }
        
        .floating-icon {
            animation: float 3s ease-in-out infinite;
        }
        
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }
        
        .error-message {
            animation: slideIn 0.5s ease-out;
        }
        
        @keyframes slideIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .password-toggle {
            cursor: pointer;
            transition: color 0.3s ease;
        }
        
        .password-toggle:hover {
            color: #3b82f6;
        }
        
        .password-strength {
            height: 4px;
            border-radius: 2px;
            margin-top: 4px;
            transition: all 0.3s ease;
        }
        
        .strength-weak {
            background-color: #ef4444;
            width: 25%;
        }
        
        .strength-medium {
            background-color: #f59e0b;
            width: 50%;
        }
        
        .strength-strong {
            background-color: #10b981;
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="p-8">
            <!-- Header dengan animasi -->
            <div class="text-center mb-8">
                <div class="floating-icon inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-100 text-blue-600 mb-4">
                    <i class="fas fa-lock text-xl"></i>
                </div>
                <h1 class="text-2xl font-bold text-gray-800">Reset Password</h1>
                <p class="text-gray-600 mt-2">Buat password baru untuk akun Anda</p>
            </div>
            
            <!-- Form Reset Password -->
            <form method="POST" action="{{ route('password.store') }}" id="reset-form">
                @csrf

                <!-- Password Reset Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <!-- Email Address -->
                <div class="mb-6">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <div class="relative">
                        <input 
                            id="email" 
                            name="email" 
                            type="email" 
                            class="form-input w-full px-4 py-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            value="{{ old('email', $request->email) }}" 
                            required 
                            autofocus 
                            autocomplete="username"
                            placeholder="Masukkan alamat email Anda"
                        >
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-gray-400"></i>
                        </div>
                    </div>
                    <div id="email-error" class="error-message text-red-500 text-sm mt-2 hidden">
                        <!-- Error akan dimasukkan di sini melalui JavaScript -->
                    </div>
                </div>

                <!-- Password -->
                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password Baru</label>
                    <div class="relative">
                        <input 
                            id="password" 
                            name="password" 
                            type="password" 
                            class="form-input w-full px-4 py-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required 
                            autocomplete="new-password"
                            placeholder="Masukkan password baru"
                        >
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <i class="fas fa-eye password-toggle text-gray-400" id="toggle-password"></i>
                        </div>
                    </div>
                    <div id="password-strength" class="password-strength strength-weak hidden"></div>
                    <div id="password-error" class="error-message text-red-500 text-sm mt-2 hidden">
                        <!-- Error akan dimasukkan di sini melalui JavaScript -->
                    </div>
                </div>

                <!-- Confirm Password -->
                <div class="mb-6">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password</label>
                    <div class="relative">
                        <input 
                            id="password_confirmation" 
                            name="password_confirmation" 
                            type="password" 
                            class="form-input w-full px-4 py-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required 
                            autocomplete="new-password"
                            placeholder="Konfirmasi password baru"
                        >
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <i class="fas fa-eye password-toggle text-gray-400" id="toggle-password-confirmation"></i>
                        </div>
                    </div>
                    <div id="password-confirmation-error" class="error-message text-red-500 text-sm mt-2 hidden">
                        <!-- Error akan dimasukkan di sini melalui JavaScript -->
                    </div>
                </div>

                <!-- Tombol Aksi -->
                <div class="flex flex-col space-y-4">
                    <button type="submit" class="btn-primary text-white font-medium py-3 px-4 rounded-lg flex items-center justify-center">
                        <i class="fas fa-key mr-2"></i>
                        Reset Password
                    </button>
                    
                    <a href="{{ route('login') }}" class="btn-secondary text-gray-700 font-medium py-3 px-4 rounded-lg border border-gray-300 text-center flex items-center justify-center">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali ke Login
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle visibility password
            const togglePassword = document.getElementById('toggle-password');
            const togglePasswordConfirmation = document.getElementById('toggle-password-confirmation');
            const passwordInput = document.getElementById('password');
            const passwordConfirmationInput = document.getElementById('password_confirmation');
            const passwordStrength = document.getElementById('password-strength');
            
            togglePassword.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                this.classList.toggle('fa-eye');
                this.classList.toggle('fa-eye-slash');
            });
            
            togglePasswordConfirmation.addEventListener('click', function() {
                const type = passwordConfirmationInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordConfirmationInput.setAttribute('type', type);
                this.classList.toggle('fa-eye');
                this.classList.toggle('fa-eye-slash');
            });
            
            // Password strength indicator
            passwordInput.addEventListener('input', function() {
                const password = this.value;
                let strength = 0;
                
                if (password.length === 0) {
                    passwordStrength.classList.add('hidden');
                    return;
                }
                
                passwordStrength.classList.remove('hidden');
                
                // Check password strength
                if (password.length >= 8) strength++;
                if (/[a-z]/.test(password)) strength++;
                if (/[A-Z]/.test(password)) strength++;
                if (/[0-9]/.test(password)) strength++;
                if (/[^a-zA-Z0-9]/.test(password)) strength++;
                
                // Update strength indicator
                passwordStrength.className = 'password-strength';
                if (strength <= 2) {
                    passwordStrength.classList.add('strength-weak');
                } else if (strength <= 4) {
                    passwordStrength.classList.add('strength-medium');
                } else {
                    passwordStrength.classList.add('strength-strong');
                }
            });
            
            // Simulasi error (untuk demo)
            const emailError = "{{ $errors->first('email') }}";
            if (emailError) {
                const errorElement = document.getElementById('email-error');
                errorElement.textContent = emailError;
                errorElement.classList.remove('hidden');
            }
            
            const passwordError = "{{ $errors->first('password') }}";
            if (passwordError) {
                const errorElement = document.getElementById('password-error');
                errorElement.textContent = passwordError;
                errorElement.classList.remove('hidden');
            }
            
            const passwordConfirmationError = "{{ $errors->first('password_confirmation') }}";
            if (passwordConfirmationError) {
                const errorElement = document.getElementById('password-confirmation-error');
                errorElement.textContent = passwordConfirmationError;
                errorElement.classList.remove('hidden');
            }
            
            // Animasi saat form submit
            const form = document.getElementById('reset-form');
            form.addEventListener('submit', function(e) {
                const button = form.querySelector('button[type="submit"]');
                button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Memproses...';
                button.disabled = true;
                
                // Validasi tambahan sebelum submit
                const password = document.getElementById('password').value;
                const passwordConfirmation = document.getElementById('password_confirmation').value;
                
                if (password !== passwordConfirmation) {
                    e.preventDefault();
                    const errorElement = document.getElementById('password-confirmation-error');
                    errorElement.textContent = 'Konfirmasi password tidak sesuai';
                    errorElement.classList.remove('hidden');
                    
                    button.innerHTML = '<i class="fas fa-key mr-2"></i> Reset Password';
                    button.disabled = false;
                }
            });
        });
    </script>
</body>
</html>