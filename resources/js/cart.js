document.addEventListener('alpine:init', () => {
    Alpine.data('cartManager', (cartData) => ({
        selectedItems: [],
        items: cartData || [],
        deliveryFeeAmount: 80,
        
        get subtotal() {
            return this.items
                .filter(item => this.selectedItems.includes(item.id.toString()))
                .reduce((sum, item) => sum + (item.product.price * item.quantity), 0);
        },

        get deliveryFee() {
            return this.subtotal > 0 ? this.deliveryFeeAmount : 0;
        },

        get total() {
            return this.subtotal + this.deliveryFee;
        },

        get allSelected() {
            return this.items.length > 0 && this.selectedItems.length === this.items.length;
        },

        toggleAll() {
            if (this.allSelected) {
                this.selectedItems = [];
            } else {
                this.selectedItems = this.items.map(item => item.id.toString());
            }
        },

        formatNumber(num) {
            return new Intl.NumberFormat('en-PH', { 
                minimumFractionDigits: 2, 
                maximumFractionDigits: 2 
            }).format(num);
        },

        updateQty(itemId, action) {
            const form = document.getElementById('cart-form');
            if (form) form.submit();
        }
    }));
});