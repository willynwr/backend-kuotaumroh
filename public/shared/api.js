/**
 * API Service Layer
 * Kuotaumroh.id Agent Portal
 */

/* ===========================
   Configuration
   =========================== */

// Import konfigurasi dari config.js
// Pastikan config.js sudah di-load sebelum file ini
const API_BASE = typeof API_URL !== 'undefined' ? API_URL : 'https://kuotaumroh.id/api';

// Mock mode for development (set to false when API is ready)
// Jangan deklarasi ulang jika sudah ada di config.js
if (typeof USE_MOCK_DATA === 'undefined') {
  var USE_MOCK_DATA = false;
}

/* ===========================
   API Helper Functions
   =========================== */

/**
 * Generic fetch wrapper with error handling
 * @param {string} endpoint - API endpoint
 * @param {Object} options - Fetch options
 * @returns {Promise} Response data
 */
async function apiFetch(endpoint, options = {}) {
  const url = `${API_BASE}${endpoint}`;

  const defaultOptions = {
    headers: {
      'Content-Type': 'application/json',
      // Add authentication token if available
      ...(getAuthToken() && { 'Authorization': `Bearer ${getAuthToken()}` }),
    },
  };

  const config = { ...defaultOptions, ...options };

  try {
    const response = await fetch(url, config);

    if (!response.ok) {
      throw new Error(`API Error: ${response.status} ${response.statusText}`);
    }

    return await response.json();
  } catch (error) {
    console.error('API Fetch Error:', error);
    throw error;
  }
}

/**
 * Get authentication token from localStorage
 * @returns {string|null} Auth token
 */
function getAuthToken() {
  try {
    const user = JSON.parse(localStorage.getItem('user') || '{}');
    return user.token || null;
  } catch {
    return null;
  }
}

/* ===========================
   Authentication API
   =========================== */

/**
 * Get Google Auth URL
 * @returns {Promise<Object>} Object containing url
 */
async function getGoogleAuthUrl() {
  if (USE_MOCK_DATA) {
    return Promise.resolve({
      url: 'callback.html?code=mock_google_code_123'
    });
  }
  return apiFetch('/auth/google/url');
}

/**
 * Handle Google Callback
 * @param {string} code - Google Auth Code
 * @returns {Promise<Object>} User data and token
 */
async function handleGoogleCallback(code) {
  if (USE_MOCK_DATA) {
    return new Promise((resolve) => {
      setTimeout(() => {
        resolve({
          success: true,
          user: {
            name: 'Mock Google User',
            email: 'mock.google@gmail.com',
            token: 'mock-token-xyz'
          },
          is_registered: true // Toggle this to test signup flow
        });
      }, 1000);
    });
  }
  return apiFetch(`/auth/google/callback?code=${code}`);
}

/**
 * Login user
 * @param {string} email - User email
 * @param {string} password - User password
 * @returns {Promise<Object>} User data with token
 */
async function login(email, password) {
  if (USE_MOCK_DATA) {
    // Mock login for development
    return new Promise((resolve) => {
      setTimeout(() => {
        resolve({
          success: true,
          user: {
            name: 'Ahmad Fauzi',
            email: email,
            agentCode: 'AGN-2024-001',
            token: 'mock-jwt-token',
          },
        });
      }, 500);
    });
  }

  return apiFetch('/auth/login', {
    method: 'POST',
    body: JSON.stringify({ email, password }),
  });
}

/**
 * Logout user
 * @returns {Promise<Object>} Logout confirmation
 */
async function logout() {
  if (USE_MOCK_DATA) {
    return Promise.resolve({ success: true });
  }

  return apiFetch('/auth/logout', { method: 'POST' });
}

/* ===========================
   Package API
   =========================== */

/**
 * Fetch available packages by provider
 * @param {string} provider - Provider name (optional)
 * @returns {Promise<Array>} Array of packages
 */
async function fetchPackages(provider = null) {
  try {
    // Fetch from real API
    const response = await fetch('https://kuotaumroh.id/api/umroh/package?ref_code=bulk_umroh');
    if (!response.ok) {
      throw new Error('Failed to fetch packages');
    }

    const data = await response.json();

    // Map API response to app format
    const packages = data
      .filter(pkg => pkg.is_active === '1') // Only include active packages
      .filter(pkg => pkg.price_bulk && pkg.price_customer) // Only include packages with prices
      .map(pkg => {
        return {
          id: pkg.id,
          name: pkg.name,
          provider: pkg.type, // e.g., "TELKOMSEL", "INDOSAT", "XL", "AXIS"
          subType: pkg.sub_type, // e.g., "INTERNET", "INTERNET + TELP/SMS"
          price: parseInt(pkg.price_bulk), // Agent wholesale price
          sellPrice: parseInt(pkg.price_customer), // Public retail price
          feeAffiliate: pkg.fee_affiliate ? parseInt(pkg.fee_affiliate) : 0,
          quota: pkg.quota || 'Unlimited',
          validity: `${pkg.days} hari`,
          days: parseInt(pkg.days),
          telp: pkg.telp,
          sms: pkg.sms,
          bonus: pkg.bonus,
          description: pkg.bonus ? `${pkg.quota} + ${pkg.bonus}` : pkg.quota,
          promo: pkg.promo || null,
        };
      });

    // Filter by provider if specified
    if (provider) {
      return packages.filter(pkg => pkg.provider === provider);
    }

    return packages;
  } catch (error) {
    console.error('Error fetching packages:', error);

    // Fallback to mock data if API fails
    return new Promise((resolve) => {
      setTimeout(() => {
        resolve([
          {
            id: 'pkg-1',
            name: 'Paket Umroh 5GB',
            provider: 'SIMPATI',
            price: 50000,
            sellPrice: 65000,
            quota: '5GB',
            validity: '30 hari',
          },
          {
            id: 'pkg-2',
            name: 'Paket Umroh 10GB',
            provider: 'SIMPATI',
            price: 85000,
            sellPrice: 110000,
            quota: '10GB',
            validity: '30 hari',
          },
        ]);
      }, 500);
    });
  }
}

/* ===========================
   Order API
   =========================== */

/**
 * Create new order
 * @param {Object} orderData - Order data
 * @returns {Promise<Object>} Created order
 */
async function createOrder(orderData) {
  if (USE_MOCK_DATA) {
    return new Promise((resolve) => {
      setTimeout(() => {
        resolve({
          success: true,
          orderId: 'ORD-' + Date.now(),
          ...orderData,
        });
      }, 1000);
    });
  }

  return apiFetch('/orders', {
    method: 'POST',
    body: JSON.stringify(orderData),
  });
}

/**
 * Fetch order history
 * @param {Object} filters - Filter parameters
 * @returns {Promise<Array>} Array of orders
 */
async function fetchOrders(filters = {}) {
  if (USE_MOCK_DATA) {
    return new Promise((resolve) => {
      setTimeout(() => {
        resolve([
          {
            id: 'ORD-001',
            date: new Date('2024-02-15'),
            items: 25,
            total: 1250000,
            status: 'completed',
          },
          {
            id: 'ORD-002',
            date: new Date('2024-02-14'),
            items: 18,
            total: 900000,
            status: 'pending',
          },
        ]);
      }, 500);
    });
  }

  const queryString = new URLSearchParams(filters).toString();
  return apiFetch(`/orders?${queryString}`);
}

/* ===========================
   Wallet API
   =========================== */

/**
 * Fetch wallet balance
 * @returns {Promise<Object>} Wallet data
 */
async function fetchWalletBalance() {
  if (USE_MOCK_DATA) {
    return Promise.resolve({
      balance: 3250000,
      pendingWithdrawal: 500000,
    });
  }

  return apiFetch('/wallet/balance');
}

/**
 * Request withdrawal
 * @param {Object} withdrawalData - Withdrawal data
 * @returns {Promise<Object>} Withdrawal confirmation
 */
async function requestWithdrawal(withdrawalData) {
  if (USE_MOCK_DATA) {
    return new Promise((resolve) => {
      setTimeout(() => {
        resolve({
          success: true,
          withdrawalId: 'WD-' + Date.now(),
          ...withdrawalData,
        });
      }, 1000);
    });
  }

  return apiFetch('/wallet/withdraw', {
    method: 'POST',
    body: JSON.stringify(withdrawalData),
  });
}

/* ===========================
   Referral API
   =========================== */

/**
 * Fetch referral data
 * @returns {Promise<Object>} Referral data
 */
async function fetchReferralData() {
  if (USE_MOCK_DATA) {
    return Promise.resolve({
      agentCode: 'AGN-2024-001',
      referralLink: 'https://kuotaumroh.id/ref/AGN-2024-001',
      totalReferrals: 12,
      activeReferrals: 8,
      totalCommission: 2450000,
      pendingCommission: 350000,
    });
  }

  return apiFetch('/referrals');
}

/**
 * Fetch referred agents
 * @returns {Promise<Array>} Array of referred agents
 */
async function fetchReferredAgents() {
  if (USE_MOCK_DATA) {
    return Promise.resolve([
      {
        id: '1',
        name: 'Budi Santoso',
        email: 'budi.s@email.com',
        agentCode: 'AGN-2024-015',
        location: 'Surabaya, Jawa Timur',
        joinedAt: new Date('2024-01-10'),
        lastActiveAt: new Date('2024-02-12'),
        totalOrders: 25,
        commission: 450000,
        status: 'active',
      },
    ]);
  }

  return apiFetch('/referrals/agents');
}

/* ===========================
   Dashboard API
   =========================== */

/**
 * Fetch dashboard statistics
 * @returns {Promise<Object>} Dashboard stats
 */
async function fetchDashboardStats() {
  if (USE_MOCK_DATA) {
    return Promise.resolve({
      monthlyProfit: 2450000,
      totalProfit: 15750000,
      walletBalance: 3250000,
      pendingWithdrawal: 500000,
    });
  }

  return apiFetch('/dashboard/stats');
}

/* ===========================
   Backend Integration API
   (For client to implement)
   =========================== */

/**
 * Submit order batch to backend
 * @param {Array} backendPayload - Array of order items with batch info
 * @returns {Promise<Object>} Order submission response
 * 
 * TODO: Client needs to implement this endpoint
 * Endpoint: POST /api/orders/batch
 * Request body format:
 * [
 *   {
 *     batch_id: "BATCH-xxx",
 *     batch_name: "Order 10/01/2026 07:43",
 *     msisdn: "081234567890",
 *     provider: "TELKOMSEL",
 *     package_id: "pkg123",
 *     schedule_date: "2026-01-15T10:30:00.000Z" // or null
 *   },
 *   ...more items
 * ]
 * 
 * Expected response:
 * {
 *   success: true,
 *   batch_id: "BATCH-xxx",
 *   order_count: 10,
 *   message: "Order batch created successfully"
 * }
 */
async function submitOrderBatch(backendPayload) {
  if (USE_MOCK_DATA) {
    // Mock response for development
    return new Promise((resolve) => {
      setTimeout(() => {
        console.log('📤 MOCK: Submitting order batch to backend');
        console.log('Payload:', backendPayload);
        resolve({
          success: true,
          batch_id: backendPayload[0]?.batch_id,
          order_count: backendPayload.length,
          message: 'Order batch created successfully (MOCK)'
        });
      }, 1000);
    });
  }

  // TODO: Replace with your actual API endpoint
  return apiFetch('/api/orders/batch', {
    method: 'POST',
    body: JSON.stringify(backendPayload),
  });
}

/* ===========================
   Umroh Payment API
   =========================== */

/**
 * Get Umroh package catalog
 * @param {string} refCode - Reference code for special pricing (default: bulk_umroh)
 * @returns {Promise<Object>} Catalog response
 * 
 * Endpoint: GET /api/umroh/package?ref_code=bulk_umroh
 */
async function getUmrohPackages(refCode = 'bulk_umroh') {
  try {
    const response = await fetch(`${API_BASE}/umroh/package?ref_code=${refCode}`);
    return await response.json();
  } catch (error) {
    console.error('Error fetching umroh packages:', error);
    throw error;
  }
}

/**
 * Create bulk payment order
 * @param {Object} orderData - Order data
 * @returns {Promise<Object>} Bulk payment response with QR code
 * 
 * Endpoint: POST /api/umroh/bulkpayment
 * Request body format:
 * {
 *   batch_id: "BATCH_1234567",
 *   batch_name: "ORDER_GOLD_TGL12",
 *   payment_method: "QRIS" | "SALDO",
 *   detail: "{date: 2026-01-16T13:00}" | null,
 *   ref_code: "6600000001",
 *   msisdn: ["6281262225575", "6285141302510"],
 *   package_id: ["R1-TSEL-012", "R2-BYU-012"]
 * }
 */
async function createBulkPayment(orderData) {
  try {
    const response = await fetch(`${API_BASE}/umroh/bulkpayment`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      },
      body: JSON.stringify(orderData),
    });
    return await response.json();
  } catch (error) {
    console.error('Error creating bulk payment:', error);
    throw error;
  }
}

/**
 * Get bulk payment history
 * @param {string} agentId - Agent ID / ref_code
 * @returns {Promise<Object>} Payment history
 * 
 * Endpoint: GET /api/umroh/bulkpayment?agent_id=600001
 */
async function getBulkPaymentHistory(agentId) {
  try {
    const response = await fetch(`${API_BASE}/umroh/bulkpayment?agent_id=${agentId}`);
    return await response.json();
  } catch (error) {
    console.error('Error fetching payment history:', error);
    throw error;
  }
}

/**
 * Get bulk payment detail
 * @param {number} paymentId - Payment ID
 * @param {string} agentId - Agent ID / ref_code
 * @returns {Promise<Object>} Payment detail with items
 * 
 * Endpoint: GET /api/umroh/bulkpayment/detail?id=123&agent_id=600001
 */
async function getBulkPaymentDetail(paymentId, agentId) {
  try {
    const response = await fetch(`${API_BASE}/umroh/bulkpayment/detail?id=${paymentId}&agent_id=${agentId}`);
    return await response.json();
  } catch (error) {
    console.error('Error fetching payment detail:', error);
    throw error;
  }
}

/**
 * Get payment status (for polling)
 * @param {number} paymentId - Payment ID
 * @returns {Promise<Object>} Payment status with QRIS
 * 
 * Endpoint: GET /api/umroh/payment/status?id=123
 */
async function getPaymentStatus(paymentId) {
  try {
    const response = await fetch(`${API_BASE}/umroh/payment/status?id=${paymentId}`);
    return await response.json();
  } catch (error) {
    console.error('Error fetching payment status:', error);
    throw error;
  }
}

/**
 * Verify payment manually
 * @param {number} paymentId - Payment ID
 * @returns {Promise<Object>} Verification result
 * 
 * Endpoint: POST /api/umroh/payment/verify
 */
async function verifyPayment(paymentId) {
  try {
    const response = await fetch(`${API_BASE}/umroh/payment/verify`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      },
      body: JSON.stringify({ id: paymentId }),
    });
    return await response.json();
  } catch (error) {
    console.error('Error verifying payment:', error);
    throw error;
  }
}

/**
 * Create payment transaction and get QR code
 * @param {Object} paymentData - Payment data
 * @returns {Promise<Object>} Payment transaction with QR code
 * 
 * TODO: Client needs to implement this endpoint
 * Endpoint: POST /api/payments/create
 * Request body format:
 * {
 *   batch_id: "BATCH-xxx",
 *   amount: 1500000,
 *   payment_method: "qris"
 * }
 * 
 * Expected response:
 * {
 *   success: true,
 *   payment_id: "PAY-xxx",
 *   qr_code_url: "https://api.payment-gateway.com/qr/xxx.png",
 *   qr_string: "00020101021126...", // QRIS string for generating QR
 *   expires_at: "2026-01-10T08:00:00.000Z",
 *   amount: 1500000
 * }
 */
async function createPaymentTransaction(paymentData) {
  if (USE_MOCK_DATA) {
    // Mock response for development
    return new Promise((resolve) => {
      setTimeout(() => {
        console.log('💳 MOCK: Creating payment transaction');
        console.log('Payment data:', paymentData);
        resolve({
          success: true,
          payment_id: 'PAY-' + Date.now(),
          qr_code_url: 'https://via.placeholder.com/300x300.png?text=QRIS+Mock+QR',
          qr_string: '00020101021126...mock-qris-string...',
          expires_at: new Date(Date.now() + 15 * 60 * 1000).toISOString(),
          amount: paymentData.amount
        });
      }, 1000);
    });
  }

  // Use the new bulk payment API
  return createBulkPayment(paymentData);
}

/**
 * Check payment transaction status
 * @param {string} paymentId - Payment transaction ID
 * @returns {Promise<Object>} Payment status
 * 
 * TODO: Client needs to implement this endpoint
 * Endpoint: GET /api/payments/{payment_id}/status
 * 
 * Expected response:
 * {
 *   success: true,
 *   payment_id: "PAY-xxx",
 *   status: "success" | "pending" | "expired" | "failed",
 *   paid_at: "2026-01-10T07:50:00.000Z", // if status is success
 *   amount: 1500000
 * }
 */
async function checkPaymentStatus(paymentId) {
  if (USE_MOCK_DATA) {
    // Mock response for development
    return new Promise((resolve) => {
      setTimeout(() => {
        console.log('🔍 MOCK: Checking payment status for:', paymentId);
        // Randomly return success for demo (2% chance)
        const isSuccess = Math.random() > 0.98;
        resolve({
          success: true,
          payment_id: paymentId,
          status: isSuccess ? 'success' : 'pending',
          paid_at: isSuccess ? new Date().toISOString() : null,
          amount: 1500000
        });
      }, 1500);
    });
  }

  // Use the new payment status API
  const response = await getPaymentStatus(paymentId);
  
  // Map to expected format
  return {
    success: response.success,
    payment_id: response.data?.payment_id,
    status: response.data?.status,
    paid_at: response.data?.qris_date,
    amount: response.data?.total_pembayaran
  };
}
