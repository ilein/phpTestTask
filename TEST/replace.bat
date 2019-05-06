@echo off

chcp 65001
cls
cd ..

php user_text_util.php replaceDates semicolon

pause >nul