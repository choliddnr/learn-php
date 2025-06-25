import './style.css';
import Alpine from 'alpinejs'
import type { Tag } from './types';

const tags = window.server.tags || [];
const current_tags = window.server.current_tags || [];

function tagDropdown() {
    return {
        open: false,
        selected: [...current_tags],
        toggle(tag:Tag) {
            const index = this.selected.findIndex(t => t.id === tag.id);
            if (index > -1) {
                this.selected.splice(index, 1);
            } else {
                this.selected.push(tag);
            }
        }
    };
}

window.tagDropdown = tagDropdown;
window.tags = tags;
Alpine.start()