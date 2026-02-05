# JobFinder PowerShell Script
# Usage: .\start.ps1 [command]
# Commands: start, stop, build, all, destroy, rebuild, refresh, init-db, init-data

param(
    [Parameter(Position=0)]
    [string]$Command = ""
)

# Load .env file if exists
if (Test-Path ".env") {
    Get-Content ".env" | ForEach-Object {
        if ($_ -match "^\s*([^#][^=]+)=(.*)$") {
            [Environment]::SetEnvironmentVariable($matches[1].Trim(), $matches[2].Trim(), "Process")
        }
    }
}

# Menu items
$menuItems = @(
    @{ Key = "start";     Label = "Start";     Desc = "Start Docker containers" },
    @{ Key = "stop";      Label = "Stop";      Desc = "Stop Docker containers" },
    @{ Key = "build";     Label = "Build";     Desc = "Install composer and npm dependencies" },
    @{ Key = "all";       Label = "All";       Desc = "Full setup: docker build, start, dependencies, init" },
    @{ Key = "destroy";   Label = "Destroy";   Desc = "Remove containers, vendor and node_modules" },
    @{ Key = "rebuild";   Label = "Rebuild";   Desc = "Destroy and rebuild everything" },
    @{ Key = "refresh";   Label = "Refresh";   Desc = "Update jobs and companies data" },
    @{ Key = "init-db";   Label = "Init DB";   Desc = "Run database migrations" },
    @{ Key = "init-data"; Label = "Init Data"; Desc = "Initialize condition.json" },
    @{ Key = "exit";      Label = "Exit";      Desc = "Exit this menu" }
)

function Show-Menu {
    param([int]$SelectedIndex)

    Clear-Host
    Write-Host ""
    Write-Host "  +------------------------------------------------------------+" -ForegroundColor Cyan
    Write-Host "  |              JobFinder - Development Tools                 |" -ForegroundColor Cyan
    Write-Host "  +------------------------------------------------------------+" -ForegroundColor Cyan
    Write-Host ""
    Write-Host "  Use Up/Down to navigate, Enter to select, Esc to exit" -ForegroundColor DarkGray
    Write-Host ""

    for ($i = 0; $i -lt $menuItems.Count; $i++) {
        $item = $menuItems[$i]
        if ($i -eq $SelectedIndex) {
            Write-Host "  > " -ForegroundColor Green -NoNewline
            Write-Host "$($item.Label.PadRight(12))" -ForegroundColor Green -NoNewline
            Write-Host " - $($item.Desc)" -ForegroundColor White
        } else {
            Write-Host "    " -NoNewline
            Write-Host "$($item.Label.PadRight(12))" -ForegroundColor Gray -NoNewline
            Write-Host " - $($item.Desc)" -ForegroundColor DarkGray
        }
    }
    Write-Host ""
}

function Get-MenuSelection {
    $selectedIndex = 0
    $running = $true

    [Console]::CursorVisible = $false

    while ($running) {
        Show-Menu -SelectedIndex $selectedIndex

        $key = $Host.UI.RawUI.ReadKey("NoEcho,IncludeKeyDown")

        switch ($key.VirtualKeyCode) {
            38 { # Up arrow
                if ($selectedIndex -gt 0) { $selectedIndex-- }
                else { $selectedIndex = $menuItems.Count - 1 }
            }
            40 { # Down arrow
                if ($selectedIndex -lt $menuItems.Count - 1) { $selectedIndex++ }
                else { $selectedIndex = 0 }
            }
            13 { # Enter
                $running = $false
            }
            27 { # Escape
                [Console]::CursorVisible = $true
                return "exit"
            }
        }
    }

    [Console]::CursorVisible = $true
    Clear-Host
    return $menuItems[$selectedIndex].Key
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
    docker exec -it dev_phpfpm composer install
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
    Write-Host "  start      - Start Docker containers"
    Write-Host "  stop       - Stop Docker containers"
    Write-Host "  build      - Install composer and npm dependencies"
    Write-Host "  all        - Full setup: docker build, start, dependencies, init"
    Write-Host "  destroy    - Remove containers, vendor and node_modules"
    Write-Host "  rebuild    - Destroy and rebuild everything"
    Write-Host "  refresh    - Update jobs and companies data"
    Write-Host "  init-db    - Run database migrations"
    Write-Host "  init-data  - Initialize condition.json"
    Write-Host "  help       - Show this help message"
    Write-Host ""
    Write-Host "Run without arguments to show interactive menu." -ForegroundColor DarkGray
}

function Invoke-Action {
    param([string]$Cmd)

    switch ($Cmd.ToLower()) {
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
        "exit"      { exit 0 }
        default     {
            Write-Host "Unknown command: $Cmd" -ForegroundColor Red
            Show-Help
        }
    }
}

# Main
if ($Command -eq "") {
    $selected = Get-MenuSelection
    Invoke-Action -Cmd $selected
} else {
    Invoke-Action -Cmd $Command
}
