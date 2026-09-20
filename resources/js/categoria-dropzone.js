export default function categoriaDropzone(existingUrl = null) {
    return {
        preview: existingUrl,
        dragOver: false,
        handleSelect(event) { this.setFile(event.target.files[0]); },
        handleDrop(event) { this.dragOver = false; this.setFile(event.dataTransfer.files[0]); },
        setFile(file) {
            if (!file) return;
            this.preview = URL.createObjectURL(file);
            const transfer = new DataTransfer();
            transfer.items.add(file);
            this.$refs.input.files = transfer.files;
            this.$refs.remove.value = '0';
        },
        removeImage() {
            this.preview = null;
            this.$refs.input.value = '';
            this.$refs.remove.value = '1';
        },
    };
}
