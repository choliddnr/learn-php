import './style.css';
import Alpine from 'alpinejs'
import type { Tag } from './types';

const tags = window.server.tags || [];
function tagDropdown() {
    return {
        open: false,
        selected: [] as Tag[],
        toggle(tag: Tag) {
            if (this.selected.includes(tag)) {
                this.selected = this.selected.filter(t => t.id !== tag.id);
            } else {
                this.selected.push(tag);
            }
        }
    };
}

window.tagDropdown = tagDropdown
window.tags = tags
Alpine.start()