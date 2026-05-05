from flask import Flask, request, jsonify, make_response
import win32print
import win32api
from datetime import datetime

app = Flask(__name__)

# GANTI DENGAN NAMA PRINTER DI WINDOWS
PRINTER_NAME = "pos"

def raw_print(text):
    hPrinter = win32print.OpenPrinter(PRINTER_NAME)
    try:
        hJob = win32print.StartDocPrinter(hPrinter, 1, ("Ticket", None, "RAW"))
        win32print.StartPagePrinter(hPrinter)
        # Init ESC/POS + encoding aman untuk printer thermal
        text = "\x1b\x40" + text
        win32print.WritePrinter(hPrinter, text.encode("cp437", errors="ignore"))
        win32print.EndPagePrinter(hPrinter)
        win32print.EndDocPrinter(hPrinter)
    finally:
        win32print.ClosePrinter(hPrinter)

@app.route("/print", methods=["POST", "OPTIONS"])
def print_ticket():
    if request.method == "OPTIONS":
        resp = make_response("", 200)
        resp.headers["Access-Control-Allow-Origin"] = "*"
        resp.headers["Access-Control-Allow-Headers"] = "Content-Type"
        resp.headers["Access-Control-Allow-Methods"] = "POST, OPTIONS"
        return resp

    data = request.json
    if not data:
        return jsonify(success=False, message="Payload kosong")

    layanan = data.get("layanan", "")
    nomor = data.get("nomor", "")
    waktu = datetime.now().strftime("%d/%m/%Y %H:%M")

    # ESC/POS styling
    # Align center: ESC a 1
    # Bold on/off: ESC E n
    # Double size: GS ! 0x11
    text = ""
    text += "\x1b\x61\x01"          # center
    text += "\x1b\x45\x01"          # bold on
    text += "MAL PELAYANAN PUBLIK\n"
    text += "KABUPATEN REMBANG\n"
    text += "\x1b\x45\x00"          # bold off
    text += "------------------------------\n"
    text += layanan + "\n"
    text += "------------------------------\n"
    text += "\x1d\x21\x11"          # double size
    text += nomor + "\n"
    text += "\x1d\x21\x00"          # normal size
    text += "------------------------------\n"
    text += waktu + "\n"
    text += "Silakan menunggu panggilan\n"
    text += "\n\n"
    text += "\x1d\x56\x00"          # cut

    raw_print(text)
    resp = jsonify(success=True)
    resp.headers["Access-Control-Allow-Origin"] = "*"
    return resp

if __name__ == "__main__":
    app.run(host="127.0.0.1", port=9100)
