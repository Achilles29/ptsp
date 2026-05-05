# Panduan Print Otomatis PTSP di Mini PC Ubuntu (USB Thermal)

Panduan ini untuk mini PC Ubuntu yang hanya dipakai untuk browser dan cetak tiket otomatis.
Endpoint lokal: `http://127.0.0.1:9100/print`.

## Ringkas
1. Hubungkan printer USB dan cek `VID`/`PID`.
2. Install dependency Ubuntu + Python.
3. Jalankan service lokal `thermal_server.py`.
4. Browser memanggil `/print` setelah pilih layanan.

---

## A. Identifikasi printer USB
Colokkan printer, lalu cek:
```bash
lsusb
```
Contoh output:
```
Bus 001 Device 005: ID 28e9:0289 GDMicroelectronics micro-printer
```
Berarti:
- `VID=28e9`
- `PID=0289`

## B. Install dependency (Ubuntu)
```bash
sudo apt update
sudo apt install -y python3-venv python3-pip libusb-1.0-0 usbutils
```

## C. Siapkan folder service lokal
```bash
sudo mkdir -p /opt/ptsp-print
sudo cp /www/wwwroot/ptsp/readme/thermal_server.py /opt/ptsp-print/thermal_server.py
sudo chown -R $USER:$USER /opt/ptsp-print
```

## D. Install library Python
```bash
cd /opt/ptsp-print
python3 -m venv venv
./venv/bin/pip install flask python-escpos pyusb
```

## E. Udev rule agar USB bisa diakses user
Ganti `idVendor` dan `idProduct` sesuai `lsusb`:
```bash
sudo tee /etc/udev/rules.d/99-pos-printer.rules >/dev/null <<'EOF_UDEV'
SUBSYSTEM=="usb", ATTR{idVendor}=="28e9", ATTR{idProduct}=="0289", MODE="0666", GROUP="plugdev"
EOF_UDEV
```
Reload rules:
```bash
sudo udevadm control --reload-rules
sudo udevadm trigger
sudo usermod -aG plugdev $USER
```
Logout/login sekali agar group aktif.

## F. Test manual
```bash
cd /opt/ptsp-print
PRINTER_VID=28e9 PRINTER_PID=0289 ./venv/bin/python thermal_server.py
```
Tes cetak:
```bash
curl -X POST http://127.0.0.1:9100/print \
  -H 'Content-Type: application/json' \
  -d '{"layanan":"TES LAYANAN","nomor":"A001"}'
```

## G. Jadikan service auto-start
File service siap download di halaman `/readme/printer` (nama file: `ptsp-printer.service`).

```bash
sudo tee /etc/systemd/system/ptsp-printer.service >/dev/null <<'EOF_SERVICE'
[Unit]
Description=PTSP USB Printer Service
After=network.target

[Service]
Type=simple
User=root
WorkingDirectory=/opt/ptsp-print
ExecStart=/opt/ptsp-print/venv/bin/python /opt/ptsp-print/thermal_server.py
Restart=on-failure

[Install]
WantedBy=multi-user.target
EOF_SERVICE
```
Karena mini PC tidak login user (kiosk), service dijalankan sebagai `root`.

Aktifkan:
```bash
sudo systemctl daemon-reload
sudo systemctl enable --now ptsp-printer
sudo systemctl status ptsp-printer
```

## H. Integrasi di browser (auto print)
Panggil endpoint lokal saat pilih layanan:
```
POST http://127.0.0.1:9100/print
Content-Type: application/json
{"layanan":"NAMA LAYANAN","nomor":"A123"}
```

---

## Catatan HTTPS (jika web utama pakai HTTPS)
Browser bisa memblok request ke `http://127.0.0.1` (mixed content).
Solusi:
1. Jalankan service lokal dengan HTTPS.
2. Gunakan endpoint `https://127.0.0.1:9100/print`.

Bisa pakai sertifikat lokal, lalu set:
```
CERT_FILE=/opt/ptsp-print/cert.pem
KEY_FILE=/opt/ptsp-print/key.pem
```

---

## Troubleshooting
- Printer tidak terdeteksi: pastikan `lsusb` muncul.
- Error `Resource busy`: driver `usblp` bentrok.
  ```bash
  sudo modprobe -r usblp
  ```
- Cek log service:
  ```bash
  journalctl -u ptsp-printer -f
  ```

---

## Link file yang diunduh
### Paket Ubuntu (APT)
- https://packages.ubuntu.com/search?keywords=python3-venv
- https://packages.ubuntu.com/search?keywords=python3-pip
- https://packages.ubuntu.com/search?keywords=libusb-1.0-0
- https://packages.ubuntu.com/search?keywords=usbutils

### Paket Python (pip)
- https://pypi.org/project/Flask/
- https://pypi.org/project/python-escpos/
- https://pypi.org/project/pyusb/

### Referensi
- https://github.com/python-escpos/python-escpos
- https://github.com/pyusb/pyusb
