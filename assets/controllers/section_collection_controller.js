import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['collection', 'prototype'];
    static values = { index: Number };

    connect() {
        if (!this.hasIndexValue) {
            this.indexValue = this.collectionTarget.children.length;
        }
        console.log('SectionCollectionController connected with index:', this.indexValue);
    }

    add(event) {
        event.preventDefault();
        const content = this.prototypeTarget.innerHTML.replace(/__name__/g, this.indexValue);
        const element = document.createElement('div');
        element.setAttribute('data-section-collection-target', 'item');
        element.innerHTML = content;
        this.collectionTarget.appendChild(element);
        this.indexValue++;
    }

    remove(event) {
        event.preventDefault();
        const item = event.currentTarget.closest('[data-section-collection-target="item"]');
        if (item) {
            item.remove();
        }
    }
}
