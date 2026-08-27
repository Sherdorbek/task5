import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ["slider", "output"];

    connect() {
        this.update();
        this.fn = () => this.update();
        document.addEventListener('turbo:frame-render', this.fn);
        document.addEventListener('turbo:render', this.fn);
    }

    disconnect() {
        document.removeEventListener('turbo:frame-render', this.fn);
        document.removeEventListener('turbo:render', this.fn);
    }

    getPage() {
        const inp = document.querySelector('form[data-turbo-frame="my-frame"] input[name="page"]');
        if (inp?.value) return Math.max(1, parseInt(inp.value, 10));
        const count = document.querySelectorAll('#product-list .card').length;
        if (count) return Math.max(1, Math.ceil(count / 15));
        return Math.max(1, parseInt(new URLSearchParams(location.search).get('page'), 10) || 1);
    }

    async update() {
        const val = this.sliderTarget.value;
        const label = this.outputTarget.dataset.label || 'Likes';
        this.outputTarget.textContent = `${label} ${val}`;
        const res = await fetch(`/api/like/${this.getPage()}?range=${val}`);
        if (!res.ok) return;
        const data = await res.json();
        data.forEach((v, i) => {
            const el = document.getElementById(`like-${i}`);
            if (el) el.textContent = v;
            document.querySelectorAll(`[data-like-idx="${i}"], .like-val-${i}`).forEach(c => c.textContent = v);
        });
    }
}
