@echo off
call grunt requirejs:android
call grunt sass:android
call xcopy app\settings\site.js dist.android\js\ /Y
call xcopy dist.android\* ..\www\ /E /Y
call xcopy dist.android\* ..\platforms\android\assets\www /E /Y
@pause