module.exports = {
  apps: [{
    name: 'laravel-vite',
    script: 'npm',
    args: 'run dev',
    watch: false, // Disable PM2's file watching
    autorestart: true,
    env: {
      NODE_ENV: 'development',
      // Add other environment variables if needed
    }
  }]
}