import { defineConfig } from 'vite';
import tailwindcss from '@tailwindcss/vite';
import fullReload from 'vite-plugin-full-reload';
import path from 'path';

export default defineConfig({
  // root: 'public',
  publicDir: false,
  plugins: [
    fullReload(['../*.php', '../**/*.php']), // Adjust paths based on your structure
    tailwindcss(),
  ],
  server: {
    port: 5173,
    hmr: {
      host: 'localhost',
    }
  },
  build: {
    rollupOptions: {
      input: {
        // app: path.resolve(__dirname, 'resources/index.js'), // Or wherever your main JS is //for bootstrap css
        // app: path.resolve(__dirname, 'resources/tailwind/index.ts'), // Or wherever your main JS is //tailwind css
        index: path.resolve(__dirname, 'resources/tailwind/index.ts'), 
        todo_index: path.resolve(__dirname, 'resources/tailwind/todo_index.ts'), 
        todo_create: path.resolve(__dirname, 'resources/tailwind/todo_create.ts'), 
        todo_show: path.resolve(__dirname, 'resources/tailwind/todo_show.ts'), 
        todo_update: path.resolve(__dirname, 'resources/tailwind/todo_update.ts'), 
        
      },
    },
    outDir: 'public',
    emptyOutDir: true,
  }
});
