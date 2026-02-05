#!/usr/bin/env pwsh
# PowerShell script to add Node.js to PATH and start dev server

Write-Host "Adding Node.js to PATH..." -ForegroundColor Green
`$env:PATH = "C:\Program Files\nodejs;" + `$env:PATH

Write-Host "Node.js version:" -ForegroundColor Cyan
node --version
npm --version

Write-Host "`nStarting Laravel development server..." -ForegroundColor Green
cd C:\xampp\htdocs\ppds\Admin

Write-Host "`nRunning: npm run dev`n" -ForegroundColor Yellow
npm run dev
