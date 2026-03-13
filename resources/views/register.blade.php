<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun Baru - Growpath</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <style>
        :root {
            /* Palette disamakan dengan login.html */
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
            
            /* Warna Error khusus Register */
            --error-red: #d93025; 
            --error-bg: #fce8e6;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }

        body {
            background-color: var(--bg-body);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px 20px;
        }

        .container {
            background: var(--white);
            width: 100%;
            max-width: 1000px;
            display: flex;
            border-radius: 24px;
            box-shadow: var(--shadow-lg);
            overflow: hidden;
            min-height: 650px;
        }

        /* --- PANEL KIRI (Visual Biru Gelap ke Terang) --- */
        .left-panel {
            flex: 1.1;
            background: linear-gradient(145deg, #1E3A8A 0%, var(--primary) 100%);
            padding: 60px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            color: var(--white);
            position: relative;
            overflow: hidden;
        }

        .circle { 
            position: absolute; 
            border-radius: 50%; 
            background: linear-gradient(180deg, rgba(255,255,255,0.15) 0%, rgba(255,255,255,0) 100%);
            backdrop-filter: blur(5px);
        }
        .c1 { width: 350px; height: 350px; top: -100px; right: -100px; }
        .c2 { width: 200px; height: 200px; bottom: -50px; left: -80px; }

        .content-overlay { position: relative; z-index: 2; }
        
        /* Style untuk Logo di Panel Kiri */
        .content-overlay .left-logo {
            max-width: 300px; /* Ukuran proporsional */
            height: auto;
            
            /* Efek ini mengubah logo berwarna gelap menjadi putih agar terlihat jelas di background biru */
            filter: brightness(0) invert(1); 
            text-align: center;
            margin: 0 auto; display: block;
        }

        /* Sesuai instruksi gambar: Teks warna Grey */
        .content-overlay p { 
            font-size: 1.05rem; 
            line-height: 1.7; 
            color: #d1d5db; /* Warna Abu-abu (Grey) terang */
            font-weight: 300; 
        }

        /* --- PANEL KANAN (Form) --- */
        .right-panel {
            flex: 1;
            padding: 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .header { margin-bottom: 30px; text-align: center; }
        .header h2 { color: var(--text-dark); font-size: 1.8rem; font-weight: 700; margin-bottom: 5px; letter-spacing: -0.5px;}
        .header p { color: var(--text-gray); font-size: 0.9rem;}

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
            text-align: center;
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
        .form-group { margin-bottom: 20px; }
        .form-label { 
            display: block; color: var(--text-dark); font-size: 0.85rem; 
            margin-bottom: 8px; font-weight: 600; 
        }
        
        .input-box {
            position: relative;
            width: 100%;
        }

        .input-box i {
            position: absolute; left: 16px; top: 50%; transform: translateY(-50%);
            color: var(--text-light); font-size: 1.25rem; transition: 0.3s;
        }

        input, select {
            width: 100%; padding: 14px 15px 14px 45px;
            background-color: var(--bg-input);
            border: 1px solid var(--border-color);
            border-radius: 14px; font-size: 0.95rem;
            transition: all 0.3s ease; outline: none;
            color: var(--text-dark); appearance: none;
        }

        input:hover, select:hover { border-color: #d1d5db; }
        input:focus, select:focus { 
            background-color: var(--white);
            border-color: var(--primary); 
            box-shadow: 0 0 0 4px rgba(74, 144, 226, 0.15); 
        }
        input:focus + i, select:focus + i { color: var(--primary); }


        /* --- STYLE ERROR (GOOGLE STYLE) --- */
        input.input-error { border-color: var(--error-red) !important; background-color: var(--error-bg) !important;}
        input.input-error:focus { box-shadow: 0 0 0 4px rgba(217, 48, 37, 0.15) !important; background-color: var(--white) !important;}
        input.input-error + i { color: var(--error-red) !important; }

        .google-error-msg {
            display: none;
            align-items: center; gap: 8px; color: var(--error-red);
            font-size: 0.85rem; margin-top: 8px; font-weight: 500;
            animation: slideDown 0.2s ease-out;
        }
        @keyframes slideDown { from { opacity: 0; transform: translateY(-5px); } to { opacity: 1; transform: translateY(0); } }

        .helper { font-size: 0.75rem; color: var(--text-gray); margin-top: 6px; display: block; margin-left: 2px; }

        /* --- BUTTON --- */
        .btn-submit {
            width: 100%; padding: 15px; 
            background: linear-gradient(to right, var(--primary), var(--primary-hover));
            color: white; border: none; border-radius: 14px; font-size: 1rem;
            font-weight: 600; cursor: pointer; transition: all 0.3s ease; 
            margin-top: 15px;
            box-shadow: 0 4px 12px rgba(74, 144, 226, 0.3);
        }
        .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 6px 16px rgba(74, 144, 226, 0.4); }
        .btn-submit:active { transform: translateY(0); }
        .btn-submit:disabled { background: #ccc; cursor: not-allowed; box-shadow: none; transform: none;}

        /* --- FOOTER LINK --- */
        .footer-link { text-align: center; margin-top: 25px; font-size: 0.9rem; color: var(--text-gray); }
        .footer-link a { color: var(--primary); text-decoration: none; font-weight: 600; transition: 0.2s;}
        .footer-link a:hover { color: var(--primary-hover); text-decoration: underline; }

        .hidden { display: none; }
        .fade-in { animation: fadeIn 0.4s; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(5px); } to { opacity: 1; transform: translateY(0); } }

        /* Responsive HP */
        @media (max-width: 768px) {
            .container { flex-direction: column; height: auto; max-width: 480px; margin: 20px; border-radius: 20px; }
            .left-panel { display: none; } /* Sembunyikan panel biru di HP */
            .right-panel { padding: 40px 25px; }
            .mobile-logo { display: block !important; height: 100px; margin: 0 auto 15px; object-fit: contain; }
        }
        .mobile-logo { display: none; }
    </style>
</head>
<body>

    <div class="container">
        
        <div class="left-panel">
            <div class="circle c1"></div>
            <div class="circle c2"></div>
            <div class="content-overlay">
                <img src="{{ asset('asset/Growpath.png') }}" alt="Logo Growpath" class="left-logo">
                
                <p>Platform SPK berbasis data untuk membantu Siswa dan Orang Tua menentukan jurusan kuliah yang tepat dan terarah.</p>
            </div>
        </div>

        <div class="right-panel">
            <div class="header">
                <img src="{{ asset('asset/Growpath.png') }}" alt="Logo Growpath" class="mobile-logo">
                <h2>Buat Akun Baru</h2>
                <p>Silakan lengkapi data untuk mendaftar.</p>
            </div>

            <div class="role-switch">
                <div class="role-btn active" id="tabSiswa" onclick="switchRole('siswa')">Siswa</div>
                <div class="role-btn" id="tabOrtu" onclick="switchRole('ortu')">Orang Tua</div>
            </div>

            <form id="registerForm">
                
                <div id="sectionOrtu" class="hidden fade-in">
                    <div class="form-group">
                        <label class="form-label">User ID Anak</label>
                        <div class="input-box">
                            <input type="text" id="childID" placeholder="Masukkan User ID Anak">
                            <i class="ph ph-identification-card"></i>
                        </div>
                        
                        <div id="errorText" class="google-error-msg">
                            <i class="ph-fill ph-warning-circle" style="font-size: 1.2rem;"></i>
                            <span>Sistem tidak dapat menemukan User ID Anak.</span>
                        </div>

                        <span class="helper" id="helperText">*Wajib diisi agar akun terhubung.</span>
                    </div>
                </div>

                <div class="form-group" id="nameGroup">
                    <label class="form-label">Nama Lengkap</label>
                    <div class="input-box">
                        <input type="text" id="name" placeholder="Masukkan nama lengkap Anda" required>
                        <i class="ph ph-user"></i> 
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Alamat Email</label>
                    <div class="input-box">
                        <input type="email" id="email" placeholder="Masukkan alamat email aktif" required>
                        <i class="ph ph-envelope-simple"></i>
                    </div>
                </div>

                <div id="sectionSiswa" class="fade-in">
                    <div class="form-group">
                        <label class="form-label">User ID</label>
                        <div class="input-box">
                            <input type="text" id="customUserCode" placeholder="Buat User ID unik Anda">
                            <i class="ph ph-identification-card"></i>
                        </div>
                        <span class="helper">*ID ini untuk diberikan ke Orang Tua Anda nanti.</span>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Kelas</label>
                        <div class="input-box">
                            <select id="kelas">
                                <option value="10">Kelas 10</option>
                                <option value="11">Kelas 11</option>
                                <option value="12">Kelas 12</option>
                            </select>
                            <i class="ph ph-graduation-cap"></i>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Password</label>
                    <div class="input-box">
                        <input type="password" id="password" placeholder="Buat password (min. 6 karakter)" required>
                        <i class="ph ph-lock-key"></i>
                    </div>
                </div>

                <button type="submit" class="btn-submit" id="btnSubmit">Daftar Sekarang</button>
            </form>

            <div class="footer-link">
                Sudah punya akun? <a href="{{ url('/login') }}">Masuk di sini</a>
            </div>
        </div>
    </div>

    <script>
        let currentRole = 'siswa';

        // Fungsi Bersihkan Error saat user mengetik kembali
        function clearError() {
            const input = document.getElementById('childID');
            const errorMsg = document.getElementById('errorText');
            
            // Hapus kelas merah pada input
            input.classList.remove('input-error');
            // Sembunyikan pesan error
            errorMsg.style.display = 'none';
        }
        document.getElementById('childID').addEventListener('input', clearError);

        function switchRole(role) {
            currentRole = role;
            const tabSiswa = document.getElementById('tabSiswa');
            const tabOrtu = document.getElementById('tabOrtu');
            
            const sectionSiswa = document.getElementById('sectionSiswa');
            const sectionOrtu = document.getElementById('sectionOrtu');
            const nameGroup = document.getElementById('nameGroup');

            clearError(); // Reset error jika ganti tab

            if (role === 'siswa') {
                tabSiswa.classList.add('active');
                tabOrtu.classList.remove('active');
                
                sectionSiswa.classList.remove('hidden');
                nameGroup.classList.remove('hidden');
                sectionOrtu.classList.add('hidden');
            } else {
                tabSiswa.classList.remove('active');
                tabOrtu.classList.add('active');
                
                sectionSiswa.classList.add('hidden');
                nameGroup.classList.add('hidden'); 
                sectionOrtu.classList.remove('hidden');
            }
        }

        document.getElementById('registerForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const btn = document.getElementById('btnSubmit');
            const originalText = btn.innerText;
            btn.innerHTML = '<i class="ph ph-spinner ph-spin"></i> Memproses...';
            btn.disabled = true;

            // Validasi Sederhana Siswa
            if (currentRole === 'siswa' && !document.getElementById('customUserCode').value) {
                alert("Mohon isi User ID Anda!");
                btn.disabled = false; btn.innerText = originalText;
                return;
            }
            
            // --- LOGIKA SIMULASI VALIDASI ID (GOOGLE STYLE) ---
            if (currentRole === 'ortu') {
                const childIdInput = document.getElementById('childID');
                const childIdValue = childIdInput.value;
                const errorText = document.getElementById('errorText');

                // ID Valid Simulasi: "SISWA123"
                const dummyValidID = "SISWA123"; 

                if (childIdValue !== dummyValidID) {
                    childIdInput.classList.add('input-error');
                    errorText.style.display = 'flex';
                    btn.disabled = false; 
                    btn.innerText = originalText;
                    childIdInput.focus();
                    return; 
                }
            }

            // Simulasi Sukses (Karena bypass DB)
            setTimeout(() => {
                if(currentRole === 'ortu') {
                    alert("✅ User ID Ditemukan! Akun Orang Tua berhasil dibuat.");
                } else {
                    alert("✅ Pendaftaran Siswa Berhasil.");
                }
                // Routing Laravel
                window.location.href = "{{ url('/login') }}";
            }, 1000);
        });
    </script>

</body>
</html>