import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ["slider", "output"]

    async update() {
        this.outputTarget.textContent = "Likes  "+this.sliderTarget.value

        const currParams = new URLSearchParams(window.location.search);
        const page = currParams.get('page') || 1;
        const url = '/api/like/'+page+'?range='+this.sliderTarget.value;

        const response = await fetch(url, {
            headers: { "Accept": "application/json" }
        });

        const data = await response.json();

        data.forEach((value, index) => {
            const el = document.getElementById(`like-${index}`);
            if (el) {
                el.textContent = value;
            }
        });
    }
}
