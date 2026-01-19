<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Processing Login...</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="{{ asset('frontend/shared/utils.js') }}"></script>
</head>

<body class="bg-slate-50 min-h-screen flex items-center justify-center">
  <div class="bg-white p-8 rounded-lg shadow-md max-w-md w-full text-center">
    <div class="mb-4 flex justify-center">
      <svg class="animate-spin h-10 w-10 text-emerald-500" xmlns="http://www.w3.org/2000/svg" fill="none"
        viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor"
          d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
        </path>
      </svg>
    </div>
    <h2 class="text-xl font-semibold text-slate-800 mb-2">Memeriksa Akun</h2>
    <p class="text-slate-500 text-sm" id="status-message">Mohon tunggu, sedang memverifikasi data...</p>
    <div id="error-container" class="hidden mt-4 p-3 bg-red-50 text-red-600 rounded text-sm text-center">
      <p class="font-bold">Login Gagal</p>
      <p id="error-message">Login gagal. Akun Anda belum terdaftar. Silakan daftar terlebih dahulu atau hubungi tim support.</p>
      <p class="text-xs mt-2 text-slate-500">Anda akan dikembalikan ke login dalam <span id="countdown">5</span> detik...</p>
    </div>
  </div>

  <script>
    const API_BASE_URL = '{{ url('/') }}';
    const API_URL = `${API_BASE_URL}/api`;

    function apiUrl(endpoint) {
      const cleanEndpoint = endpoint.startsWith('/') ? endpoint.slice(1) : endpoint;
      return `${API_URL}/${cleanEndpoint}`;
    }

    document.addEventListener('DOMContentLoaded', async () => {
      const urlParams = new URLSearchParams(window.location.search);
      const code = urlParams.get('code');
      const statusEl = document.getElementById('status-message');
      const errorContainer = document.getElementById('error-container');
      const errorMsg = document.getElementById('error-message');

      if (!code) {
        statusEl.textContent = 'Kode otentikasi tidak ditemukan.';
        errorMsg.textContent = 'Tidak ada kode otentikasi dari Google.';
        errorContainer.classList.remove('hidden');
        return;
      }

      try {
        statusEl.textContent = 'Menghubungkan ke Google...';

        // 1. Exchange code for user info
        const response = await fetch(apiUrl(`/auth/google/callback?code=${code}`));
        const result = await response.json();

        const userEmail = result.user?.email || result.email;
        const userName = result.user?.name || result.name;

        if (!userEmail) {
          throw new Error('Gagal mendapatkan email dari Google.');
        }

        console.log('Google Email:', userEmail);
        statusEl.textContent = 'Mengecek status pendaftaran...';

        // Helper function for redirection
        function redirectUser(user, role, type = null) {
          saveUser({
            id: user.id,
            name: user.nama_pic || user.nama || userName,
            email: user.email,
            role: role,
            agentCode: user.agent_code,
            token: result.token
          });

          if (role === 'agent') {
            window.location.href = `{{ url('/') }}/agent/dashboard?id=${user.id}`;
          } else {
            // For freelance/affiliate
            window.location.href = `{{ url('/') }}/freelance/dashboard?id=${user.id}&type=${type || role}`;
          }
        }

        // 2. Check if user is registered (backend already checked)
        if (result.is_registered && result.user) {
          console.log('User found:', result.user);
          const role = result.role || 'agent';
          redirectUser(result.user, role, role);
          return;
        }

        // 3. Not Found -> Signup or Error
        console.log('User not found in any list.');
        const intent = sessionStorage.getItem('auth_intent');

        if (intent === 'login') {
          throw new Error('Login gagal. Akun Anda belum terdaftar. Silakan daftar terlebih dahulu atau hubungi tim support.');
        } else {
          window.location.href = `{{ url('/signup') }}?email=${encodeURIComponent(userEmail)}`;
        }

      } catch (error) {
        console.error('Callback Error:', error);
        statusEl.textContent = '';
        errorMsg.textContent = error.message || 'Akun Anda belum terdaftar. Silakan daftar terlebih dahulu atau hubungi tim support.';
        errorContainer.classList.remove('hidden');

        // Auto-redirect to login after 5 seconds
        let countdown = 5;
        const countdownEl = document.getElementById('countdown');
        const timer = setInterval(() => {
          countdown--;
          if (countdownEl) countdownEl.textContent = countdown;
          if (countdown <= 0) {
            clearInterval(timer);
            window.location.href = '{{ url('/login') }}';
          }
        }, 1000);
      }
    });
  </script>
</body>

</html>
