<div
    x-data="{
        canvas: null,
        ctx: null,
        drawing: false,
        init() {
            this.canvas = this.$refs.sig;
            this.canvas.width = 600;
            this.canvas.height = 400;
            this.ctx = this.canvas.getContext('2d');
            this.ctx.fillStyle = 'white';
            this.ctx.fillRect(0, 0, this.canvas.width, this.canvas.height);
            console.log('Livewire SignaturePad initialized');
        },
        clear() {
            // Reset canvas size first
            this.canvas.width = 600;
            this.canvas.height = 400;

            // Re-get context after resize
            this.ctx = this.canvas.getContext('2d');

            // Fill with white background
            this.ctx.fillStyle = 'white';
            this.ctx.fillRect(0, 0, this.canvas.width, this.canvas.height);

            $wire.clearSignature();
            console.log('Signature cleared via Livewire');
        },
        start(e) {
            this.drawing = true;
            this.draw(e);
        },
        end() {
            this.drawing = false;
            this.ctx.beginPath();
            this.update();
        },
        draw(e) {
            if (!this.drawing) return;
            const rect = this.canvas.getBoundingClientRect();
            const x = (e.touches ? e.touches[0].clientX : e.clientX) - rect.left;
            const y = (e.touches ? e.touches[0].clientY : e.clientY) - rect.top;
            this.ctx.lineWidth = 2;
            this.ctx.lineCap = 'round';
            this.ctx.strokeStyle = '#111827';
            this.ctx.lineTo(x, y);
            this.ctx.stroke();
            this.ctx.beginPath();
            this.ctx.moveTo(x, y);
        },
        update() {
            const dataUrl = this.canvas.toDataURL('image/png');
            $wire.updateSignature(dataUrl);
            console.log('✅ Signature sent to Livewire, length:', dataUrl.length);
        }
    }"
    class="space-y-2"
>
    <div class="text-sm text-gray-600 mb-2 font-medium">
        📝 Gambar tanda tangan Anda di area putih di bawah ini
    </div>

    <canvas
        x-ref="sig"
        class="border-2 border-gray-300 rounded bg-white w-full cursor-crosshair"
        style="touch-action: none; height: 400px;width: 600px"
        @mousedown="start($event)"
        @mouseup="end()"
        @mousemove="draw($event)"
        @touchstart.prevent="start($event)"
        @touchend.prevent="end()"
        @touchcancel.prevent="end()"
        @touchmove.prevent="draw($event)"
    ></canvas>

    <div class="flex items-center gap-4">
        <button
            type="button"
            class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 transition"
            @click="clear()"
        >
            <span class="flex items-center gap-2">
                Hapus Tanda Tangan
            </span>
        </button>

        @if($signatureData)
        <div class="text-sm text-green-600 font-medium flex items-center gap-2">
            Tanda tangan tersimpan ({{ strlen($signatureData) }} karakter)
        </div>
        @endif
    </div>
</div>

