import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ["number"];

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
        if (inp?.value) return Math.max(1, inp.value - 1);
        const count = document.querySelectorAll('#product-list .card').length;
        return count ? Math.ceil(count / 15) : (new URLSearchParams(location.search).get('page') || 1);
    }

    async update() {
        const val = this.numberTarget.value || 0;
        const res = await fetch(`/api/review/${this.getPage()}?amount=${val}`);
        if (!res.ok) return;
        const data = await res.json();
        data.forEach((list, i) => {
            const el = document.getElementById(`review-${i}`);
            if (!el) return;
            el.innerHTML = list.length ? list.map(r => `
                <div class="mt-2">
                    <p class="text-secondary-emphasis mt-0">${r.comment}</p>
                    <i class="text-secondary">- ${r.author}</i>
                </div>`).join('') : '-';
        });
    }
}
