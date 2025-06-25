declare global {
    interface Window {
        server: {
            view: string;
            tags: Tag[];
            current_tags: Tag[];
            filter: {
                tags: number[];
                status: string[];
            };
        };
        Alpine: typeof Alpine;
        tags: Tag[];
        statuses: string[];
        statusFilterDropdown: () => {
            open: boolean;
            selected: string[];
            toggle: (status: string) => void;
        };
        deleteTodo: (id: number) => void;
        tagsFilterDropdown: () => {
            open: boolean;
            selected: Tag[];
            toggle: (tag: Tag) => void;
        };
        filterTodo: () => {
            tag_open: boolean;
            status_open: boolean;
            url: string;
            status_ids: string;
            tag_ids: number[];
            tag_selected: Tag[];
            status_selected: string[];
            toggleTag: (tag: Tag) => void;
            toggleStatus: (status: string) => void;
            // init: () => void;
        };
        tagDropdown: () => {
            open: boolean;
            selected: Tag[];
            toggle: (tag: Tag) => void;
        };
    }
}
import Alpine from 'alpinejs';
export type Tag = {
    id: number;
    title: string;
    description: string;
    
}