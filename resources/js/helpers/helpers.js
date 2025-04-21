export function displayNumber(paginationData, index) {
    if (!paginationData || !paginationData.current_page || !paginationData.per_page) {
        return '';
    }
    return (paginationData.current_page - 1) * paginationData.per_page + index + 1;
}

export function formatCurrency(amount) {
    return new Intl.NumberFormat('id-ID', {
      style: 'currency',
      currency: 'IDR',
      minimumFractionDigits: 0, // Rupiah typically doesn’t use decimals
    }).format(amount);
  }