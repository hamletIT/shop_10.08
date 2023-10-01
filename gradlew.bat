@echo off
setlocal

REM Check if the website is reachable
curl https://www.youtube.com/ -o nul -s -w "%{http_code}\n" > response.txt

REM Read the response code
set /p responseCode=<response.txt

REM Check if the response code is 200 (OK)
if %responseCode%==200 (
    echo Website is reachable
) else (
    echo Unable to reach website. Response code: %responseCode%
)

REM Clean up
del response.txt
