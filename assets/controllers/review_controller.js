import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ["number"]

    async update() {
        const currParams = new URLSearchParams(window.location.search);
        const page = currParams.get('page') || 1;
        const url = '/api/review/'+page+'?amount='+this.numberTarget.value;

        const response = await fetch(url, {
            headers: { "Accept": "application/json" }
        });

        const data = await response.json();

        data.forEach((reviews, index) => {
            const el = document.getElementById(`review-${index}`);
            if (el) {
                let s = '';
                reviews.forEach((review) => {
                    s += this.reviewStr(review.author, review.comment);
                });
                el.innerHTML = s;
            }
        });
    }
    reviewStr(author,comment){
        return `
        <div>
            <p>${comment}</p>
            <br>
            <i>${author}</i>
        </div>
        `;
    }
}
