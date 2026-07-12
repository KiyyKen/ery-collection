import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

/**
 * Live search dengan debounce untuk halaman berbasis tabel (Produk, Distribusi).
 * `url` adalah endpoint index (mis. route('products.index')), `initialSearch`
 * adalah nilai pencarian yang sedang aktif saat halaman pertama kali dirender
 * (dari query string atau session). Hasil fetch (fragment HTML tabel+pagination)
 * disisipkan ke elemen ber-`x-ref="results"` di dalam komponen yang sama.
 */
Alpine.data('liveSearch', (url, initialSearch) => ({
    search: initialSearch ?? '',
    requestId: 0,

    fetchResults() {
        const currentRequest = ++this.requestId;
        const target = new URL(url, window.location.origin);

        if (this.search) {
            target.searchParams.set('search', this.search);
        } else {
            target.searchParams.set('search', '');
        }

        fetch(target, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then((response) => response.text())
            .then((html) => {
                if (currentRequest === this.requestId) {
                    this.$refs.results.innerHTML = html;
                    window.history.replaceState({}, '', target);
                }
            });
    },
}));

Alpine.start();
