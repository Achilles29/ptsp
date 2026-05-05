from flask import Flask, request, jsonify, make_response
from escpos.printer import Usb
from datetime import datetime
import os
import usb.core

app = Flask(__name__)

# Jika mau pakai manual, isi VID/PID di sini.
# Jika None, akan auto-detect perangkat USB pertama yang ditemukan.
PRINTER_VID = 0x1fc9
PRINTER_PID = 0x2016

# Bisa override via environment (hex tanpa 0x juga boleh)
# Contoh: set PRINTER_VID=0416, set PRINTER_PID=5011
def _env_hex(name):
    val = os.getenv(name)
    if not val:
        return None
    val = val.strip().lower().replace("0x", "")
    try:
        return int(val, 16)
    except ValueError:
        return None

def _autodetect_usb():
    devices = list(usb.core.find(find_all=True))
    if not devices:
        return None, None, "Tidak ada perangkat USB terdeteksi"

    # Jika hanya 1 device, pakai itu
    if len(devices) == 1:
        d = devices[0]
        return d.idVendor, d.idProduct, None

    # Jika lebih dari 1, coba pilih device dengan class printer (0x07)
    for d in devices:
        try:
            if d.bDeviceClass == 0x07:
                return d.idVendor, d.idProduct, None
        except Exception:
            pass

    # Fallback: pakai device pertama
    d = devices[0]
    return d.idVendor, d.idProduct, None

@app.route('/print', methods=['POST', 'OPTIONS'])
def print_ticket():
    if request.method == 'OPTIONS':
        resp = make_response('', 200)
        resp.headers['Access-Control-Allow-Origin'] = '*'
        resp.headers['Access-Control-Allow-Headers'] = 'Content-Type'
        resp.headers['Access-Control-Allow-Methods'] = 'POST, OPTIONS'
        return resp

    data = request.json

    try:
        vid = _env_hex("PRINTER_VID") or PRINTER_VID
        pid = _env_hex("PRINTER_PID") or PRINTER_PID

        if vid is None or pid is None:
            vid, pid, err = _autodetect_usb()
            if err:
                return jsonify(success=False, message=err)

        p = Usb(vid, pid)
        try:
            layanan = data.get('layanan', '')
            nomor = data.get('nomor', '')
            loket = data.get('loket')
            sisa = data.get('sisa')

            now = datetime.now()
            hari_map = {
                0: "SENIN",
                1: "SELASA",
                2: "RABU",
                3: "KAMIS",
                4: "JUMAT",
                5: "SABTU",
                6: "MINGGU",
            }
            hari = hari_map.get(now.weekday(), "")
            waktu = f"{hari} {now.strftime('%d %b %Y').upper()} JAM {now.strftime('%H:%M:%S')}"
            sep = "-" * 32 + "\n"

            p.set(align='center', bold=True)
            p.text("MAL PELAYANAN PUBLIK\n")
            p.text("REMBANG\n")

            p.set(bold=False)
            p.text(sep)
            p.text(waktu + "\n\n")
            p.text("NOMOR ANTRIAN\n")

            # Paksa ukuran besar via ESC/POS (beberapa printer mengabaikan width/height dari library)
            # GS ! n => high nibble = width, low nibble = height
            p.set(bold=True)
            p._raw(b"\x1d\x21\x33")  # 4x4 (jika printer support)
            p.text(nomor + "\n")
            p._raw(b"\x1d\x21\x00")  # reset ukuran normal

            p.set(bold=False, width=1, height=1)
            if layanan:
                p.text("(" + layanan + ")\n")

            if loket:
                p.text("\nLOKET " + str(loket) + "\n")
            p.text("\nMohon Menunggu Hingga Nomor Di Panggil\n")
            if sisa is not None:
                p.text("Yang Belum Di Panggil : " + str(sisa) + " Pemohon\n")
            p.text("\n")

            p.cut()
        finally:
            try:
                p.close()
            except Exception:
                pass
        resp = jsonify(success=True)
        resp.headers['Access-Control-Allow-Origin'] = '*'
        return resp

    except Exception as e:
        resp = jsonify(success=False, message=str(e))
        resp.headers['Access-Control-Allow-Origin'] = '*'
        return resp

if __name__ == '__main__':
    # HTTPS untuk menghindari mixed-content di browser (halaman HTTPS)
    # Set env CERT_FILE & KEY_FILE jika ingin HTTPS.
    # Contoh: set CERT_FILE=C:\printfd\cert.pem
    #         set KEY_FILE=C:\printfd\key.pem
    cert_file = os.getenv("CERT_FILE")
    key_file = os.getenv("KEY_FILE")

    if cert_file and key_file:
        app.run(host="127.0.0.1", port=9100, ssl_context=(cert_file, key_file))
    else:
        app.run(host="127.0.0.1", port=9100)
