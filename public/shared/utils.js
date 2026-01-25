/**
 * Shared Utility Functions
 * Kuotaumroh.id Agent Portal
 */

/* ===========================
   Number Formatting
   =========================== */

/**
 * Format number to Indonesian Rupiah format
 * @param {number} amount - Amount to format
 * @returns {string} Formatted rupiah string
 */
function formatRupiah(amount) {
  return `Rp ${amount.toLocaleString('id-ID')}`;
}

/* ===========================
   Date Formatting
   =========================== */

/**
 * Format date to Indonesian format (dd MMM yyyy)
 * @param {Date|string} date - Date to format
 * @returns {string} Formatted date string
 */
function formatDate(date) {
  const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
  const d = new Date(date);
  return `${d.getDate()} ${months[d.getMonth()]} ${d.getFullYear()}`;
}

/**
 * Format date and time to Indonesian format
 * @param {Date|string} date - Date to format
 * @returns {string} Formatted date and time string
 */
function formatDateTime(date) {
  const d = new Date(date);
  const hours = String(d.getHours()).padStart(2, '0');
  const minutes = String(d.getMinutes()).padStart(2, '0');
  return `${formatDate(d)} ${hours}:${minutes}`;
}

/* ===========================
   Phone Number (MSISDN) Utilities
   =========================== */

const PROVIDER_PREFIXES = {
  'ByU':['0851'],
  'Telkomsel': [ '0810','0811','0812','0813',
    '0820','0821','0822','0823','0824','0825','0827','0828','0829','0841','0850',
    '0851','0852','0853','0854','0861','0862','0863','0864','0865','0866',
    '0867','0869','0871','0872','0873','0874','0875','0880'],
  'Indosat': ['0814', '0815', '0816', '0855', '0856', '0857', '0858'],
  'XL': ['0817', '0818', '0819', '0859', '0877', '0878'],
  'Axis': ['0831', '0832', '0833', '0838'],
  'Tri': ['0895', '0896', '0897', '0898', '0899'],
  'Smartfren': ['0881', '0882', '0883', '0884', '0885', '0886', '0887', '0888', '0889'],
};

/**
 * Detect mobile provider from phone number
 * @param {string} msisdn - Phone number
 * @returns {string|null} Provider name or null
 */
function detectProvider(msisdn) {
  const normalized = normalizeMsisdn(msisdn);

  for (const [provider, prefixes] of Object.entries(PROVIDER_PREFIXES)) {
    if (prefixes.some(prefix => normalized.startsWith(prefix))) {
      return provider;
    }
  }

  return null;
}

/**
 * Validate phone number format
 * @param {string} msisdn - Phone number to validate
 * @returns {boolean} True if valid
 */
function validateMsisdn(msisdn) {
  const normalized = normalizeMsisdn(msisdn);
  return /^0\d{9,12}$/.test(normalized);
}

/**
 * Normalize phone number format
 * @param {string} msisdn - Phone number to normalize
 * @returns {string} Normalized phone number
 */
function normalizeMsisdn(msisdn) {
  return msisdn
    .replace(/^62/, '0')
    .replace(/^\+62/, '0')
    .replace(/\s+/g, '');
}

/**
 * Normalize provider name for API calls
 * @param {string} detectedProvider - Detected provider name
 * @returns {string} API-compatible provider name
 */
function normalizeProviderForApi(detectedProvider) {
  // Map detected provider names to API type field values
  const providerMap = {
    'telkomsel': 'TELKOMSEL',
    'indosat': 'INDOSAT',
    'xl': 'XL',
    'axis': 'AXIS',  // Note: AXIS has its own packages in the new API
    'tri': 'TRI',
    'smartfren': 'SMARTFREN',
  };

  return providerMap[detectedProvider.toLowerCase()] || detectedProvider.toUpperCase();
}

/* ===========================
   String Utilities
   =========================== */

/**
 * Truncate string with ellipsis
 * @param {string} str - String to truncate
 * @param {number} maxLength - Maximum length
 * @returns {string} Truncated string
 */
function truncate(str, maxLength) {
  if (str.length <= maxLength) return str;
  return str.substring(0, maxLength - 3) + '...';
}

/**
 * Capitalize first letter of string
 * @param {string} str - String to capitalize
 * @returns {string} Capitalized string
 */
function capitalize(str) {
  return str.charAt(0).toUpperCase() + str.slice(1).toLowerCase();
}

/* ===========================
   Array Utilities
   =========================== */

/**
 * Group array of objects by key
 * @param {Array} array - Array to group
 * @param {string} key - Key to group by
 * @returns {Object} Grouped object
 */
function groupBy(array, key) {
  return array.reduce((result, item) => {
    const groupKey = item[key];
    if (!result[groupKey]) {
      result[groupKey] = [];
    }
    result[groupKey].push(item);
    return result;
  }, {});
}

/* ===========================
   Validation
   =========================== */

/**
 * Check if email is valid
 * @param {string} email - Email to validate
 * @returns {boolean} True if valid
 */
function isValidEmail(email) {
  return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
}

/* ===========================
   Local Storage Helpers
   =========================== */

/**
 * Get user data from localStorage
 * @returns {Object} User data or empty object
 */
function getUser() {
  try {
    return JSON.parse(localStorage.getItem('user') || '{}');
  } catch {
    return {};
  }
}

/**
 * Set user data in localStorage
 * @param {Object} userData - User data to save
 */
function setUser(userData) {
  localStorage.setItem('user', JSON.stringify(userData));
}

/**
 * Clear user data (logout)
 */
function clearUser() {
  localStorage.removeItem('user');
}

function setReferralContext(context) {
  try {
    if (!context) {
      localStorage.removeItem('referral_context');
      return;
    }
    localStorage.setItem('referral_context', JSON.stringify({
      ...context,
      at: Date.now()
    }));
  } catch {
  }
}

function getReferralContext() {
  try {
    return JSON.parse(localStorage.getItem('referral_context') || 'null');
  } catch {
    return null;
  }
}

function clearReferralContext() {
  localStorage.removeItem('referral_context');
}

function parseReferralString(str) {
  if (!str || typeof str !== 'string') return null;
  const parts = str.split(':');
  if (parts.length !== 2) return null;
  const type = parts[0];
  const id = parseInt(parts[1], 10);
  if (!Number.isFinite(id) || id <= 0) return null;
  if (type !== 'affiliate' && type !== 'freelance') return null;
  return { source_type: type, id };
}

function setReferral(ref) {
  if (!ref) return;
  localStorage.setItem('kuotaumroh_ref', JSON.stringify(ref));
  localStorage.setItem('kuotaumroh_ref_ts', String(Date.now()));
  try {
    const d = new Date();
    d.setTime(d.getTime() + 30 * 24 * 60 * 60 * 1000);
    document.cookie = `kuotaumroh_ref=${encodeURIComponent(JSON.stringify(ref))};expires=${d.toUTCString()};path=/`;
  } catch { }
}

function getReferral() {
  try {
    const v = localStorage.getItem('kuotaumroh_ref');
    if (v) return JSON.parse(v);
    const m = document.cookie.match(/(?:^|; )kuotaumroh_ref=([^;]+)/);
    if (m) return JSON.parse(decodeURIComponent(m[1]));
  } catch { }
  return null;
}

function clearReferral() {
  localStorage.removeItem('kuotaumroh_ref');
  localStorage.removeItem('kuotaumroh_ref_ts');
  document.cookie = 'kuotaumroh_ref=;expires=Thu, 01 Jan 1970 00:00:00 GMT;path=/';
}

/**
 * Check if user is logged in
 * @returns {boolean} True if logged in
 */
function isLoggedIn() {
  const user = getUser();
  // Check if user has any role (agent, freelance, admin) or legacy agentCode
  return user && (user.role || user.agentCode || user.email);
}

/* ===========================
   Business Logic
   =========================== */

/**
 * Calculate profit from base price
 * @param {number} basePrice - Base price
 * @param {number} marginPercent - Margin percentage (default 30)
 * @returns {Object} Object with sellPrice and profit
 */
function calculateProfit(basePrice, marginPercent = 30) {
  const profit = Math.round(basePrice * (marginPercent / 100));
  return {
    sellPrice: basePrice + profit,
    profit,
  };
}

/* ===========================
   Role Management
   =========================== */

/**
 * Save user with role to localStorage
 * @param {Object} userData - User data including role
 */
function saveUser(userData) {
  localStorage.setItem('user', JSON.stringify({
    ...userData,
    role: userData.role || 'agent' // 'admin', 'freelance', 'agent'
  }));
  if (userData.token) {
    localStorage.setItem('token', userData.token);
  }
}

/**
 * Get user role
 * @returns {string|null} User role or null
 */
function getUserRole() {
  const user = getUser();
  return user?.role || null;
}

function getQueryInt(key) {
  try {
    const params = new URLSearchParams(window.location.search);
    const v = parseInt(params.get(key), 10);
    if (Number.isFinite(v) && v > 0) return v;
  } catch { }
  return null;
}

function appendQueryParam(url, key, value) {
  try {
    const u = new URL(url, window.location.href);
    u.searchParams.set(key, String(value));
    return u.pathname + u.search + u.hash;
  } catch {
    const sep = url.includes('?') ? '&' : '?';
    return `${url}${sep}${encodeURIComponent(key)}=${encodeURIComponent(String(value))}`;
  }
}

function syncFreelanceIdFromUrl() {
  const id = getQueryInt('id');
  if (!id) return null;
  let role = 'freelance';
  try {
    const params = new URLSearchParams(window.location.search);
    const hinted = params.get('type');
    if (hinted === 'affiliate') role = 'affiliate';
  } catch { }
  const current = getUser() || {};
  setUser({
    ...current,
    id,
    role
  });
  return id;
}

function syncAgentIdFromUrl() {
  const id = getQueryInt('id');
  if (!id) return null;
  const current = getUser() || {};
  setUser({
    ...current,
    id,
    role: 'agent'
  });
  return id;
}

/**
 * Role-based redirect after login
 */
function redirectToDashboard() {
  const role = getUserRole();
  const user = getUser();
  const freelanceId = role === 'freelance' ? (user?.id || null) : null;
  const affiliateId = role === 'affiliate' ? (user?.id || null) : null;
  const agentId = role === 'agent' ? (user?.id || null) : null;
  const dashboards = {
    'admin': '/admin/dashboard.html',
    'freelance': freelanceId ? appendQueryParam('/freelance/dashboard.html', 'id', freelanceId) : '/freelance/dashboard.html',
    'affiliate': affiliateId ? appendQueryParam(appendQueryParam('/freelance/dashboard.html', 'id', affiliateId), 'type', 'affiliate') : appendQueryParam('/freelance/dashboard.html', 'type', 'affiliate'),
    'agent': agentId ? appendQueryParam('/agent/dashboard.html', 'id', agentId) : '/agent/dashboard.html'
  };
  window.location.href = dashboards[role] || '/login.html';
}

/**
 * Check role access (use in each page's init)
 * @param {Array} allowedRoles - Array of allowed roles
 * @param {boolean} demoMode - If true, auto-create/update demo user instead of redirecting
 */
function requireRole(allowedRoles, demoMode = false) {
  const isDemoMode = demoMode || window.location.search.includes('demo');

  // In demo mode, auto-set or update user to the required role
  if (isDemoMode) {
    const currentRole = getUserRole();
    // If not logged in OR logged in with wrong role, set the demo user
    if (!isLoggedIn() || !allowedRoles.includes(currentRole)) {
      let demoRole = allowedRoles[0];
      try {
        const params = new URLSearchParams(window.location.search);
        const hinted = params.get('type') || params.get('role');
        if (hinted && allowedRoles.includes(hinted)) demoRole = hinted;
      } catch { }
      saveUser({
        name: demoRole === 'admin' ? 'Admin Demo' : (demoRole === 'agent' ? 'Agent Demo' : 'Affiliate Demo'),
        email: `demo@${demoRole}.com`,
        role: demoRole,
        referralCode: (demoRole === 'freelance' || demoRole === 'affiliate') ? 'demo-ref' : undefined,
        agentCode: demoRole === 'agent' ? 'AGT-DEMO' : undefined
      });
    }
    return true;
  }

  if (!isLoggedIn()) {
    window.location.href = '/login.html';
    return false;
  }

  const userRole = getUserRole();
  if (!allowedRoles.includes(userRole)) {
    // Redirect to their proper dashboard or login
    redirectToDashboard();
    return false;
  }
  return true;
}

/**
 * Check if user has specific permission
 * @param {string} permission - Permission to check
 * @returns {boolean} True if user has permission
 */
function hasPermission(permission) {
  const role = getUserRole();
  const permissions = {
    'admin': ['all'],
    'freelance': ['view_downlines', 'view_points', 'claim_rewards', 'view_profile'],
    'affiliate': ['view_downlines', 'view_points', 'claim_rewards', 'view_profile'],
    'agent': ['create_orders', 'view_own_orders', 'manage_wallet', 'view_referrals', 'view_profile']
  };

  return permissions[role]?.includes(permission) || permissions[role]?.includes('all');
}
