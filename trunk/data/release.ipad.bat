@echo off
call grunt requirejs:ipad
call grunt sass:ipad
call xcopy app\settings\site.js dist.ipad\js\ /Y
call xcopy dist.ipad\* ..\www\ /E /Y
call xcopy dist.ipad\* ..\platforms\ipad\www /E /Y
@pause