/**
 * ギャラリーページ（gallery.blade.php）のライトボックス（#46）
 *
 * 使い方: x-data="galleryLightbox(@js($photos))"
 *   photos = [{ src: '…', alt: '…' }, …]
 *   サムネイルの <button> に x-ref="thumb0", "thumb1"… を付け、@click="show(index)" で開く。
 *
 * - Esc / 背景クリック / 閉じるボタンで閉じる。←/→ キーと前へ/次へボタンで移動（端はループ）。
 * - 開いたら閉じるボタンにフォーカスし、Tab はダイアログ内で循環させる（@alpinejs/focus 不使用の最小実装）。
 * - 閉じたら開いたときのサムネイルへフォーカスを戻す。開いている間は背景スクロールを止める。
 */
export default function galleryLightbox(photos = []) {
    return {
        photos,
        open: false,
        index: 0,
        openerIndex: 0,

        get current() {
            return this.photos[this.index] ?? { src: '', alt: '' };
        },

        show(i) {
            this.index = i;
            this.openerIndex = i;
            this.open = true;
            document.documentElement.style.overflow = 'hidden';
            this.$nextTick(() => this.$refs.closeButton?.focus());
        },

        close() {
            if (!this.open) return;
            this.open = false;
            document.documentElement.style.overflow = '';
            this.$nextTick(() => this.$refs[`thumb${this.openerIndex}`]?.focus());
        },

        next() {
            this.index = (this.index + 1) % this.photos.length;
        },

        prev() {
            this.index = (this.index - 1 + this.photos.length) % this.photos.length;
        },

        onKeydown(event) {
            if (!this.open) return;
            if (event.key === 'Escape') {
                event.preventDefault();
                this.close();
            } else if (event.key === 'ArrowRight') {
                event.preventDefault();
                this.next();
            } else if (event.key === 'ArrowLeft') {
                event.preventDefault();
                this.prev();
            } else if (event.key === 'Tab') {
                this.trapFocus(event);
            }
        },

        // ダイアログ内のボタンだけで Tab / Shift+Tab を循環させる
        trapFocus(event) {
            const focusables = Array.from(
                this.$refs.dialog.querySelectorAll('button:not([disabled])')
            );
            if (focusables.length === 0) return;
            const first = focusables[0];
            const last = focusables[focusables.length - 1];
            const active = document.activeElement;
            if (event.shiftKey && (active === first || !this.$refs.dialog.contains(active))) {
                event.preventDefault();
                last.focus();
            } else if (
                !event.shiftKey &&
                (active === last || !this.$refs.dialog.contains(active))
            ) {
                event.preventDefault();
                first.focus();
            }
        },

        // 写真やボタン以外（背景）をクリックしたら閉じる
        onBackdropClick(event) {
            if (event.target.closest('[data-lightbox-content]')) return;
            this.close();
        },
    };
}
