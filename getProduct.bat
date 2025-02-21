@echo off
chcp 65001 >nul
setlocal EnableDelayedExpansion

rem Default values (set to empty)
set "SEARCH="
set "AUTH="

rem Parse command-line arguments
:parse
if "%~1"=="" goto :endparse
if /i "%~1"=="-search" (
    set "SEARCH=%~2"
    shift
    shift
    goto :parse
)
if /i "%~1"=="-auth" (
    set "AUTH=%~2"
    shift
    shift
    goto :parse
)
shift
goto :parse

:endparse

rem Check if required parameters are provided
if "!SEARCH!"=="" (
    echo Error: -search parameter is required
    exit /b 1
)
if "!AUTH!"=="" (
    echo Error: -auth parameter is required
    exit /b 1
)

rem Run the curl command
curl --location "http://localhost:8000/api/admin/product-cmd" ^
     --header "accept: application/json" ^
     --header "Accept-Charset: UTF-8" ^
     --header "X-CSRF-TOKEN;" ^
     --header "Content-Type: application/json" ^
     --header "Authorization: Bearer !AUTH!" ^
     --data "{ \"search\" : \"%search%\" }"


endlocal