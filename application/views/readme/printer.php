<div class="container-xxl py-4">
    <div class="d-flex flex-wrap align-items-center justify-content-between mb-3">
        <div>
            <h4 class="text-maroon mb-1">
                <i class="ri ri-printer-cloud-line me-2"></i><?= $title ?>
            </h4>
            <p class="text-muted mb-0">Panduan lengkap untuk setup mini PC Ubuntu + printer thermal.</p>
        </div>
        <div class="d-flex gap-2 mt-2 mt-md-0">
            <a class="btn btn-outline-primary"
                href="<?= base_url('readme/download/ubuntu-printer'); ?>">
                <i class="ri ri-download-2-line me-1"></i>Download Panduan
            </a>
            <a class="btn btn-outline-secondary"
                href="<?= base_url('readme/download/thermal-server'); ?>">
                <i class="ri ri-file-code-line me-1"></i>Download thermal_server.py
            </a>
            <a class="btn btn-outline-secondary"
                href="<?= base_url('readme/download/service-ubuntu'); ?>">
                <i class="ri ri-settings-3-line me-1"></i>Download ptsp-printer.service
            </a>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header bg-maroon text-white">
                    <strong><i class="ri ri-link-m me-1"></i>Link Download</strong>
                </div>
                <div class="card-body">
                    <div class="mb-2 fw-semibold">Paket Ubuntu (APT)</div>
                    <div class="d-flex flex-column gap-1">
                        <a href="https://packages.ubuntu.com/search?keywords=python3-venv" target="_blank">python3-venv</a>
                        <a href="https://packages.ubuntu.com/search?keywords=python3-pip" target="_blank">python3-pip</a>
                        <a href="https://packages.ubuntu.com/search?keywords=libusb-1.0-0" target="_blank">libusb-1.0-0</a>
                        <a href="https://packages.ubuntu.com/search?keywords=usbutils" target="_blank">usbutils</a>
                    </div>

                    <hr>

                    <div class="mb-2 fw-semibold">Paket Python (pip)</div>
                    <div class="d-flex flex-column gap-1">
                        <a href="https://pypi.org/project/Flask/" target="_blank">Flask</a>
                        <a href="https://pypi.org/project/python-escpos/" target="_blank">python-escpos</a>
                        <a href="https://pypi.org/project/pyusb/" target="_blank">pyusb</a>
                    </div>

                    <hr>

                    <div class="mb-2 fw-semibold">Referensi</div>
                    <div class="d-flex flex-column gap-1">
                        <a href="https://github.com/python-escpos/python-escpos" target="_blank">python-escpos</a>
                        <a href="https://github.com/pyusb/pyusb" target="_blank">pyusb</a>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mt-3">
                <div class="card-header bg-light">
                    <strong><i class="ri ri-attachment-2 me-1"></i>File Lokal</strong>
                </div>
                <div class="card-body d-flex flex-column gap-2">
                    <a class="btn btn-sm btn-outline-primary"
                        href="<?= base_url('readme/download/ubuntu-printer'); ?>">
                        README_UBUNTU_PRINTER.md
                    </a>
                    <a class="btn btn-sm btn-outline-primary"
                        href="<?= base_url('readme/download/thermal-server'); ?>">
                        thermal_server.py
                    </a>
                    <a class="btn btn-sm btn-outline-primary"
                        href="<?= base_url('readme/download/service-ubuntu'); ?>">
                        ptsp-printer.service
                    </a>
                    <a class="btn btn-sm btn-outline-primary"
                        href="<?= base_url('readme/download/printer-win-simple'); ?>">
                        printer_win_simple.py
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <strong><i class="ri ri-book-2-line me-1"></i>Isi Panduan</strong>
                </div>
                <div class="card-body">
                    <div id="readme-content" class="markdown-body"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .text-maroon {
        color: #7a003c;
    }

    .bg-maroon {
        background: #7a003c;
    }

    .markdown-body h1,
    .markdown-body h2,
    .markdown-body h3 {
        color: #7a003c;
        margin-top: 1.2rem;
    }

    .markdown-body pre {
        background: #0f172a;
        color: #e2e8f0;
        padding: 12px;
        border-radius: 8px;
        overflow-x: auto;
        font-size: 0.9rem;
    }

    .markdown-body code {
        background: #f3f4f6;
        padding: 2px 6px;
        border-radius: 6px;
        font-size: 0.88rem;
    }

    .markdown-body pre code {
        background: transparent;
        padding: 0;
    }

    .markdown-body hr {
        border-top: 1px solid #e5e7eb;
        margin: 1.2rem 0;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
<script>
    (function () {
        const md = <?= json_encode($readme_md ?? '', JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?>;
        const target = document.getElementById('readme-content');
        if (!target) return;
        if (!md || !md.trim()) {
            target.innerHTML = '<p class=\"text-muted\">Dokumen panduan kosong.</p>';
            return;
        }

        marked.setOptions({
            mangle: false,
            headerIds: false
        });

        target.innerHTML = marked.parse(md);
    })();
</script>
