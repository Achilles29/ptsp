import time
import logging
import os
import json
import threading
import platform
import usb.core
from datetime import datetime
from flask import Flask, request, jsonify
from flask_cors import CORS
from escpos.printer import Usb

# ============================================================
# CONFIG
# ============================================================
APP_DIR = os.path.dirname(os.path.abspath(__file__))

logging.basicConfig(
    filename=os.path.join(APP_DIR, "printer_daemon_usb.log"),
    level=logging.INFO,
    format="%(asctime)s [%(levelname)s] %(message)s"
)

print = lambda *a, **k: logging.info(" ".join(map(str, a)))

with open(os.path.join(APP_DIR, "config_usb.json")) as f:
    CONFIG = json.load(f)

PRINTERS = CONFIG["printers"]  # list: lokasi, vid, pid, port

# ============================================================
# AUTO DETECT USB (VID/PID kosong -> pilih printer pertama)
# ============================================================
def autodetect_usb():
    devices = list(usb.core.find(find_all=True))
    if not devices:
        return None, None

    if len(devices) == 1:
        d = devices[0]
        return d.idVendor, d.idProduct

    # coba pilih class printer
    for d in devices:
        try:
            if d.bDeviceClass == 0x07:
                return d.idVendor, d.idProduct
        except Exception:
            pass

    d = devices[0]
    return d.idVendor, d.idProduct

# ============================================================
# SAFE PRINT (AUTO RECONNECT)
# ============================================================
def safe_print(vid, pid, payload):
    while True:
        try:
            p = Usb(vid, pid)
            p.set(align='center', bold=True)
            p.text("MAL PELAYANAN PUBLIK\n")
            p.text("KABUPATEN REMBANG\n\n")

            p.set(bold=False)
            p.text(payload.get("layanan", "") + "\n\n")

            p.set(bold=True, width=2, height=2)
            p.text(payload.get("nomor", "") + "\n\n")

            p.set(bold=False, width=1, height=1)
            p.text(datetime.now().strftime("%d/%m/%Y %H:%M") + "\n")
            p.text("Silakan menunggu panggilan\n\n\n")

            p.cut()
            print(f"✔ Cetak berhasil VID={hex(vid)} PID={hex(pid)}")
            return True

        except Exception as e:
            print(f"❌ Gagal cetak: {e}")
            print("🔁 Mencoba reconnect printer...")
            time.sleep(2)

# ============================================================
# START FLASK
# ============================================================
def start_flask_server(lokasi, vid, pid, http_port):
    app = Flask(lokasi)
    CORS(app)

    @app.route("/print", methods=["POST"])
    def cetak():
        data = request.get_json(force=True)
        if not data:
            return jsonify({"success": False, "message": "Payload kosong"}), 400

        success = safe_print(vid, pid, data)
        if success:
            return jsonify({"success": True}), 200
        return jsonify({"success": False, "message": "Gagal cetak"}), 500

    print(f"▶ Printer {lokasi} jalan di port {http_port} (VID={hex(vid)} PID={hex(pid)})")
    app.run(host="127.0.0.1", port=http_port, debug=False, use_reloader=False)

# ============================================================
# MAIN
# ============================================================
def main():
    print("🔥 START USB DAEMON — AUTO-DETECT + AUTO-RECONNECT")

    for p in PRINTERS:
        lokasi = p.get("lokasi", "PRINTER")
        http_port = p.get("port", 9100)
        vid = p.get("vid")
        pid = p.get("pid")

        if not vid or not pid:
            vid, pid = autodetect_usb()

        if not vid or not pid:
            print(f"❌ Printer {lokasi} tidak ditemukan")
            continue

        t = threading.Thread(
            target=start_flask_server,
            args=(lokasi, int(vid), int(pid), http_port),
            daemon=True
        )
        t.start()

        print(f"✔ Printer {lokasi} siap — VID={hex(int(vid))} PID={hex(int(pid))} | HTTP {http_port}")

    while True:
        time.sleep(1)

if __name__ == "__main__":
    main()
