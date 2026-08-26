import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ["slider", "output"]

    update() {
        this.outputTarget.textContent = "Likes  "+this.sliderTarget.value
    }
}
