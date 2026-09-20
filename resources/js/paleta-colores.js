export default function paletaColores(initial = {}) {
    const colors = {
        primary_color: initial.primary_color || '',
        secondary_color: initial.secondary_color || '',
        button_color: initial.button_color || '',
        menu_color: initial.menu_color || '',
        background_color: initial.background_color || '',
        text_color: initial.text_color || '',
    };

    return {
        colors,
        defaults: {
            primary_color: '#6f42c1',
            secondary_color: '#42c5c9',
            button_color: '#6f42c1',
            menu_color: '#101828',
            background_color: '#f4f6fb',
            text_color: '#1d2939',
        },

        hexToRgb(hex) {
            const h = (hex || '').replace('#', '');
            if (h.length !== 6) return null;
            const n = parseInt(h, 16);
            return [(n >> 16) & 255, (n >> 8) & 255, n & 255];
        },

        alphaColor(hex, alpha) {
            const rgb = this.hexToRgb(hex) || this.hexToRgb('#6f42c1');
            return `rgba(${rgb[0]}, ${rgb[1]}, ${rgb[2]}, ${alpha})`;
        },

        get previewBtn() {
            const c = this.colors.button_color || this.defaults.button_color;
            return { backgroundColor: c, borderColor: c, color: '#fff' };
        },

        get previewPill() {
            const c = this.colors.menu_color || this.defaults.menu_color;
            return { backgroundColor: c, color: '#fff' };
        },

        setColor(field, value) {
            this.colors[field] = value;
        },

        clearColor(field) {
            this.colors[field] = '';
        },
    };
}
