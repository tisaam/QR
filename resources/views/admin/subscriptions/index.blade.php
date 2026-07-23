@extends('layouts.admin')

@section('title', 'Subscriptions Data')

@section('content')

<style>
    .subscription-container{
        max-width:1200px;
        margin:auto;
        padding:30px 20px;
    }

    .page-header{
        display:flex;
        align-items:center;
        gap:15px;
        margin-bottom:25px;
    }

    .back-btn{
        color:#6b7280;
        font-size:20px;
        text-decoration:none;
        transition:.3s;
    }

    .back-btn:hover{
        color:#111827;
    }

    .page-title{
        font-size:28px;
        font-weight:700;
        color:#1f2937;
        margin:0;
    }

    .table-wrapper{
        background:#fff;
        border:1px solid #e5e7eb;
        border-radius:14px;
        overflow:hidden;
        box-shadow:0 8px 20px rgba(0,0,0,.05);
    }

    .subscription-table{
        width:100%;
        border-collapse:collapse;
    }

    .subscription-table thead{
        background:#f9fafb;
    }

    .subscription-table th{
        text-align:left;
        padding:16px 20px;
        font-size:12px;
        text-transform:uppercase;
        color:#6b7280;
        font-weight:700;
        letter-spacing:.5px;
        border-bottom:1px solid #e5e7eb;
    }

    .subscription-table td{
        padding:18px 20px;
        border-bottom:1px solid #f1f5f9;
        vertical-align:middle;
    }

    .subscription-table tbody tr:hover{
        background:#fafafa;
    }

    .business-name{
        font-weight:600;
        color:#111827;
    }

    .business-email{
        font-size:13px;
        color:#6b7280;
        margin-top:4px;
    }

    .plan-badge{
        display:inline-block;
        padding:8px 16px;
        border-radius:8px;
        background:#eef2ff;
        color:#4338ca;
        font-size:14px;
        font-weight:600;
    }

    .price{
        font-weight:700;
        color:#111827;
    }

    .status{
        display:inline-block;
        padding:7px 14px;
        border-radius:20px;
        font-size:12px;
        font-weight:600;
    }

    .status-active{
        background:#dcfce7;
        color:#15803d;
    }

    .status-trialing{
        background:#ffedd5;
        color:#c2410c;
    }

    .status-inactive{
        background:#fee2e2;
        color:#b91c1c;
    }

    .date{
        color:#4b5563;
        font-size:14px;
    }

    .empty-state{
        text-align:center;
        padding:50px 20px;
        color:#9ca3af;
    }

    .empty-state i{
        display:block;
        font-size:40px;
        margin-bottom:12px;
    }

    .pagination-wrapper{
        margin-top:25px;
        display:flex;
        justify-content:center;
    }

    /* Mobile Cards */

    .mobile-cards{
        display:none;
    }

    .subscription-card{
        background:#fff;
        border:1px solid #e5e7eb;
        border-radius:14px;
        padding:18px;
        margin-bottom:18px;
        box-shadow:0 4px 15px rgba(0,0,0,.05);
    }

    .card-row{
        display:flex;
        justify-content:space-between;
        margin-bottom:12px;
        gap:15px;
    }

    .card-label{
        color:#6b7280;
        font-size:13px;
        font-weight:600;
    }

    .card-value{
        text-align:right;
        font-size:14px;
    }

    @media(max-width:768px){

        .table-wrapper{
            display:none;
        }

        .mobile-cards{
            display:block;
        }

        .subscription-container{
            padding:20px 15px;
        }

        .page-title{
            font-size:22px;
        }
    }
/* ===================================
   SUBSCRIPTIONS PAGE DARK MODE
=================================== */

body:not(.light-mode) .subscription-container{
    color:#f8fafc;
}

body:not(.light-mode) .page-title{
    color:#f8fafc;
}

body:not(.light-mode) .back-btn{
    color:#cbd5e1;
}

body:not(.light-mode) .back-btn:hover{
    color:#ffffff;
}

body:not(.light-mode) .table-wrapper{
    background:#1e293b;
    border:1px solid #334155;
    box-shadow:none;
}

body:not(.light-mode) .subscription-table{
    background:#1e293b;
}

body:not(.light-mode) .subscription-table thead{
    background:#0f172a;
}

body:not(.light-mode) .subscription-table th{
    color:#cbd5e1;
    border-bottom:1px solid #334155;
}

body:not(.light-mode) .subscription-table td{
    background:#1e293b;
    color:#f8fafc;
    border-bottom:1px solid #334155;
}

body:not(.light-mode) .subscription-table tbody tr:hover{
    background:#273549;
}

body:not(.light-mode) .business-name{
    color:#ffffff;
}

body:not(.light-mode) .business-email{
    color:#94a3b8;
}

body:not(.light-mode) .plan-badge{
    background:#312e81;
    color:#c7d2fe;
}

body:not(.light-mode) .price{
    color:#ffffff;
}

body:not(.light-mode) .date{
    color:#cbd5e1;
}

body:not(.light-mode) .subscription-card{
    background:#1e293b;
    border:1px solid #334155;
    box-shadow:none;
}

body:not(.light-mode) .card-label{
    color:#94a3b8;
}

body:not(.light-mode) .card-value{
    color:#ffffff;
}

body:not(.light-mode) .card-value small{
    color:#94a3b8;
}

body:not(.light-mode) .empty-state{
    color:#94a3b8;
}
</style>

<div class="subscription-container">

    <div class="page-header">
        <a href="{{ route('admin.dashboard') }}" class="back-btn">
            <i class="fas fa-arrow-left"></i>
        </a>

        <h1 class="page-title">
            Subscription Details
        </h1>
    </div>

    <!-- Desktop Table -->

    <div class="table-wrapper">

        <table class="subscription-table">

            <thead>
                <tr>
                    <th>Business / User</th>
                    <th>Plan Purchased</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Purchased On</th>
                </tr>
            </thead>

            <tbody>

            @forelse($subscriptions as $sub)

                <tr>

                    <td>
                        <div class="business-name">
                            {{ $sub->business->name ?? 'N/A' }}
                        </div>

                        <div class="business-email">
                            {{ $sub->user->email }}
                        </div>
                    </td>

                    <td>
                        <span class="plan-badge">
                            {{ $sub->plan->name }}
                        </span>
                    </td>

                    <td class="price">
                        ${{ number_format($sub->plan->price,2) }}
                    </td>

                    <td>

                        <span class="status
                        {{ $sub->status=='active' ? 'status-active' : ($sub->status=='trialing' ? 'status-trialing' : 'status-inactive') }}">

                            {{ ucfirst($sub->status) }}

                        </span>

                    </td>

                    <td class="date">
                        {{ $sub->created_at->format('M d, Y h:i A') }}
                    </td>

                </tr>

            @empty

                <tr>
                    <td colspan="5">

                        <div class="empty-state">
                            <i class="fas fa-inbox"></i>
                            No one has purchased a plan yet.
                        </div>

                    </td>
                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

    <!-- Mobile Cards -->

    <div class="mobile-cards">

        @forelse($subscriptions as $sub)

        <div class="subscription-card">

            <div class="card-row">
                <div class="card-label">Business</div>
                <div class="card-value">
                    <strong>{{ $sub->business->name ?? 'N/A' }}</strong><br>
                    <small>{{ $sub->user->email }}</small>
                </div>
            </div>

            <div class="card-row">
                <div class="card-label">Plan</div>
                <div class="card-value">
                    <span class="plan-badge">
                        {{ $sub->plan->name }}
                    </span>
                </div>
            </div>

            <div class="card-row">
                <div class="card-label">Amount</div>
                <div class="card-value price">
                    ${{ number_format($sub->plan->price,2) }}
                </div>
            </div>

            <div class="card-row">
                <div class="card-label">Status</div>

                <div class="card-value">

                    <span class="status
                    {{ $sub->status=='active' ? 'status-active' : ($sub->status=='trialing' ? 'status-trialing' : 'status-inactive') }}">

                        {{ ucfirst($sub->status) }}

                    </span>

                </div>

            </div>

            <div class="card-row">
                <div class="card-label">Purchased</div>
                <div class="card-value">
                    {{ $sub->created_at->format('M d, Y h:i A') }}
                </div>
            </div>

        </div>

        @empty

        <div class="subscription-card">
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                No one has purchased a plan yet.
            </div>
        </div>

        @endforelse

    </div>

    <div class="pagination-wrapper">
        {{ $subscriptions->links() }}
    </div>

</div>

@endsection