<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tes Minat Bakat - Growpath</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/exam.js'])
</head>
<body class="bg-[#F4F7F6] font-sans flex justify-center items-center min-h-screen p-5 text-[#333]">

    <div class="bg-white w-full max-w-[900px] p-6 md:p-10 rounded-[20px] shadow-[0_10px_40px_rgba(0,0,0,0.08)] relative overflow-hidden">
        
        <div class="mb-8">
            <div class="w-full h-3 bg-gray-100 rounded-full overflow-hidden mb-4">
                <div id="progressBar" class="h-full bg-[#4A90E2] w-0 transition-all duration-500 ease-out rounded-full"></div>
            </div>
            
            <div class="flex justify-between items-center text-sm font-medium text-gray-500">
                <div class="flex items-center gap-3">
                    <span class="flex items-center gap-2 text-[#4A90E2]">
                        <i class="ph-fill ph-brain text-lg"></i> Tes Minat Bakat (RIASEC)
                    </span>
                    <span id="examTimer" class="bg-red-50 text-red-500 px-3 py-1 rounded-full text-xs font-bold flex items-center gap-1.5 border border-red-100 shadow-sm">
                        <i class="ph-fill ph-timer"></i> <span id="timeDisplay">00:00</span>
                    </span>
                </div>
                
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
     window.examQuestions = @json($questions ?? []);
     window.isBetaMode = @json(isset($is_beta) && $is_beta);
     window.examDuration = {{ (int)($duration ?? 60) }}; 
     window.examId = {{ $exam->id ?? 0 }};
     window.csrfToken = "{{ csrf_token() }}";
 </script>
</body>
</html>

    <style>
        /* Animasi Transisi Halus */
        .animate-fade-in { animation: fadeIn 0.4s ease-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</body>
</html>