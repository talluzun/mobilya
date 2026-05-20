@php
    $statePath = $getStatePath();
@endphp

<div
    x-data="colorPickerField(@entangle($statePath))"
    x-init="init()"
    class="flex flex-col gap-2 overflow-visible"
>
    <div class="flex flex-wrap items-start gap-4 overflow-visible">
        <div
            x-ref="square"
            class="relative flex-shrink-0 overflow-visible"
            style="width: 220px; height: 220px; min-width: 220px; min-height: 220px;"
            @pointerdown.prevent="startSquare($event)"
        >
            <canvas
                x-ref="svCanvas"
                class="block rounded-md border border-gray-700"
                style="width: 220px; height: 220px;"
            ></canvas>
            <div
                class="absolute rounded-full border-2 border-white shadow"
                style="width: 16px; height: 16px; transform: translate(-8px, -8px);"
                x-bind:style="cursorStyle"
            ></div>
        </div>
        <div class="flex flex-col items-center gap-3 flex-shrink-0">
            <input
                type="range"
                min="0"
                max="360"
                step="1"
                class="w-4 h-56 accent-transparent"
                style="height: 220px; writing-mode: bt-lr; -webkit-appearance: slider-vertical; background: linear-gradient(to top, red, yellow, lime, cyan, blue, magenta, red);"
                x-model.number="h"
                @input="updateFromHsv"
            />
            <div class="rounded-md border border-gray-700 w-10 h-10" x-bind:style="previewStyle"></div>
        </div>
    </div>
    <input
        type="text"
        class="w-32 rounded-md border border-gray-700 bg-gray-900 text-gray-200 text-xs px-2 py-1"
        x-model="hexInput"
        @input="handleHexInput"
        x-bind:class="isHexValid ? 'border-gray-700' : 'border-red-500'"
    />
    <span class="text-xs text-red-400" x-show="!isHexValid">Geçerli bir hex girin (#RGB veya #RRGGBB).</span>
</div>

@once
    <script>
        function colorPickerField(state) {
            return {
                hex: state,
                h: 0,
                s: 100,
                v: 100,
                updatingFromPicker: false,
                hexInput: '',
                isHexValid: true,
                init() {
                    this.hex = this.normalizeHex(this.hex || '#FFFFFF');
                    this.setFromHex(this.hex || '#FFFFFF');
                    this.hexInput = this.hex;
                    this.refreshCanvas();

                    this.$watch('hex', value => {
                        if (this.updatingFromPicker || ! value) {
                            return;
                        }

                        const normalized = this.normalizeHex(value);
                        if (! normalized) {
                            return;
                        }

                        this.hex = normalized;
                        this.hexInput = normalized;
                        this.setFromHex(normalized);
                        this.refreshCanvas();
                    });

                    window.addEventListener('color-picker:refresh', () => {
                        this.refreshCanvas();
                    });
                },
                get cursorStyle() {
                    return `left: ${this.s}%; top: ${100 - this.v}%; background: ${this.hex || '#FFFFFF'};`;
                },
                get previewStyle() {
                    return `background: ${this.hex || '#FFFFFF'};`;
                },
                handleHexInput() {
                    const normalized = this.normalizeHex(this.hexInput);
                    if (! normalized) {
                        this.isHexValid = false;
                        return;
                    }

                    this.isHexValid = true;
                    this.hex = normalized;
                    this.hexInput = normalized;
                    this.setFromHex(normalized);
                    this.refreshCanvas();
                },
                startSquare(event) {
                    this.updateSquare(event);
                    const move = e => this.updateSquare(e);
                    const stop = () => {
                        window.removeEventListener('pointermove', move);
                        window.removeEventListener('pointerup', stop);
                    };
                    window.addEventListener('pointermove', move);
                    window.addEventListener('pointerup', stop);
                },
                updateSquare(event) {
                    const rect = this.$refs.square.getBoundingClientRect();
                    const x = Math.min(Math.max(0, event.clientX - rect.left), rect.width);
                    const y = Math.min(Math.max(0, event.clientY - rect.top), rect.height);
                    this.s = Math.round((x / rect.width) * 100);
                    this.v = Math.round(100 - (y / rect.height) * 100);
                    this.updateHex();
                },
                updateFromHsv() {
                    this.refreshCanvas();
                    this.updateHex();
                },
                setFromHex(hex) {
                    const { r, g, b } = this.hexToRgb(hex);
                    const { h, s, v } = this.rgbToHsv(r, g, b);
                    this.h = h;
                    this.s = s;
                    this.v = v;
                    this.hex = this.rgbToHex(r, g, b);
                },
                updateHex() {
                    const { r, g, b } = this.hsvToRgb(this.h, this.s, this.v);
                    this.updatingFromPicker = true;
                    this.hex = this.rgbToHex(r, g, b);
                    this.hexInput = this.hex;
                    this.$nextTick(() => {
                        this.updatingFromPicker = false;
                    });
                },
                refreshCanvas() {
                    this.$nextTick(() => {
                        if (! this.ensureCanvasSize()) {
                            requestAnimationFrame(() => this.refreshCanvas());
                            return;
                        }
                        this.drawSquare();
                    });
                },
                ensureCanvasSize() {
                    if (! this.$refs.svCanvas) {
                        return false;
                    }
                    const rect = this.$refs.svCanvas.getBoundingClientRect();
                    if (rect.width === 0 || rect.height === 0) {
                        return false;
                    }
                    this.$refs.svCanvas.width = rect.width;
                    this.$refs.svCanvas.height = rect.height;
                    return true;
                },
                drawSquare() {
                    const canvas = this.$refs.svCanvas;
                    if (! canvas) {
                        return;
                    }
                    const ctx = canvas.getContext('2d');
                    const { width, height } = canvas;
                    ctx.clearRect(0, 0, width, height);

                    const hueColor = `hsl(${this.h}, 100%, 50%)`;
                    const horiz = ctx.createLinearGradient(0, 0, width, 0);
                    horiz.addColorStop(0, '#FFFFFF');
                    horiz.addColorStop(1, hueColor);
                    ctx.fillStyle = horiz;
                    ctx.fillRect(0, 0, width, height);

                    const vert = ctx.createLinearGradient(0, 0, 0, height);
                    vert.addColorStop(0, 'rgba(0,0,0,0)');
                    vert.addColorStop(1, 'rgba(0,0,0,1)');
                    ctx.fillStyle = vert;
                    ctx.fillRect(0, 0, width, height);
                },
                hexToRgb(hex) {
                    const clean = hex.replace('#', '');
                    const num = parseInt(clean, 16);
                    return {
                        r: (num >> 16) & 255,
                        g: (num >> 8) & 255,
                        b: num & 255,
                    };
                },
                rgbToHex(r, g, b) {
                    const toHex = value => value.toString(16).padStart(2, '0');
                    return `#${toHex(r)}${toHex(g)}${toHex(b)}`.toUpperCase();
                },
                rgbToHsv(r, g, b) {
                    const rp = r / 255;
                    const gp = g / 255;
                    const bp = b / 255;
                    const max = Math.max(rp, gp, bp);
                    const min = Math.min(rp, gp, bp);
                    const delta = max - min;
                    let h = 0;

                    if (delta !== 0) {
                        if (max === rp) {
                            h = ((gp - bp) / delta) % 6;
                        } else if (max === gp) {
                            h = (bp - rp) / delta + 2;
                        } else {
                            h = (rp - gp) / delta + 4;
                        }
                        h = Math.round(h * 60);
                        if (h < 0) {
                            h += 360;
                        }
                    }

                    const s = max === 0 ? 0 : Math.round((delta / max) * 100);
                    const v = Math.round(max * 100);

                    return { h, s, v };
                },
                hsvToRgb(h, s, v) {
                    const sat = s / 100;
                    const val = v / 100;
                    const c = val * sat;
                    const x = c * (1 - Math.abs(((h / 60) % 2) - 1));
                    const m = val - c;
                    let rp = 0;
                    let gp = 0;
                    let bp = 0;

                    if (h >= 0 && h < 60) {
                        rp = c; gp = x; bp = 0;
                    } else if (h < 120) {
                        rp = x; gp = c; bp = 0;
                    } else if (h < 180) {
                        rp = 0; gp = c; bp = x;
                    } else if (h < 240) {
                        rp = 0; gp = x; bp = c;
                    } else if (h < 300) {
                        rp = x; gp = 0; bp = c;
                    } else {
                        rp = c; gp = 0; bp = x;
                    }

                    return {
                        r: Math.round((rp + m) * 255),
                        g: Math.round((gp + m) * 255),
                        b: Math.round((bp + m) * 255),
                    };
                },
                normalizeHex(value) {
                    if (! value) {
                        return null;
                    }

                    let hex = value.trim();
                    if (! hex.startsWith('#')) {
                        hex = `#${hex}`;
                    }

                    const shortMatch = /^#([0-9A-Fa-f]{3})$/.exec(hex);
                    if (shortMatch) {
                        const [r, g, b] = shortMatch[1].split('');
                        return `#${r}${r}${g}${g}${b}${b}`.toUpperCase();
                    }

                    if (/^#([0-9A-Fa-f]{6})$/.test(hex)) {
                        return hex.toUpperCase();
                    }

                    return null;
                },
            };
        }
    </script>

    <script>
        (() => {
            if (window.__colorPickerHook) {
                return;
            }

            window.__colorPickerHook = true;

            document.addEventListener('livewire:init', () => {
                Livewire.hook('message.processed', () => {
                    window.dispatchEvent(new CustomEvent('color-picker:refresh'));
                });
            });
        })();
    </script>
@endonce
