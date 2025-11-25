<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - SPMB</title>
    <link href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #2da0a8 0%, #1a7a80 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .reset-container {
            background: #fff;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            max-width: 450px;
            width: 100%;
        }
        .reset-container h2 {
            color: #2da0a8;
            margin-bottom: 30px;
            text-align: center;
        }
        .form-control {
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #ddd;
            margin-bottom: 15px;
        }
        .btn-reset {
            background: #2da0a8;
            color: #fff;
            padding: 12px;
            border: none;
            border-radius: 8px;
            width: 100%;
            font-weight: 600;
            text-transform: uppercase;
            cursor: pointer;
            transition: all 0.3s;
        }
        .btn-reset:hover {
            background: #1a7a80;
        }
        .alert {
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="reset-container">
        <h2>Reset Password</h2>
        
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        
        <div id="message" style="display:none;" class="alert"></div>
        
        <form id="resetForm">
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ $email }}">
            
            <div class="mb-3">
                <label>Email</label>
                <input type="email" class="form-control" value="{{ $email }}" disabled>
            </div>
            
            <div class="mb-3">
                <label>Password Baru</label>
                <div style="position:relative;">
                    <input type="password" id="newPassword" name="password" class="form-control" placeholder="Masukkan password baru" required style="padding-right:40px;">
                    <i class="fas fa-eye" onclick="togglePasswordReset('newPassword', this)" style="position:absolute;right:12px;top:50%;transform:translateY(-50%);cursor:pointer;color:#999;"></i>
                </div>
            </div>
            
            <div class="mb-3">
                <label>Konfirmasi Password</label>
                <div style="position:relative;">
                    <input type="password" id="confirmPassword" name="password_confirmation" class="form-control" placeholder="Konfirmasi password baru" required style="padding-right:40px;">
                    <i class="fas fa-eye" onclick="togglePasswordReset('confirmPassword', this)" style="position:absolute;right:12px;top:50%;transform:translateY(-50%);cursor:pointer;color:#999;"></i>
                </div>
            </div>
            
            <button type="submit" class="btn-reset">Reset Password</button>
        </form>
        
        <div style="text-align: center; margin-top: 20px;">
            <a href="/" style="color: #2da0a8; text-decoration: none;">Kembali ke Login</a>
        </div>
    </div>

    <script>
        document.getElementById('resetForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const data = Object.fromEntries(formData);
            const messageDiv = document.getElementById('message');
            const submitBtn = this.querySelector('button[type="submit"]');
            
            submitBtn.disabled = true;
            submitBtn.textContent = 'Memproses...';
            messageDiv.style.display = 'none';
            
            try {
                const response = await fetch('/reset-password', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(data)
                });
                
                const result = await response.json();
                
                if (result.success) {
                    messageDiv.className = 'alert alert-success';
                    messageDiv.textContent = result.message;
                    messageDiv.style.display = 'block';
                    
                    setTimeout(() => {
                        window.location.href = '/';
                    }, 2000);
                } else {
                    messageDiv.className = 'alert alert-danger';
                    messageDiv.textContent = result.message || 'Terjadi kesalahan';
                    messageDiv.style.display = 'block';
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Reset Password';
                }
            } catch (error) {
                messageDiv.className = 'alert alert-danger';
                messageDiv.textContent = 'Terjadi kesalahan. Silakan coba lagi.';
                messageDiv.style.display = 'block';
                submitBtn.disabled = false;
                submitBtn.textContent = 'Reset Password';
            }
        });
        
        function togglePasswordReset(inputId, icon) {
            const input = document.getElementById(inputId);
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>
