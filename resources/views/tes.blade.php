<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tes Minat Bakat - Growpath</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#F4F7F6] font-sans flex justify-center items-center min-h-screen p-5 text-[#333]">

    <div class="bg-white w-full max-w-[900px] p-6 md:p-10 rounded-[20px] shadow-[0_10px_40px_rgba(0,0,0,0.08)] relative overflow-hidden">
        
        <div class="mb-8">
            <div class="w-full h-3 bg-gray-100 rounded-full overflow-hidden mb-4">
                <div id="progressBar" class="h-full bg-[#4A90E2] w-0 transition-all duration-500 ease-out rounded-full"></div>
            </div>
            
            <div class="flex justify-between items-center text-sm font-medium text-gray-500">
                <span class="flex items-center gap-2 text-[#4A90E2]">
                    <i class="ph-fill ph-brain text-lg"></i> Tes Minat Bakat (RIASEC)
                </span>
                <span>Soal <span id="currNum" class="text-gray-800 font-bold">1</span> dari <span id="totalNum"></span></span>
            </div>
        </div>

        <div id="quizArea" class="animate-fade-in">
            <h2 id="qText" class="text-xl md:text-2xl font-bold text-gray-800 mb-2 leading-relaxed">
                Sedang memuat pertanyaan...
            </h2>
            <p class="text-gray-400 text-sm italic mb-8">
                *Pilih semua opsi yang sesuai dengan diri Anda (Boleh lebih dari satu).
            </p>
            
            <div id="optionsContainer" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                </div>
        </div>

        <div class="mt-10 pt-6 border-t border-gray-100 flex justify-between items-center">
            <button id="btnPrev" class="px-6 py-3 rounded-xl font-semibold text-gray-400 hover:text-gray-600 hover:bg-gray-50 transition-all flex items-center gap-2 disabled:opacity-0 disabled:cursor-default">
                <i class="ph-bold ph-arrow-left"></i> Kembali
            </button>
            
            <button id="btnNext" class="px-8 py-3 bg-[#4A90E2] hover:bg-[#357ABD] text-white rounded-xl font-semibold shadow-lg shadow-[#4A90E2]/30 transition-all transform hover:-translate-y-1 flex items-center gap-2">
                Selanjutnya <i class="ph-bold ph-arrow-right"></i>
            </button>
        </div>

    </div>

    <script>
       
        const questions = @json($questions);
        // 2. State Aplikasi
        let currentIndex = 0;
        let userAnswers = []; // Array of arrays (karena multi-select)

        // 3. Inisialisasi
        function initQuiz() {
            // Siapkan array kosong untuk setiap soal
            userAnswers = new Array(questions.length).fill(null).map(() => []);
            renderQuestion();
        }

        // 4. Render Tampilan
        function renderQuestion() {
            const q = questions[currentIndex];
            
            // Update Teks
            document.getElementById('qText').innerText = q.question_text;
            document.getElementById('currNum').innerText = currentIndex + 1;
            document.getElementById('totalNum').innerText = questions.length;
            
            // Update Progress Bar
            const percent = ((currentIndex + 1) / questions.length) * 100;
            document.getElementById('progressBar').style.width = percent + "%";

            // Mapping Opsi ke Kode RIASEC
            const optionsMap = [
                { code: 'R', text: q.opt_r }, { code: 'I', text: q.opt_i },
                { code: 'A', text: q.opt_a }, { code: 'S', text: q.opt_s },
                { code: 'E', text: q.opt_e }, { code: 'C', text: q.opt_c }
            ];

            const container = document.getElementById('optionsContainer');
            container.innerHTML = ""; // Reset opsi

            optionsMap.forEach(opt => {
                // Cek apakah opsi ini sudah dipilih sebelumnya
                const isSelected = userAnswers[currentIndex].includes(opt.code);
                
                // Styling Tailwind Dynamic
                const baseClass = "p-5 border-2 rounded-2xl cursor-pointer transition-all flex items-center justify-center text-center font-medium min-h-[80px] relative group";
                const activeClass = isSelected 
                    ? "bg-[#4A90E2] border-[#4A90E2] text-white shadow-md transform -translate-y-1" 
                    : "bg-white border-gray-100 text-gray-600 hover:border-[#4A90E2] hover:bg-blue-50";

                const btn = document.createElement('div');
                btn.className = `${baseClass} ${activeClass}`;
                btn.innerText = opt.text;

                // Checkmark icon (jika dipilih)
                if(isSelected) {
                    const badge = document.createElement('div');
                    badge.className = "absolute -top-2 -right-2 bg-white text-[#4A90E2] w-6 h-6 rounded-full flex items-center justify-center border-2 border-[#4A90E2] shadow-sm";
                    badge.innerHTML = '<i class="ph-bold ph-check text-xs"></i>';
                    btn.appendChild(badge);
                }

                // Event Listener
                btn.onclick = () => toggleAnswer(opt.code);
                container.appendChild(btn);
            });

            // Update Tombol Navigasi
            const prevBtn = document.getElementById('btnPrev');
            const nextBtn = document.getElementById('btnNext');

            prevBtn.disabled = (currentIndex === 0);

            if(currentIndex === questions.length - 1) {
                nextBtn.innerHTML = 'Selesai & Kirim <i class="ph-bold ph-paper-plane-right"></i>';
                nextBtn.className = "px-8 py-3 bg-[#2ECC71] hover:bg-[#27ae60] text-white rounded-xl font-semibold shadow-lg shadow-green-500/30 transition-all transform hover:-translate-y-1 flex items-center gap-2";
            } else {
                nextBtn.innerHTML = 'Selanjutnya <i class="ph-bold ph-arrow-right"></i>';
                nextBtn.className = "px-8 py-3 bg-[#4A90E2] hover:bg-[#357ABD] text-white rounded-xl font-semibold shadow-lg shadow-[#4A90E2]/30 transition-all transform hover:-translate-y-1 flex items-center gap-2";
            }
        }

        // 5. Logic Toggle (Multi Select)
        function toggleAnswer(code) {
            const currentSelected = userAnswers[currentIndex];
            
            if (currentSelected.includes(code)) {
                // Unselect
                userAnswers[currentIndex] = currentSelected.filter(c => c !== code);
            } else {
                // Select
                userAnswers[currentIndex].push(code);
            }
            renderQuestion(); // Re-render untuk update UI
        }

        // 6. Navigasi Next
        document.getElementById('btnNext').addEventListener('click', () => {
            // Validasi: Wajib pilih minimal 1
            if(userAnswers[currentIndex].length === 0) {
                alert("Mohon pilih setidaknya satu jawaban yang sesuai dengan Anda.");
                return;
            }

            if(currentIndex < questions.length - 1) {
                currentIndex++;
                renderQuestion();
            } else {
                submitTestResult();
            }
        });

        // 7. Navigasi Prev
        document.getElementById('btnPrev').addEventListener('click', () => {
            if(currentIndex > 0) {
                currentIndex--;
                renderQuestion();
            }
        });

        // 8. Submit & Hitung Skor
        function submitTestResult() {
            const btn = document.getElementById('btnNext');
            btn.innerHTML = '<i class="ph-bold ph-spinner animate-spin"></i> Menyimpan...';
            btn.disabled = true;

            // Hitung Skor RIASEC
            let scores = { R:0, I:0, A:0, S:0, E:0, C:0 };
            
            userAnswers.forEach(answers => {
                answers.forEach(code => {
                    if(scores[code] !== undefined) scores[code] += 1;
                });
            });

            // Cari Kode Dominan (Top 3)
            let sortedCodes = Object.entries(scores)
                .sort((a, b) => b[1] - a[1]) // Urutkan dari terbesar
                .slice(0, 3)                 // Ambil 3 teratas
                .map(item => item[0]);       // Ambil kodenya saja (misal "S")
            
            const dominantCode = sortedCodes.join(""); // Misal "SEC"

            // Simulasi Kirim ke Backend (Nanti diganti AJAX/Fetch sesungguhnya)
            console.log("Skor Akhir:", scores);
            console.log("Kode Dominan:", dominantCode);

          setTimeout(() => {
                alert(`✅ Tes Selesai!\n\nKode Dominan Anda: ${dominantCode}`);
                
                // Gunakan Blade directive untuk membedakan Beta Test vs Ujian Asli
                @if(isset($is_beta) && $is_beta)
                    alert("Mode Beta selesai. Tab pratinjau ini akan ditutup.");
                    window.close(); // Otomatis menutup tab browser
                @else
                    // Jika nanti rute laporan sudah ada, ubah tulisan "/dashboard" di bawah ini
                    // menjadi: "{{ route('nama.rute.laporan') }}"
                    window.location.href = "/dashboard"; 
                @endif

            }, 1500);
        }

        // Mulai Aplikasi
        initQuiz();

    </script>

    <style>
        /* Animasi Transisi Halus */
        .animate-fade-in { animation: fadeIn 0.4s ease-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</body>
</html>