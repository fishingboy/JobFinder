# JobFinder PowerShell Script
# Usage: .\start.ps1 [command]
# Commands: start, stop, build, all, destroy, rebuild, refresh, init-db, init-data

param(
    [Parameter(Position=0)]
    [string]$Command = "start"
)

# Load .env file if exists
if (Test-Path ".env") {
    Get-Content ".env" | ForEach-Object {
        if ($_ -match "^\s*([^#][^=]+)=(.*)$") {
            [Environment]::SetEnvironmentVariable($matches[1].Trim(), $matches[2].Trim(), "Process")
        }
    }
}

function Start-App {
    if (-not (Test-Path ".env")) {
        if (Test-Path ".env.example") {
            Copy-Item ".env.example" -Destination ".env"
            Write-Host ">>> Created .env from .env.example" -ForegroundColor Yellow
        } else {
            Write-Host ">>> Warning: .env and .env.example not found" -ForegroundColor Red
        }
    }
    docker-compose up -d --no-recreate
    Write-Host ">>> Start: Visit http://localhost:9487 ...." -ForegroundColor Green
}

function Stop-App {
    docker-compose stop
    Write-Host ">>> Stopped" -ForegroundColor Yellow
}

function Build-Dependencies {
    Write-Host ">>> Installing Composer dependencies..." -ForegroundColor Cyan
    composer install
    Write-Host ">>> Installing NPM dependencies..." -ForegroundColor Cyan
    npm install
}

function Build-Docker {
    Write-Host ">>> Building Docker images..." -ForegroundColor Cyan
    docker-compose build --parallel
}

function Initialize-Database {
    Write-Host ">>> Running database migrations..." -ForegroundColor Cyan
    docker exec -it dev_phpfpm php artisan migrate
}

function Initialize-Data {
    Write-Host ">>> Initializing data..." -ForegroundColor Cyan
    Copy-Item "resources/json/condition.sample.json" -Destination "resources/json/condition.json" -Force
}

function Destroy-App {
    docker-compose down --remove-orphans
    if (Test-Path "vendor") { Remove-Item -Recurse -Force "vendor" }
    if (Test-Path "node_modules") { Remove-Item -Recurse -Force "node_modules" }
    Write-Host ">>> Destroyed" -ForegroundColor Red
}

function Refresh-Data {
    Write-Host ">>> Updating jobs..." -ForegroundColor Cyan
    docker exec -it dev_phpfpm php artisan update:jobs 104
    Write-Host ">>> Updating companies..." -ForegroundColor Cyan
    docker exec -it dev_phpfpm php artisan update:companies
}

function Show-Help {
    Write-Host "Usage: .\start.ps1 [command]" -ForegroundColor White
    Write-Host ""
    Write-Host "Commands:" -ForegroundColor Yellow
    Write-Host "  start      - Start Docker containers (default)"
    Write-Host "  stop       - Stop Docker containers"
    Write-Host "  build      - Install composer and npm dependencies"
    Write-Host "  all        - Full setup: docker build, start, dependencies, init db & data"
    Write-Host "  destroy    - Remove containers, vendor and node_modules"
    Write-Host "  rebuild    - Destroy and rebuild everything"
    Write-Host "  refresh    - Update jobs and companies data"
    Write-Host "  init-db    - Run database migrations"
    Write-Host "  init-data  - Initialize condition.json"
    Write-Host "  help       - Show this help message"
}

switch ($Command.ToLower()) {
    "start"     { Start-App }
    "stop"      { Stop-App }
    "build"     { Build-Dependencies }
    "all"       {
        Build-Docker
        Start-App
        Build-Dependencies
        Initialize-Database
        Initialize-Data
    }
    "destroy"   { Destroy-App }
    "rebuild"   {
        Destroy-App
        Build-Docker
        Start-App
        Build-Dependencies
        Initialize-Database
        Initialize-Data
    }
    "refresh"   { Refresh-Data }
    "init-db"   { Initialize-Database }
    "init-data" { Initialize-Data }
    "help"      { Show-Help }
    default     {
        Write-Host "Unknown command: $Command" -ForegroundColor Red
        Show-Help
    }
}
