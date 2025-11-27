<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SkyraMart Reset Password</title>
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
        
        .success-message {
            animation: slideIn 0.5s ease-out;
        }
        
        @keyframes slideIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
    <div class="card w-full max-w-md">
        <div class="p-8">
            <!-- Header dengan animasi -->
            <div class="text-center mb-8">
                <div class="floating-icon inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-100 text-blue-600 mb-4">
                    <i class="fas fa-key text-xl"></i>
                </div>
                <h1 class="text-2xl font-bold text-gray-800">Reset Password</h1>
                <p class="text-gray-600 mt-2">Please enter your email address to receive a password reset link.</p>
            </div>
            
            <!-- Status Session -->
            <div id="session-status" class="success-message mb-4 p-3 rounded-lg bg-green-100 text-green-700 hidden">
                <!-- Status akan dimasukkan di sini melalui JavaScript -->
            </div>
            
            <!-- Form Reset Password -->
            <form method="POST" action="{{ route('password.email') }}" id="reset-form">
                @csrf
                
                <!-- Email Input -->
                <div class="mb-6">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <div class="relative">
                        <input 
                            id="email" 
                            name="email" 
                            type="email" 
                            required 
                            autofocus 
                            class="form-input w-full px-4 py-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Enter your email address"
                        >
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-gray-400"></i>
                        </div>
                    </div>
                    <div id="email-error" class="text-red-500 text-sm mt-2 hidden">
                        <!-- Error akan dimasukkan di sini melalui JavaScript -->
                    </div>
                </div>
                
                <!-- Tombol Aksi -->
                <div class="flex flex-col space-y-4">
                    <button type="submit" class="btn-primary text-white font-medium py-3 px-4 rounded-lg flex items-center justify-center">
                        <i class="fas fa-paper-plane mr-2"></i>
                        Send Reset Link
                    </button>
                    
                    <a href="{{ route('login') }}" class="btn-secondary text-gray-700 font-medium py-3 px-4 rounded-lg border border-gray-300 text-center flex items-center justify-center">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Login
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Simulasi status session (dalam implementasi nyata, ini akan berasal dari Laravel)
        document.addEventListener('DOMContentLoaded', function() {
            // Contoh status session (untuk demo)
            const statusMessage = "{{ session('status') }}";
            if (statusMessage) {
                const statusElement = document.getElementById('session-status');
                statusElement.textContent = statusMessage;
                statusElement.classList.remove('hidden');
                
                // Sembunyikan status setelah 5 detik
                setTimeout(() => {
                    statusElement.classList.add('hidden');
                }, 5000);
            }
            
            // Simulasi error (untuk demo)
            const emailError = "{{ $errors->first('email') }}";
            if (emailError) {
                const errorElement = document.getElementById('email-error');
                errorElement.textContent = emailError;
                errorElement.classList.remove('hidden');
            }
            
            // Animasi saat form submit
            const form = document.getElementById('reset-form');
            form.addEventListener('submit', function(e) {
                const button = form.querySelector('button[type="submit"]');
                button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Sending...';
                button.disabled = true;
                
                // Simulasi pengiriman (dalam implementasi nyata, ini akan otomatis)
                setTimeout(() => {
                    button.innerHTML = '<i class="fas fa-check mr-2"></i> Successfully sent!';
                    
                    // Reset button setelah 2 detik
                    setTimeout(() => {
                        button.innerHTML = '<i class="fas fa-paper-plane mr-2"></i> Send Reset Link';
                        button.disabled = false;
                    }, 2000);
                }, 1500);
            });
        });
    </script>
</body>
</html>