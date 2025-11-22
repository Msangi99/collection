<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Transactions - HIGHLINK ISGC</title>
    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <!-- Bootstrap Icons CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <!-- DataTables CSS CDN -->
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <style>
        .search-input {
            width: 100%;
            padding: 4px;
            font-size: 12px;
        }
        .card {
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            padding: 15px;
            border-bottom: 1px solid #e9ecef;
        }
        .table th, .table td {
            vertical-align: middle;
            border: 1px solid #dee2e6; /* Ensure borders for PDF */
            padding: 8px;
            text-align: left;
        }
        #transactionsTable {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }
        #transactionsTable thead {
            background-color: #f8f9fa;
        }
        .form-control-sm, .btn-sm {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
        }
        .dataTables_paginate .pagination .page-item .page-link {
            font-size: 0.75rem;
            padding: 0.15rem 0.3rem;
            margin: 0 0.05rem;
            min-width: 1.1rem;
            text-align: center;
            border-radius: 0.15rem;
            color: #6c757d;
        }
        .dataTables_paginate .pagination .page-item.active .page-link {
            background-color: #6c757d;
            border-color: #6c757d;
        }
        .dataTables_paginate .pagination {
            margin-top: 0.2rem;
            display: flex;
            justify-content: center;
        }
    </style>
</head>
<body>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-3">
                        <h5 class="mb-0">Payment Transactions</h5>
                        <h4 class="mb-0 fw-bold">HIGHLINK ISGC</h4>
                        <div>
                            <span class="badge bg-light text-dark fw-bold ms-3">Total Amount: Tsh {{ number_format($data['transactions']->sum('amount'), 2, '.', ',') }}</span>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped align-middle mb-0" id="transactionsTable">
                                <thead class="table-light"> 
                                     <tr>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bold">Company</th>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bold">User</th>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bold">Amount</th>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bold">Date</th>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bold">Status</th>
                                    </tr>
                                </thead>
                                <tbody id="transactionsTableBody">
                                    @forelse ($data['transactions'] as $transaction)
                                        <tr>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">
                                                    {{ auth()->user()->campany->name }}</p>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">
                                                    {{ $transaction->user ? $transaction->user->name : 'Unknown' }}</p>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0 amount"
                                                    data-amount="{{ $transaction->amount }}">
                                                    Tsh {{ number_format($transaction->amount, 2, '.', ',') }}</p>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">
                                                    {{ $transaction->created_at ?? 'Unknown' }}</p>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge badge-sm {{ $transaction->status === 'Completed' ? 'bg-success' : ($transaction->status === 'Pending' ? 'bg-warning' : 'bg-danger') }}">
                                                    {{ $transaction->status }}
                                                </span>
                                            </td> 
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-4">No transactions found.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>