@echo off
echo [%date% %time%] STARTING USB PRINTER SERVICE >> C:\printfd\task.log

cd /d C:\printfd
call venv\Scripts\activate
venv\Scripts\python.exe C:\printfd\printer_service_usb_win.py >> C:\printfd\task.log 2>&1
