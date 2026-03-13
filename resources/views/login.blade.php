<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Growpath</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <style>
        :root {
            --primary: #4A90E2; 
            --primary-hover: #357ABD;
            --text-dark: #1f2937;
            --text-gray: #6b7280;
            --text-light: #9ca3af;
            --bg-body: #f3f4f6;
            --bg-input: #f9fafb;
            --white: #ffffff;
            --border-color: #e5e7eb;
            --shadow-sm: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 20px 40px -5px rgba(0,0,0,0.1);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }

        body {
            background-color: var(--bg-body);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        /* --- Container Utama --- */
        .container {
            background: var(--white);
            width: 100%;
            max-width: 960px;
            height: 600px;
            display: flex;
            border-radius: 24px;
            box-shadow: var(--shadow-lg);
            overflow: hidden;
        }

        /* --- Panel Kiri (Visual Biru) --- */
        .left-panel {
            flex: 1.1;
            background: linear-gradient(145deg, #1E3A8A 0%, var(--primary) 100%);
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 60px;
            color: var(--white);
            overflow: hidden;
        }

        /* Dekorasi Lingkaran */
        .circle-bg {
            position: absolute;
            background: linear-gradient(180deg, rgba(255,255,255,0.15) 0%, rgba(255,255,255,0) 100%);
            border-radius: 50%;
            backdrop-filter: blur(5px);
        }
        .c1 { width: 350px; height: 350px; top: -100px; right: -100px; }
        .c2 { width: 200px; height: 200px; bottom: 50px; left: -80px; }

        .content-overlay { position: relative; z-index: 2; }
        
        /* Style untuk Logo di Panel Kiri */
        .content-overlay .left-logo {
            max-width: 300px;
            height: auto;
            filter: brightness(0) invert(1); 
            text-align: center;
            margin: 0 auto; display: block;
        }

        .content-overlay p { 
            font-size: 1.05rem; 
            line-height: 1.7; 
            color: #d1d5db; 
            font-weight: 300; 
        }

        /* --- Panel Kanan (Form) --- */
        .right-panel {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            background: var(--white);
        }

        .login-wrapper { width: 100%; max-width: 340px; }

        /* --- Header Form --- */
        .header { margin-bottom: 30px; text-align: center; }
        .header h2 { color: var(--text-dark); font-size: 1.8rem; font-weight: 700; margin-bottom: 5px; letter-spacing: -0.5px;}
        .header p { color: var(--text-gray); font-size: 0.9rem; }

        /* --- ROLE SWITCHER (Pill Style) --- */
        .role-switch {
            display: flex;
            background: var(--bg-body);
            padding: 6px;
            border-radius: 16px;
            margin-bottom: 30px;
            gap: 4px;
        }

        .role-btn {
            flex: 1;
            padding: 12px;
            border: none;
            background: transparent;
            cursor: pointer;
            border-radius: 12px;
            font-weight: 600;
            color: var(--text-gray);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-size: 0.9rem;
        }

        .role-btn.active {
            background: var(--white);
            color: var(--primary);
            box-shadow: var(--shadow-sm);
        }

        /* --- INPUT FIELD --- */
        .input-group { margin-bottom: 20px; }
        .input-group label { 
            display: block; color: var(--text-dark); font-size: 0.85rem; 
            margin-bottom: 8px; font-weight: 600; 
        }

        .input-field { position: relative; width: 100%; }
        .input-field input {
            width: 100%;
            padding: 14px 15px 14px 45px;
            background-color: var(--bg-input);
            border: 1px solid var(--border-color);
            border-radius: 14px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            outline: none;
            color: var(--text-dark);
        }
        
        .input-field i.icon {
            position: absolute; left: 16px; top: 50%; transform: translateY(-50%);
            color: var(--text-light); font-size: 1.25rem; transition: 0.3s;
        }

        /* Hover & Focus State */
        .input-field input:hover { border-color: #d1d5db; }
        .input-field input:focus { 
            background-color: var(--white);
            border-color: var(--primary); 
            box-shadow: 0 0 0 4px rgba(74, 144, 226, 0.15); 
        }
        .input-field input:focus + i.icon { color: var(--primary); }

        /* --- BUTTON --- */
        .btn-submit {
            width: 100%; padding: 15px; 
            background: linear-gradient(to right, var(--primary), var(--primary-hover));
            color: white; border: none; border-radius: 14px; font-size: 1rem;
            font-weight: 600; cursor: pointer; transition: all 0.3s ease; 
            margin-top: 10px;
            box-shadow: 0 4px 12px rgba(74, 144, 226, 0.3);
        }
        .btn-submit:hover { 
            transform: translateY(-2px); 
            box-shadow: 0 6px 16px rgba(74, 144, 226, 0.4); 
        }
        .btn-submit:active { transform: translateY(0); }

        /* --- FOOTER LINK --- */
        .register-text { text-align: center; margin-top: 25px; font-size: 0.9rem; color: var(--text-gray); }
        .register-text a { color: var(--primary); text-decoration: none; font-weight: 600; transition: 0.2s;}
        .register-text a:hover { color: var(--primary-hover); text-decoration: underline; }

        /* Responsive HP */
        @media (max-width: 768px) {
            .container { height: auto; flex-direction: column; max-width: 480px; margin: 20px; border-radius: 20px;}
            .left-panel { display: none; }
            .right-panel { padding: 40px 25px; }
            .mobile-logo { display: block !important; height: 100px; margin: 0 auto 15px; object-fit: contain; }
        }
        .mobile-logo { display: none; }
    </style>
</head>
<body>

    <div class="container">
        <div class="left-panel">
            <div class="circle-bg c1"></div>
            <div class="circle-bg c2"></div>
            <div class="content-overlay">
                
                <img src="{{ asset('asset/Growpath.png') }}" alt="Logo Growpath" class="left-logo">
                
                <p>Platform SPK berbasis data untuk membantu Siswa dan Orang Tua menentukan jurusan kuliah yang tepat dan terarah.</p>
            </div>
        </div>

        <div class="right-panel">
            <div class="login-wrapper">
                
                <div class="header">
                    <img src="{{ asset('asset/Growpath.png') }}" alt="Logo Growpath" class="mobile-logo">
                    <h2>Selamat Datang</h2>
                    <p>Silakan pilih peran untuk masuk.</p>
                </div>

                <div class="role-switch">
                    <button type="button" class="role-btn active" id="btnSiswa" onclick="switchRole('siswa')">Siswa</button>
                    <button type="button" class="role-btn" id="btnOrtu" onclick="switchRole('ortu')">Orang Tua</button>
                </div>

                <form id="loginForm">
                    <div class="input-group">
                        <label id="inputLabel" for="loginInput">Alamat Email</label>
                        <div class="input-field">
                            <input type="text" id="loginInput" placeholder="Masukkan email Anda" required>
                            <i class="ph ph-user icon"></i>
                        </div>
                    </div>

                    <div class="input-group">
                        <label for="password">Password</label>
                        <div class="input-field">
                            <input type="password" id="password" placeholder="Masukkan password Anda" required>
                            <i class="ph ph-lock-key icon"></i>
                        </div>
                    </div>

                    <button type="submit" class="btn-submit" id="submitBtn">Masuk Sebagai Siswa</button>
                </form>

                <div class="register-text">
                    Belum punya akun? <a href="{{ url('/register') }}">Daftar sekarang</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentRole = 'siswa';

        function switchRole(role) {
            currentRole = role;
            const inputLabel = document.getElementById('inputLabel');
            const inputField = document.getElementById('loginInput');
            const submitBtn = document.getElementById('submitBtn');

            document.querySelectorAll('.role-btn').forEach(btn => btn.classList.remove('active'));

            if (role === 'siswa') {
                document.getElementById('btnSiswa').classList.add('active');
                inputLabel.innerText = "Alamat Email";
                inputField.placeholder = "Masukkan email Anda";
                submitBtn.innerText = "Masuk Sebagai Siswa";
            } else {
                document.getElementById('btnOrtu').classList.add('active');
                inputLabel.innerText = "Alamat Email";
                inputField.placeholder = "Masukkan email Anda";
                submitBtn.innerText = "Masuk Sebagai Orang Tua";
            }
        }

        document.getElementById("loginForm").addEventListener("submit", async function(e) {
            e.preventDefault();
            
            const loginInput = document.getElementById("loginInput").value;
            const password = document.getElementById("password").value;
            const submitBtn = document.getElementById("submitBtn");

            const originalText = submitBtn.innerText;
            submitBtn.innerHTML = '<i class="ph ph-spinner ph-spin"></i> Memproses...';
            submitBtn.disabled = true;
            
            try {
                await new Promise(resolve => setTimeout(resolve, 1000));

                const response = await fetch('http://localhost:3000/api/login', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ loginInput, password })
                });

                const data = await response.json();

                if (data.success) {
                    if(data.user.role !== currentRole) {
                        alert("⚠️ Akun ditemukan, tapi peran salah. Silakan pindah tab (Siswa/Orang Tua).");
                        submitBtn.innerText = "Coba Lagi";
                        submitBtn.disabled = false;
                        return;
                    }

                    localStorage.setItem('user', JSON.stringify(data.user));
                    
                    if (data.user.role === 'siswa') {
                        // Routing Laravel
                        window.location.href = "{{ url('/dashboard') }}";
                    } else {
                        window.location.href = "{{ url('/dashboard-ortu') }}";
                    }
                } else {
                    alert("❌ Gagal: " + data.message);
                    submitBtn.innerText = originalText;
                    submitBtn.disabled = false;
                }
            } catch (error) {
                console.log("Offline mode: Melakukan bypass login karena tidak ada server backend.");
                
                let dummyUser = {};
                if (currentRole === 'siswa') {
                    dummyUser = { id: 'siswa-01', name: 'Rizky (Siswa)', role: 'siswa', kelas: '12' };
                    localStorage.setItem('user', JSON.stringify(dummyUser));
                    // Routing Laravel
                    window.location.href = "{{ url('/dashboard') }}";
                } else {
                    dummyUser = { id: 'ortu-01', name: 'Bapak/Ibu', role: 'orang tua' };
                    localStorage.setItem('user', JSON.stringify(dummyUser));
                    // Routing Laravel
                    window.location.href = "{{ url('/dashboard-ortu') }}";
                }
            }
        });
    </script>
</body>
</html>