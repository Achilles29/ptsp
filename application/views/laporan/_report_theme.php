<style>
  .report-page {
    padding: 1.5rem;
  }

  .report-shell {
    display: grid;
    gap: 1.25rem;
  }

  .report-hero {
    padding: 1.5rem 1.65rem;
    border-radius: 28px;
    background:
      radial-gradient(circle at top left, rgba(73, 121, 199, .20), transparent 42%),
      linear-gradient(135deg, #0f2746 0%, #123d6b 48%, #1d6397 100%);
    color: #fff;
    box-shadow: 0 28px 60px rgba(15, 39, 70, .18);
  }

  .report-hero-grid {
    display: grid;
    grid-template-columns: minmax(0, 1.6fr) minmax(280px, .95fr);
    gap: 1.25rem;
    align-items: end;
  }

  .report-eyebrow {
    display: inline-flex;
    align-items: center;
    gap: .5rem;
    margin-bottom: .7rem;
    font-size: .79rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .08em;
    color: rgba(255, 255, 255, .76);
  }

  .report-title {
    margin: 0 0 .8rem;
    font-size: clamp(2rem, 2.6vw, 3rem);
    line-height: 1.08;
    color: #fff;
  }

  .report-subtitle {
    margin: 0;
    max-width: 58rem;
    color: rgba(255, 255, 255, .84);
    line-height: 1.7;
  }

  .report-summary {
    padding: 1.1rem 1.15rem;
    border-radius: 22px;
    background: rgba(255, 255, 255, .12);
    border: 1px solid rgba(255, 255, 255, .14);
    backdrop-filter: blur(12px);
  }

  .report-summary-title {
    margin: 0 0 .9rem;
    font-size: .94rem;
    font-weight: 700;
    color: rgba(255, 255, 255, .86);
  }

  .report-summary-list {
    display: grid;
    gap: .8rem;
  }

  .report-summary-item {
    display: flex;
    justify-content: space-between;
    gap: 1rem;
    padding-bottom: .75rem;
    border-bottom: 1px dashed rgba(255, 255, 255, .18);
  }

  .report-summary-item:last-child {
    padding-bottom: 0;
    border-bottom: 0;
  }

  .report-summary-item span {
    color: rgba(255, 255, 255, .74);
  }

  .report-summary-item strong {
    color: #fff;
    text-align: right;
  }

  .report-kpi-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: .95rem;
  }

  .report-kpi {
    padding: 1.05rem 1.1rem;
    border-radius: 22px;
    background: linear-gradient(180deg, #ffffff 0%, #f7fbff 100%);
    border: 1px solid rgba(148, 163, 184, .18);
    box-shadow: 0 16px 30px rgba(15, 23, 42, .05);
  }

  .report-kpi small {
    display: block;
    color: #64748b;
    font-size: .8rem;
    letter-spacing: .03em;
    text-transform: uppercase;
    margin-bottom: .45rem;
  }

  .report-kpi strong {
    display: block;
    font-size: 1.85rem;
    line-height: 1.1;
    color: #14233c;
  }

  .report-kpi span {
    display: block;
    margin-top: .25rem;
    color: #64748b;
    line-height: 1.5;
  }

  .report-card {
    border: 1px solid rgba(148, 163, 184, .18);
    border-radius: 28px;
    background: rgba(255, 255, 255, .96);
    box-shadow: 0 18px 36px rgba(15, 23, 42, .05);
    overflow: hidden;
  }

  .report-card-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    flex-wrap: wrap;
    gap: 1rem;
    padding: 1.2rem 1.35rem 0;
  }

  .report-card-title {
    margin: 0;
    font-size: 1.15rem;
    color: #14233c;
  }

  .report-card-note {
    margin-top: .3rem;
    color: #64748b;
    line-height: 1.6;
  }

  .report-card-body {
    padding: 1.25rem 1.35rem 1.35rem;
  }

  .report-filter {
    padding: 1.05rem;
    border-radius: 22px;
    background: linear-gradient(180deg, #f8fbff 0%, #f2f7ff 100%);
    border: 1px solid rgba(191, 219, 254, .7);
  }

  .report-filter .form-label {
    font-size: .85rem;
    font-weight: 700;
    color: #334155;
  }

  .report-filter .form-control,
  .report-filter .form-select {
    min-height: 45px;
    border-radius: 14px;
    border-color: #cbd5e1;
    box-shadow: none;
  }

  .report-filter .form-control:focus,
  .report-filter .form-select:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 .18rem rgba(59, 130, 246, .14);
  }

  .report-actions {
    display: flex;
    flex-wrap: wrap;
    gap: .7rem;
    align-items: center;
  }

  .report-btn-export {
    background: linear-gradient(135deg, #15803d, #16a34a);
    color: #fff;
    border: 0;
    border-radius: 999px;
    padding: .78rem 1.1rem;
    font-weight: 700;
    text-decoration: none;
    box-shadow: 0 14px 28px rgba(22, 163, 74, .18);
  }

  .report-btn-export:hover {
    color: #fff;
    transform: translateY(-1px);
  }

  .report-btn-primary {
    border-radius: 14px;
    min-height: 45px;
    font-weight: 700;
  }

  .report-table {
    margin-bottom: 0;
  }

  .report-table thead th {
    padding: .9rem .85rem;
    font-size: .78rem;
    letter-spacing: .04em;
    text-transform: uppercase;
    color: #475569;
    background: #eff6ff;
    border-bottom: 1px solid #dbeafe;
    white-space: nowrap;
  }

  .report-table tbody td {
    padding: .95rem .85rem;
    vertical-align: middle;
    color: #1e293b;
    border-bottom: 1px solid #eef2f7;
  }

  .report-table tbody tr:hover td {
    background: #f8fbff;
  }

  .report-pill {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: .35rem .7rem;
    border-radius: 999px;
    font-size: .75rem;
    font-weight: 700;
    line-height: 1;
  }

  .report-pill.success {
    background: #dcfce7;
    color: #166534;
  }

  .report-pill.warning {
    background: #fef3c7;
    color: #92400e;
  }

  .report-pill.danger {
    background: #fee2e2;
    color: #991b1b;
  }

  .report-pill.info {
    background: #dbeafe;
    color: #1d4ed8;
  }

  .report-pill.neutral {
    background: #e2e8f0;
    color: #334155;
  }

  .report-subrow {
    background: #f8fbff;
  }

  .report-subrow-content {
    display: grid;
    gap: .75rem;
  }

  .report-detail-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: .75rem;
  }

  .report-detail-box {
    padding: .85rem .95rem;
    border-radius: 18px;
    border: 1px solid rgba(191, 219, 254, .7);
    background: #fff;
  }

  .report-detail-box small {
    display: block;
    margin-bottom: .3rem;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: .04em;
    font-size: .72rem;
  }

  .report-detail-box strong,
  .report-detail-box div {
    color: #14233c;
    line-height: 1.6;
  }

  .report-empty {
    padding: 2rem 1rem;
    text-align: center;
    color: #64748b;
  }

  .report-pagination .pagination {
    margin-bottom: 0;
  }

  .report-pagination .page-link {
    border-radius: 12px;
    border-color: #dbeafe;
    color: #1d4ed8;
  }

  .report-pagination .page-item.active .page-link {
    background: linear-gradient(135deg, #2563eb, #3b82f6);
    border-color: transparent;
    color: #fff;
  }

  .report-footnote {
    color: #64748b;
    line-height: 1.6;
  }

  .report-chart-grid {
    display: grid;
    grid-template-columns: 1.3fr .9fr;
    gap: 1rem;
  }

  .report-chart-card {
    border: 1px solid rgba(191, 219, 254, .7);
    border-radius: 22px;
    background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
    padding: 1rem;
    min-height: 340px;
  }

  .report-chart-card h3 {
    margin: 0 0 .35rem;
    font-size: 1rem;
    color: #14233c;
  }

  .report-chart-card p {
    margin: 0 0 1rem;
    color: #64748b;
    line-height: 1.6;
  }

  .report-chart-box {
    min-height: 260px;
  }

  .report-grid-two {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
  }

  .report-mini-list {
    display: grid;
    gap: .75rem;
  }

  .report-mini-item {
    display: flex;
    justify-content: space-between;
    gap: 1rem;
    padding: .85rem .9rem;
    border-radius: 16px;
    background: #f8fbff;
    border: 1px solid #dbeafe;
  }

  .report-mini-item strong {
    color: #14233c;
  }

  .report-mini-item span {
    color: #64748b;
  }

  .report-heatmap {
    display: grid;
    gap: .45rem;
  }

  .report-heatmap-row {
    display: grid;
    grid-template-columns: 120px repeat(11, minmax(52px, 1fr));
    gap: .45rem;
    align-items: center;
  }

  .report-heatmap-label {
    font-weight: 700;
    color: #334155;
  }

  .report-heatmap-cell {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 48px;
    border-radius: 14px;
    border: 1px solid rgba(191, 219, 254, .7);
    background: #f8fbff;
    color: #0f172a;
    font-weight: 700;
  }

  .report-heatmap-cell.level-0 { background: #f8fafc; color: #94a3b8; }
  .report-heatmap-cell.level-1 { background: #e0f2fe; color: #0f766e; }
  .report-heatmap-cell.level-2 { background: #bfdbfe; color: #1d4ed8; }
  .report-heatmap-cell.level-3 { background: #93c5fd; color: #1e3a8a; }
  .report-heatmap-cell.level-4 { background: #60a5fa; color: #fff; }

  .report-stage-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
    gap: .85rem;
  }

  .report-stage {
    position: relative;
    padding: 1rem;
    border-radius: 20px;
    background: linear-gradient(180deg, #eff6ff 0%, #ffffff 100%);
    border: 1px solid #dbeafe;
  }

  .report-stage small {
    display: block;
    color: #64748b;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .04em;
    margin-bottom: .35rem;
  }

  .report-stage strong {
    display: block;
    font-size: 1.85rem;
    color: #14233c;
  }

  .report-stage span {
    color: #64748b;
  }

  .report-note-box {
    padding: .95rem 1rem;
    border-radius: 18px;
    background: #eef6ff;
    border: 1px solid #dbeafe;
    color: #36506d;
    line-height: 1.7;
  }

  @media (max-width: 991.98px) {
    .report-hero-grid {
      grid-template-columns: 1fr;
    }

    .report-chart-grid,
    .report-grid-two {
      grid-template-columns: 1fr;
    }
  }

  @media (max-width: 767.98px) {
    .report-page {
      padding: 1rem;
    }

    .report-hero {
      padding: 1.2rem;
      border-radius: 24px;
    }

    .report-card-header,
    .report-card-body {
      padding-left: 1rem;
      padding-right: 1rem;
    }

    .report-kpi-grid {
      grid-template-columns: 1fr 1fr;
    }

    .report-heatmap-row {
      grid-template-columns: 90px repeat(11, minmax(44px, 1fr));
      gap: .3rem;
    }

    .report-heatmap-cell {
      min-height: 40px;
      font-size: .78rem;
    }
  }
</style>
