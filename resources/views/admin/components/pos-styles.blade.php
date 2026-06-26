<style>
    .pos-section-title {
        font-size: .78rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .08em;
        color: #6c757d;
        border-bottom: 2px solid #e9ecef;
        padding-bottom: 6px;
        margin-bottom: 14px;
    }

    #tbl-items { min-width: 700px; }

    #tbl-items thead th {
        background: #f8f9fa;
        white-space: nowrap;
        font-size: .82rem;
        padding: 10px 12px;
    }

    #tbl-items tbody td {
        vertical-align: middle;
        padding: 8px 10px;
    }

    #tbl-items .input-group-sm .form-control { min-width: 52px; }
    #tbl-items .input-group-sm .btn { min-width: 30px; }

    .btn-remove-row {
        width: 32px;
        height: 32px;
        padding: 0;
    }

    .recap-line {
        display: flex;
        justify-content: space-between;
        padding: 5px 0;
    }

    .recap-line.total-final {
        font-size: 1.3rem;
        font-weight: 700;
        border-top: 2px solid #dee2e6;
        padding-top: 10px;
        margin-top: 5px;
    }

    .recap-line.solde {
        color: #dc3545;
        font-weight: 600;
    }

    .source-btn { cursor: pointer; }

    .source-btn.active { box-shadow: 0 0 0 2px #4e73df; }

    .caisse-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #f0f4ff;
        border-radius: 8px;
        padding: 4px 12px;
        font-size: .85rem;
        font-weight: 600;
        color: #4e73df;
    }

    .btn-xs {
        padding: 2px 7px;
        font-size: .75rem;
        line-height: 1.4;
        border-radius: 3px;
    }

    .type-disc-btn {
        height: 31px;
        padding: 0 8px;
        font-size: .75rem;
        font-weight: 600;
        line-height: 29px;
        border-radius: 0;
        border: 1px solid #ced4da !important;
        background: #f8f9fa !important;
        color: #495057 !important;
        box-shadow: none !important;
        transition: background .15s, color .15s, border-color .15s;
    }

    .type-disc-btn:first-child { border-radius: 0; }
    .type-disc-btn:last-child  { border-radius: 0 4px 4px 0; }

    .type-disc-btn.active-pct,
    .type-disc-btn.active-pct:focus,
    .type-disc-btn.active-pct:active {
        background: #4e73df !important;
        border-color: #4e73df !important;
        color: #fff !important;
        box-shadow: none !important;
    }

    .type-disc-btn.active-fixed,
    .type-disc-btn.active-fixed:focus,
    .type-disc-btn.active-fixed:active {
        background: #fd7e14 !important;
        border-color: #fd7e14 !important;
        color: #fff !important;
        box-shadow: none !important;
    }

    .global-disc-btn {
        font-size: .75rem;
        font-weight: 600;
        padding: 2px 8px;
    }

    .global-disc-btn.active-pct {
        background: #4e73df !important;
        border-color: #4e73df !important;
        color: #fff !important;
    }

    .global-disc-btn.active-fixed {
        background: #fd7e14 !important;
        border-color: #fd7e14 !important;
        color: #fff !important;
    }

    .client-found {
        border: 2px solid #28a745;
        border-radius: 10px;
        padding: 12px 16px;
        background: #f6fff8;
    }
</style>
