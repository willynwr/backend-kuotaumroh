/**
 * Reusable Alpine.js Components
 * Kuotaumroh.id Agent Portal
 */

/* ===========================
   Toast Notification Component
   =========================== */

/**
 * Create a toast notification component
 * @returns {Object} Alpine.js component
 */
function createToast() {
  return {
    visible: false,
    title: '',
    message: '',
    variant: 'default', // 'default', 'success', 'error'

    show(title, message, variant = 'default') {
      this.title = title;
      this.message = message;
      this.variant = variant;
      this.visible = true;
      setTimeout(() => {
        this.visible = false;
      }, 3000);
    },

    success(title, message) {
      this.show(title, message, 'success');
    },

    error(title, message) {
      this.show(title, message, 'error');
    },

    hide() {
      this.visible = false;
    }
  };
}

/* ===========================
   Dropdown Component
   =========================== */

/**
 * Create a dropdown component
 * @returns {Object} Alpine.js component
 */
function createDropdown() {
  return {
    open: false,

    toggle() {
      this.open = !this.open;
    },

    close() {
      this.open = false;
    },

    show() {
      this.open = true;
    }
  };
}

/* ===========================
   Modal/Dialog Component
   =========================== */

/**
 * Create a modal/dialog component
 * @returns {Object} Alpine.js component
 */
function createDialog() {
  return {
    open: false,

    show() {
      this.open = true;
      // Prevent body scroll when modal is open
      document.body.style.overflow = 'hidden';
    },

    hide() {
      this.open = false;
      // Restore body scroll
      document.body.style.overflow = '';
    },

    toggle() {
      if (this.open) {
        this.hide();
      } else {
        this.show();
      }
    }
  };
}

/* ===========================
   Tabs Component
   =========================== */

/**
 * Create a tabs component
 * @param {string} defaultTab - Default active tab
 * @returns {Object} Alpine.js component
 */
function createTabs(defaultTab = 'tab1') {
  return {
    activeTab: defaultTab,

    setTab(tab) {
      this.activeTab = tab;
    },

    isActive(tab) {
      return this.activeTab === tab;
    }
  };
}

/* ===========================
   Pagination Component
   =========================== */

/**
 * Create a pagination component
 * @param {number} totalItems - Total number of items
 * @param {number} itemsPerPage - Items per page
 * @returns {Object} Alpine.js component
 */
function createPagination(totalItems, itemsPerPage = 10) {
  return {
    currentPage: 1,
    totalItems: totalItems,
    itemsPerPage: itemsPerPage,

    get totalPages() {
      return Math.ceil(this.totalItems / this.itemsPerPage);
    },

    get startIndex() {
      return (this.currentPage - 1) * this.itemsPerPage;
    },

    get endIndex() {
      return Math.min(this.startIndex + this.itemsPerPage, this.totalItems);
    },

    nextPage() {
      if (this.currentPage < this.totalPages) {
        this.currentPage++;
      }
    },

    prevPage() {
      if (this.currentPage > 1) {
        this.currentPage--;
      }
    },

    goToPage(page) {
      if (page >= 1 && page <= this.totalPages) {
        this.currentPage = page;
      }
    }
  };
}

/* ===========================
   Search/Filter Component
   =========================== */

/**
 * Create a search/filter component
 * @param {Array} items - Items to search through
 * @param {Array} searchKeys - Keys to search in
 * @returns {Object} Alpine.js component
 */
function createSearch(items, searchKeys = []) {
  return {
    query: '',
    items: items,
    searchKeys: searchKeys,

    get filteredItems() {
      if (!this.query.trim()) {
        return this.items;
      }

      const lowerQuery = this.query.toLowerCase();

      return this.items.filter(item => {
        // If searchKeys provided, search only those keys
        if (this.searchKeys.length > 0) {
          return this.searchKeys.some(key => {
            const value = String(item[key] || '').toLowerCase();
            return value.includes(lowerQuery);
          });
        }

        // Otherwise, search all values
        return Object.values(item).some(value => {
          return String(value).toLowerCase().includes(lowerQuery);
        });
      });
    },

    clear() {
      this.query = '';
    }
  };
}

/* ===========================
   Loading State Component
   =========================== */

/**
 * Create a loading state component
 * @returns {Object} Alpine.js component
 */
function createLoadingState() {
  return {
    loading: false,
    error: null,

    startLoading() {
      this.loading = true;
      this.error = null;
    },

    stopLoading() {
      this.loading = false;
    },

    setError(error) {
      this.error = error;
      this.loading = false;
    },

    clearError() {
      this.error = null;
    },

    async execute(asyncFunction) {
      this.startLoading();
      try {
        const result = await asyncFunction();
        this.stopLoading();
        return result;
      } catch (err) {
        this.setError(err.message || 'An error occurred');
        throw err;
      }
    }
  };
}

/* ===========================
   Confirmation Dialog Component
   =========================== */

/**
 * Create a confirmation dialog component
 * @returns {Object} Alpine.js component
 */
function createConfirmDialog() {
  return {
    open: false,
    title: '',
    message: '',
    confirmText: 'Confirm',
    cancelText: 'Cancel',
    onConfirm: null,

    show(title, message, onConfirm, confirmText = 'Confirm', cancelText = 'Cancel') {
      this.title = title;
      this.message = message;
      this.onConfirm = onConfirm;
      this.confirmText = confirmText;
      this.cancelText = cancelText;
      this.open = true;
    },

    confirm() {
      if (this.onConfirm && typeof this.onConfirm === 'function') {
        this.onConfirm();
      }
      this.close();
    },

    close() {
      this.open = false;
      this.onConfirm = null;
    }
  };
}

/* ===========================
   Clipboard Copy Component
   =========================== */

/**
 * Create a clipboard copy component
 * @returns {Object} Alpine.js component
 */
function createClipboard() {
  return {
    copied: false,

    async copy(text) {
      try {
        await navigator.clipboard.writeText(text);
        this.copied = true;
        setTimeout(() => {
          this.copied = false;
        }, 2000);
        return true;
      } catch (err) {
        console.error('Failed to copy:', err);
        return false;
      }
    }
  };
}
