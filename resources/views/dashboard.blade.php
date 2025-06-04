
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShipTrack Admin Panel</title>
    <link rel="stylesheet" href="css/dashboard.css">
    <style>
    </style>
</head>
<body>
    <div class="layout">
        <!-- Sidebar -->
        <div class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="logo">
                    <div class="logo-icon">🚚</div>
                    <span>Pengiriman Barang</span>
                </div>
            </div>

            <nav class="nav-menu">
                <div class="nav-section">
                    <div class="nav-section-title">Dashboard</div>
                    <a href="#" class="nav-item active" onclick="showSection('dashboard')">
                        <div class="nav-icon">📊</div>
                        <span>Overview</span>
                    </a>
                </div>

                <div class="nav-section">
                    <div class="nav-section-title">Package Management</div>
                    <a href="#" class="nav-item" onclick="showSection('packages')">
                        <div class="nav-icon">📦</div>
                        <span>Packages</span>
                    </a>
                    <a href="#" class="nav-item" onclick="showSection('shipments')">
                        <div class="nav-icon">🚛</div>
                        <span>Shipments</span>
                    </a>
                </div>

                <div class="nav-section">
                    <div class="nav-section-title">User Management</div>
                    <a href="#" class="nav-item" onclick="showSection('senders')">
                        <div class="nav-icon">👤</div>
                        <span>Senders</span>
                    </a>
                    <a href="#" class="nav-item" onclick="showSection('customers')">
                        <div class="nav-icon">👥</div>
                        <span>Customers</span>
                    </a>
                </div>

                <div class="nav-section">
                    <div class="nav-section-title">Service Providers</div>
                    <a href="#" class="nav-item" onclick="showSection('vendors')">
                        <div class="nav-icon">🏢</div>
                        <span>Vendors</span>
                    </a>
                    <a href="#" class="nav-item" onclick="showSection('couriers')">
                        <div class="nav-icon">🏃‍♂️</div>
                        <span>Couriers</span>
                    </a>
                </div>

                <div class="nav-section">
                    <div class="nav-section-title">Settings</div>
                    <a href="#" class="nav-item" onclick="showSection('settings')">
                        <div class="nav-icon">⚙️</div>
                        <span>System Settings</span>
                    </a>
                </div>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Header -->
            <header class="header">
                <div class="header-left">
                    <button class="mobile-menu-btn" onclick="toggleSidebar()">☰</button>
                    <div>
                        <h1 class="page-title" id="pageTitle">Dashboard Overview</h1>
                        <div class="breadcrumb">
                            <span>Home</span> / <span id="breadcrumbPage">Dashboard</span>
                        </div>
                    </div>
                </div>
                <div class="header-right">
                    <div class="search-box">
                        <input type="text" class="search-input" placeholder="Search packages, customers...">
                    </div>
                    <div class="user-menu">
                        <div class="user-avatar">AD</div>
                        <span>Admin User</span>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <main class="content" id="mainContent">
                <!-- Dashboard Stats -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-title">Total Packages</div>
                            <div class="stat-icon packages">📦</div>
                        </div>
                        <div class="stat-value">5,847</div>
                        <div class="stat-change positive">↗ +12% from last month</div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-title">Active Shipments</div>
                            <div class="stat-icon shipments">🚛</div>
                        </div>
                        <div class="stat-value">1,234</div>
                        <div class="stat-change positive">↗ +8% from last month</div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-title">Total Customers</div>
                            <div class="stat-icon customers">👥</div>
                        </div>
                        <div class="stat-value">12,905</div>
                        <div class="stat-change positive">↗ +15% from last month</div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-title">Active Vendors</div>
                            <div class="stat-icon vendors">🏢</div>
                        </div>
                        <div class="stat-value">89</div>
                        <div class="stat-change positive">↗ +3% from last month</div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-title">Total Couriers</div>
                            <div class="stat-icon couriers">🏃‍♂️</div>
                        </div>
                        <div class="stat-value">156</div>
                        <div class="stat-change negative">↘ -2% from last month</div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-title">Registered Senders</div>
                            <div class="stat-icon senders">👤</div>
                        </div>
                        <div class="stat-value">1,247</div>
                        <div class="stat-change positive">↗ +5% from last month</div>
                    </div>
                </div>

                <!-- Recent Shipments Table -->
                <div class="table-card">
                    <div class="table-header">
                        <h2 class="table-title">Recent Shipments</h2>
                        <div class="table-actions">
                            <button class="btn btn-secondary">Export</button>
                            <button class="btn btn-primary">+ New Shipment</button>
                        </div>
                    </div>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Shipment ID</th>
                                <th>Package</th>
                                <th>Sender</th>
                                <th>Customer</th>
                                <th>Courier</th>
                                <th>Status</th>
                                <th>Delivery Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>SHP-2024-001</strong></td>
                                <td>PKG-2024-156</td>
                                <td>John Doe</td>
                                <td>Jane Smith</td>
                                <td>FastTrack Express</td>
                                <td><span class="status-badge status-shipped">Shipped</span></td>
                                <td>2024-06-03</td>
                                <td>
                                    <div class="action-menu">
                                        <div class="action-btn">⋯</div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>SHP-2024-002</strong></td>
                                <td>PKG-2024-157</td>
                                <td>Alice Johnson</td>
                                <td>Bob Wilson</td>
                                <td>QuickShip Logistics</td>
                                <td><span class="status-badge status-pending">Pending</span></td>
                                <td>2024-06-04</td>
                                <td>
                                    <div class="action-menu">
                                        <div class="action-btn">⋯</div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>SHP-2024-003</strong></td>
                                <td>PKG-2024-158</td>
                                <td>Michael Brown</td>
                                <td>Sarah Davis</td>
                                <td>Lightning Delivery</td>
                                <td><span class="status-badge status-delivered">Delivered</span></td>
                                <td>2024-06-01</td>
                                <td>
                                    <div class="action-menu">
                                        <div class="action-btn">⋯</div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>SHP-2024-004</strong></td>
                                <td>PKG-2024-159</td>
                                <td>Emily Chen</td>
                                <td>David Miller</td>
                                <td>ExpressWay Couriers</td>
                                <td><span class="status-badge status-cancelled">Cancelled</span></td>
                                <td>-</td>
                                <td>
                                    <div class="action-menu">
                                        <div class="action-btn">⋯</div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>

    <!-- Modal for Add/Edit Forms -->
    <div id="modal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modalTitle">Add New Item</h3>
                <span class="close" onclick="closeModal()">&times;</span>
            </div>
            <div class="modal-body">
                <form id="dataForm">
                    <div id="formFields"></div>
                    <div class="form-actions">
                        <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
    </style>

    <div id="toast" class="toast"></div>
    <script src="js/dashboard.js"></script>
</body>
</html>