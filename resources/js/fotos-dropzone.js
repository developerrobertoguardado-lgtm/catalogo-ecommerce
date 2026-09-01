/**
 * Alpine component for the product photo uploader: drag & drop to add files,
 * thumbnails in upload order, reorder by dragging. Existing (already saved)
 * images and newly staged files share one orderable list; the final order is
 * serialized into a hidden "orden_fotos" input read by the backend.
 */
export default function fotosDropzone(existentes, max) {
    return {
        items: [],
        dragOver: false,
        draggedIndex: null,
        max,

        init() {
            this.items = existentes.map((imagen) => ({
                key: `existing-${imagen.id}`,
                type: 'existing',
                id: imagen.id,
                previewUrl: imagen.url,
            }));
        },

        handleSelect(event) {
            this.addFiles(event.target.files);
        },

        handleDrop(event) {
            this.dragOver = false;
            this.addFiles(event.dataTransfer.files);
        },

        addFiles(fileList) {
            const remaining = this.max - this.items.length;

            Array.from(fileList)
                .slice(0, Math.max(remaining, 0))
                .forEach((file) => {
                    this.items.push({
                        key: `new-${Date.now()}-${Math.random()}`,
                        type: 'new',
                        file,
                        previewUrl: URL.createObjectURL(file),
                    });
                });

            this.syncFileInput();
        },

        quitarNueva(index) {
            this.items.splice(index, 1);
            this.syncFileInput();
        },

        dragStart(index) {
            this.draggedIndex = index;
        },

        dropAt(index) {
            if (this.draggedIndex === null || this.draggedIndex === index) {
                return;
            }

            const moved = this.items.splice(this.draggedIndex, 1)[0];
            this.items.splice(index, 0, moved);
            this.draggedIndex = null;
            this.syncFileInput();
        },

        syncFileInput() {
            const dataTransfer = new DataTransfer();
            this.items
                .filter((item) => item.type === 'new')
                .forEach((item) => dataTransfer.items.add(item.file));
            this.$refs.input.files = dataTransfer.files;
        },

        ordenTokens() {
            let newIndex = 0;

            return this.items.map((item) =>
                item.type === 'existing' ? `existing:${item.id}` : `new:${newIndex++}`
            );
        },
    };
}
