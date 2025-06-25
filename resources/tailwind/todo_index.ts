import './style.css';
import Alpine from 'alpinejs'
import type { Tag } from './types';



const todo_url = "/todo"
const filter_todo = Alpine.reactive({url:todo_url, status: "", tags: [] as number[]});

function deleteTodo(id: number) {
    console.log('tags', 'current_tags');
    if (confirm("Are you sure you want to delete this todo?")) {
        fetch('/todo/' + id, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json'
                },
            })
            .then(response => {
                if (response.ok) {
                    console.log("Todo deleted successfully.");
                    window.location.reload(); // Refresh the page to see the updated list
                } else {
                    console.error("Failed to delete todo.");
                }
            })
            .catch(error => {
                alert("Error: "+ error);
            });

    } else {
        console.log("Delete action canceled.");
    }
}
const tags = window.server.tags
const statuses = ['pending', 'processed', 'done'];

const filterTodo = ()=>{
    return {
        tag_open: false,
        status_open: false,
        url: todo_url,
        status_ids: filter_todo.status,
        tag_ids: filter_todo.tags,
        tag_selected: [] as Tag[],
        status_selected: [...window.server.filter.status] as string[],
        init() {
            tags.forEach((tag: Tag) => {
               if(window.server.filter.tags.includes(tag.id)) this.toggleTag(tag);
            });
            this.setUrl();
        },
        toggleTag(tag: Tag) {
            const index = this.tag_selected.findIndex(t => t.id === tag.id);
            if (index > -1) {
                this.tag_selected.splice(index, 1);
                filter_todo.tags.splice(index, 1);
            } else {
                this.tag_selected.push(tag);
                filter_todo.tags.push(tag.id);
            }
            this.setUrl();
            
            
        },
        toggleStatus(status: string) {
            const index = this.status_selected.findIndex(s => s === status);
            if (index > -1) {
                this.status_selected.splice(index, 1);
            } else {
                this.status_selected.push(status);
            }
            this.setUrl();
            
        },
        setUrl() {
            this.url = todo_url + '?';
            if (this.status_selected.length > 0) {
                this.url += 'status=' + this.status_selected.join(',') + '&';
            }
            if (this.tag_ids.length > 0) {
                this.url += 'tags=' + this.tag_ids.join(',') + '&';
            }
            this.url = this.url.slice(0, -1); // Remove the trailing '&'
        }
    }
}


window.filterTodo = filterTodo
window.statuses = statuses
window.tags = tags
window.Alpine = Alpine
window.deleteTodo = deleteTodo
Alpine.start()