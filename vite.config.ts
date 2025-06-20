import { defineConfig } from 'vite';
import fullReload from 'vite-plugin-full-reload';
import path from 'path';

export default defineConfig({
  // root: 'public',
  plugins: [
    fullReload(['../*.php', '../**/*.php']) // Adjust paths based on your structure
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
        app: path.resolve(__dirname, 'resources/index.js'), // Or wherever your main JS is
      },
    },
    outDir: 'public/dist',
    emptyOutDir: true,
  }
});
