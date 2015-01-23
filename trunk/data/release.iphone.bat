@echo off
call grunt requirejs:iphone
call grunt sass:iphone
call xcopy app\settings\site.js dist.iphone\js\ /Y
call xcopy dist.iphone\* ..\www\ /E /Y
call xcopy dist.iphone\* ..\platforms\ios\www /E /Y
@pause