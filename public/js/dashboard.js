// API Configuration
        const API_BASE_URL = 'https://your-api-endpoint.com/api'; // Ganti dengan URL API Anda
        const API_HEADERS = {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer YOUR_API_TOKEN' // Ganti dengan token API Anda
        };

        // API Functions
        class ShippingAPI {
            static async get(endpoint) {
                try {
                    const response = await fetch(`${API_BASE_URL}${endpoint}`, {
                        method: 'GET',
                        headers: API_HEADERS
                    });
                    return await response.json();
                } catch (error) {
                    console.error('API GET Error:', error);
                    throw error;
                }
            }

            static async post(endpoint, data) {
                try {
                    const response = await fetch(`${API_BASE_URL}${endpoint}`, {
                        method: 'POST',
                        headers: API_HEADERS,
                        body: JSON.stringify(data)
                    });
                    return await response.json();
                } catch (error) {
                    console.error('API POST Error:', error);
                    throw error;
                }
            }

            static async put(endpoint, data) {
                try {
                    const response = await fetch(`${API_BASE_URL}${endpoint}`, {
                        method: 'PUT',
                        headers: API_HEADERS,
                        body: JSON.stringify(data)
                    });
                    return await response.json();
                } catch (error) {
                    console.error('API PUT Error:', error);
                    throw error;
                }
            }

            static async delete(endpoint) {
                try {
                    const response = await fetch(`${API_BASE_URL}${endpoint}`, {
                        method: 'DELETE',
                        headers: API_HEADERS
                    });
                    return await response.json();
                } catch (error) {
                    console.error('API DELETE Error:', error);
                    throw error;
                }
            }
        }

        // Form Templates
        const formTemplates = {
            packages: [
                { name: 'sender_id', label: 'Sender ID', type: 'text', required: true },
                { name: 'customer_id', label: 'Customer ID', type: 'text', required: true },
                { name: 'weight', label: 'Weight (kg)', type: 'number', required: true },
                { name: 'package_type', label: 'Package Type', type: 'select', options: ['Electronics', 'Documents', 'Clothing', 'Food', 'Others'], required: true },
                { name: 'shipping_cost', label: 'Shipping Cost ($)', type: 'number', step: '0.01', required: true }
            ],
            customers: [
                { name: 'name', label: 'Full Name', type: 'text', required: true },
                { name: 'email', label: 'Email Address', type: 'email', required: true },
                { name: 'phone_number', label: 'Phone Number', type: 'tel', required: true },
                { name: 'address', label: 'Address', type: 'textarea', required: true }
            ],
            senders: [
                { name: 'name', label: 'Full Name', type: 'text', required: true },
                { name: 'email', label: 'Email Address', type: 'email', required: true },
                { name: 'phone_number', label: 'Phone Number', type: 'tel', required: true },
                { name: 'address', label: 'Address', type: 'textarea', required: true }
            ],
            vendors: [
                { name: 'business_name', label: 'Business Name', type: 'text', required: true },
                { name: 'license_number', label: 'License Number', type: 'text', required: true },
                { name: 'address', label: 'Business Address', type: 'textarea', required: true }
            ],
            couriers: [
                { name: 'name', label: 'Courier Name', type: 'text', required: true },
                { name: 'license_number', label: 'License Number', type: 'text', required: true },
                { name: 'vehicle_type', label: 'Vehicle Type', type: 'select', options: ['Motorcycle', 'Car', 'Van', 'Truck'], required: true },
                { name: 'phone_number', label: 'Phone Number', type: 'tel', required: true }
            ],
            shipments: [
                { name: 'package_id', label: 'Package ID', type: 'text', required: true },
                { name: 'vendor_id', label: 'Vendor ID', type: 'text', required: true },
                { name: 'courier_id', label: 'Courier ID', type: 'text', required: true },
                { name: 'tracking_number', label: 'Tracking Number', type: 'text', required: true },
                { name: 'delivery_date', label: 'Expected Delivery Date', type: 'date', required: true },
                { name: 'status', label: 'Status', type: 'select', options: ['Pending', 'Shipped', 'In Transit', 'Delivered', 'Cancelled'], required: true }
            ]
        };

        function showSection(section) {
            // Remove active class from all nav items
            document.querySelectorAll('.nav-item').forEach(item => {
                item.classList.remove('active');
            });
            
            // Add active class to clicked item
            event.target.closest('.nav-item').classList.add('active');
            
            // Update page title and breadcrumb
            const titles = {
                dashboard: 'Dashboard Overview',
                packages: 'Package Management',
                shipments: 'Shipment Management',
                senders: 'Sender Management',
                customers: 'Customer Management',
                vendors: 'Vendor Management',
                couriers: 'Courier Management',
                settings: 'System Settings'
            };
            
            document.getElementById('pageTitle').textContent = titles[section];
            document.getElementById('breadcrumbPage').textContent = titles[section].replace(' Management', '').replace(' Overview', '');
            
            // Update content based on section
            updateContent(section);
            
            // Load data for the section
            if (section !== 'dashboard') {
                loadSectionData(section);
            }
        }

        // Load data from API
        async function loadSectionData(section) {
            const endpoints = {
                packages: '/packages',
                customers: '/customers',
                senders: '/senders',
                vendors: '/vendors',
                couriers: '/couriers',
                shipments: '/shipments'
            };

            try {
                showLoading(true);
                const data = await ShippingAPI.get(endpoints[section]);
                populateTable(section, data);
                showLoading(false);
            } catch (error) {
                showToast('Failed to load data', 'error');
                showLoading(false);
            }
        }

        // Add new item
        function openAddModal(section) {
            const modal = document.getElementById('modal');
            const modalTitle = document.getElementById('modalTitle');
            const formFields = document.getElementById('formFields');
            
            modalTitle.textContent = `Add New ${section.slice(0, -1)}`;
            
            // Generate form fields
            const template = formTemplates[section];
            if (template) {
                formFields.innerHTML = template.map(field => generateFormField(field)).join('');
            }
            
            modal.style.display = 'block';
            
            // Set form submit handler
            document.getElementById('dataForm').onsubmit = (e) => {
                e.preventDefault();
                submitForm(section);
            };
        }

        // Generate form field HTML
        function generateFormField(field) {
            let input = '';
            
            switch (field.type) {
                case 'select':
                    input = `<select name="${field.name}" ${field.required ? 'required' : ''}>
                        <option value="">Select ${field.label}</option>
                        ${field.options.map(opt => `<option value="${opt}">${opt}</option>`).join('')}
                    </select>`;
                    break;
                case 'textarea':
                    input = `<textarea name="${field.name}" rows="3" ${field.required ? 'required' : ''}></textarea>`;
                    break;
                default:
                    input = `<input type="${field.type}" name="${field.name}" ${field.step ? `step="${field.step}"` : ''} ${field.required ? 'required' : ''}>`;
            }
            
            return `
                <div class="form-field">
                    <label>${field.label} ${field.required ? '*' : ''}</label>
                    ${input}
                </div>
            `;
        }

        // Submit form data
        async function submitForm(section) {
            const form = document.getElementById('dataForm');
            const formData = new FormData(form);
            const data = Object.fromEntries(formData);
            
            const endpoints = {
                packages: '/packages',
                customers: '/customers',
                senders: '/senders',
                vendors: '/vendors',
                couriers: '/couriers',
                shipments: '/shipments'
            };

            try {
                showLoading(true);
                await ShippingAPI.post(endpoints[section], data);
                closeModal();
                loadSectionData(section); // Refresh data
                showToast(`${section.slice(0, -1)} added successfully!`, 'success');
                showLoading(false);
            } catch (error) {
                showToast('Failed to add item', 'error');
                showLoading(false);
            }
        }

        // Delete item
        async function deleteItem(section, id) {
            if (!confirm('Are you sure you want to delete this item?')) return;
            
            const endpoints = {
                packages: '/packages',
                customers: '/customers',
                senders: '/senders',
                vendors: '/vendors',
                couriers: '/couriers',
                shipments: '/shipments'
            };

            try {
                showLoading(true);
                await ShippingAPI.delete(`${endpoints[section]}/${id}`);
                loadSectionData(section); // Refresh data
                showToast('Item deleted successfully!', 'success');
                showLoading(false);
            } catch (error) {
                showToast('Failed to delete item', 'error');
                showLoading(false);
            }
        }

        // Utility functions
        function closeModal() {
            document.getElementById('modal').style.display = 'none';
        }

        function showLoading(show) {
            const content = document.getElementById('mainContent');
            if (show) {
                content.classList.add('loading');
            } else {
                content.classList.remove('loading');
            }
        }

        function showToast(message, type = 'success') {
            const toast = document.getElementById('toast');
            toast.textContent = message;
            toast.className = `toast ${type}`;
            toast.style.display = 'block';
            
            setTimeout(() => {
                toast.style.display = 'none';
            }, 3000);
        }

        function populateTable(section, data) {
            // This function would populate the table with API data
            // Implementation depends on your API response structure
            console.log(`Populating ${section} table with:`, data);
        }

        function updateContent(section) {
            const content = document.getElementById('mainContent');
            
            if (section === 'dashboard') {
                // Keep current dashboard content
                return;
            }
            
            // Create section-specific content
            const sectionContent = {
                packages: `
                    <div class="table-card">
                        <div class="table-header">
                            <h2 class="table-title">Package Management</h2>
                            <div class="table-actions">
                                <button class="btn btn-secondary">Filter</button>
                                <button class="btn btn-primary" onclick="openAddModal('packages')">+ Add Package</button>
                            </div>
                        </div>
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Package ID</th>
                                    <th>Sender</th>
                                    <th>Customer</th>
                                    <th>Weight</th>
                                    <th>Type</th>
                                    <th>Shipping Cost</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>PKG-2024-156</strong></td>
                                    <td>John Doe</td>
                                    <td>Jane Smith</td>
                                    <td>2.5 kg</td>
                                    <td>Electronics</td>
                                    <td>$25.50</td>
                                    <td><span class="status-badge status-shipped">Shipped</span></td>
                                    <td><div class="action-btn">⋯</div></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                `,
                customers: `
                    <div class="table-card">
                        <div class="table-header">
                            <h2 class="table-title">Customer Management</h2>
                            <div class="table-actions">
                                <button class="btn btn-secondary">Export</button>
                                <button class="btn btn-primary" onclick="openAddModal('customers')">+ Add Customer</button>
                            </div>
                        </div>
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Customer ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Address</th>
                                    <th>Total Orders</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>CST-001</strong></td>
                                    <td>Jane Smith</td>
                                    <td>jane@email.com</td>
                                    <td>+1234567890</td>
                                    <td>123 Main St, City</td>
                                    <td>15</td>
                                    <td><div class="action-btn">⋯</div></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                `,
                vendors: `
                    <div class="table-card">
                        <div class="table-header">
                            <h2 class="table-title">Vendor Management</h2>
                            <div class="table-actions">
                                <button class="btn btn-secondary">Filter</button>
                                <button class="btn btn-primary">+ Add Vendor</button>
                            </div>
                        </div>
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Vendor ID</th>
                                    <th>Business Name</th>
                                    <th>License Number</th>
                                    <th>Address</th>
                                    <th>Active Shipments</th>
                                    <th>Rating</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>VND-001</strong></td>
                                    <td>FastTrack Express</td>
                                    <td>FTE-2024-001</td>
                                    <td>456 Business Ave</td>
                                    <td>23</td>
                                    <td>4.8/5.0</td>
                                    <td><div class="action-btn">⋯</div></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                `
            };
            
            if (sectionContent[section]) {
                content.innerHTML = sectionContent[section];
            }
        }

        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('open');
        }

        // Search functionality
        document.querySelector('.search-input').addEventListener('input', function(e) {
            console.log('Searching for:', e.target.value);
            // Implement search functionality here
        });

        // Close sidebar on mobile when clicking outside
        document.addEventListener('click', function(e) {
            const sidebar = document.getElementById('sidebar');
            const mobileBtn = document.querySelector('.mobile-menu-btn');
            
            if (window.innerWidth <= 768 && 
                !sidebar.contains(e.target) && 
                !mobileBtn.contains(e.target)) {
                sidebar.classList.remove('open');
            }
        });