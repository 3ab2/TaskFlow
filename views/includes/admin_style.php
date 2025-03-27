<style>
    /* Navbar Styles */
    .navbar {
        background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%) !important;
        padding: 1rem 0;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    

    .navbar-brand {
        font-size: 1.5rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 10px;
        transition: all 0.3s ease;
        color: #fff !important;
    }

    .navbar-brand:hover {
        transform: scale(1.05);
    }

    .navbar-brand i {
        color: #00ff9d;
        font-size: 1.8rem;
    }

    .nav-link {
        position: relative;
        transition: all 0.3s ease;
        color: rgba(255,255,255,0.8) !important;
        padding: 0.5rem 1rem;
        margin: 0 0.2rem;
        border-radius: 5px;
    }

    li.nav-item {
        color: #000000 !important;
    }

    .nav-link:hover {
        color: #fff !important;
        background: rgba(255,255,255,0.1);
        transform: translateY(-2px);
    }

    .nav-link.active {
        color: #00ff9d !important;
        background: rgba(0,255,157,0.1);
    }

    .nav-link i {
        margin-right: 8px;
        font-size: 1.1rem;
    }

    /* Form Container Styles */
    .form-container {
        max-width: 800px;
        margin: 2rem auto;
        padding: 2rem;
        border-radius: 15px;
        box-shadow: 0 0 20px rgba(0,0,0,0.1);
        background: #fff;
    }

    /* Button Styles */
    .btn-primary {
        background: linear-gradient(135deg, #00ff9d 0%, #00b8ff 100%);
        border: none;
        padding: 0.8rem 1.5rem;
        border-radius: 25px;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,255,157,0.3);
    }

    .btn-outline-secondary {
        border-radius: 25px;
        padding: 0.8rem 1.5rem;
    }

    /* Card Styles */
    .card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 0 20px rgba(0,0,0,0.1);
        margin-bottom: 2rem;
    }

    .card-header {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 15px 15px 0 0 !important;
        color: #333 !important;
        padding: 1.5rem;
    }

    /* Table Styles */
    .table {
        margin-bottom: 0;
    }

    .table thead th {
        background: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
        font-weight: 600;
    }

    .table tbody tr:hover {
        background: rgba(0,255,157,0.05);
    }

    /* Form Control Styles */
    .form-control, .form-select {
        border-radius: 10px;
        padding: 0.8rem 1rem;
        border: 1px solid #dee2e6;
        transition: all 0.3s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: #00ff9d;
        box-shadow: 0 0 0 0.2rem rgba(0,255,157,0.25);
    }

    /* Tab Styles */
    .nav-tabs {
        border-bottom: 2px solid #dee2e6;
    }

    .nav-tabs .nav-link {
        color: #6c757d;
        border: none;
        padding: 1rem 2rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .nav-tabs .nav-link.active {
        color: #00ff9d;
        border-bottom: 2px solid #00ff9d;
    }

    /* Animation */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .form-container, .card {
        animation: fadeIn 0.5s ease forwards;
    }
</style> 