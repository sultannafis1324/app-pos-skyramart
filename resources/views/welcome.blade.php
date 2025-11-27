<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome SkyraMart</title>
    <link rel="icon" href="{{ asset('images/Skyra-L1.png') }}" type="image/png">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #4A90E2 0%, #5AB9EA 100%);
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Animated background particles */
        .particles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 0;
        }

        .particle {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 15s infinite;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0) translateX(0) rotate(0deg);
                opacity: 0;
            }
            10% {
                opacity: 1;
            }
            90% {
                opacity: 1;
            }
            100% {
                transform: translateY(-100vh) translateX(100px) rotate(360deg);
                opacity: 0;
            }
        }

        /* Header */
        header {
            position: relative;
            z-index: 10;
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            animation: slideDown 0.8s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
            color: white;
            font-size: 24px;
            font-weight: bold;
        }

        .logo-icon {
            width: 40px;
            height: 40px;
            background: white;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }

        .nav-buttons {
            display: flex;
            gap: 15px;
        }

        .btn {
            padding: 12px 28px;
            border-radius: 25px;
            border: none;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-outline {
            background: transparent;
            border: 2px solid white;
            color: white;
        }

        .btn-outline:hover {
            background: white;
            color: #4A90E2;
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(255,255,255,0.3);
        }

        .btn-solid {
            background: white;
            color: #4A90E2;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }

        .btn-solid:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 25px rgba(0,0,0,0.3);
        }

        /* Main Content */
        .container {
            position: relative;
            z-index: 10;
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 60px;
            min-height: calc(100vh - 160px);
        }

        .content {
            flex: 1;
            animation: slideRight 1s ease;
        }

        @keyframes slideRight {
            from {
                opacity: 0;
                transform: translateX(-100px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .content h1 {
            font-size: 56px;
            color: white;
            margin-bottom: 20px;
            line-height: 1.2;
        }

        .content h1 .highlight {
            background: linear-gradient(120deg, #FFD700 0%, #FFA500 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .content p {
            font-size: 18px;
            color: rgba(255,255,255,0.9);
            margin-bottom: 30px;
            line-height: 1.6;
        }

        .features {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-bottom: 40px;
        }

        .feature-item {
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(10px);
            padding: 15px 20px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            gap: 12px;
            border: 1px solid rgba(255,255,255,0.2);
            transition: all 0.3s ease;
            animation: fadeInUp 1s ease;
            animation-fill-mode: both;
        }

        .feature-item:nth-child(1) { animation-delay: 0.2s; }
        .feature-item:nth-child(2) { animation-delay: 0.3s; }
        .feature-item:nth-child(3) { animation-delay: 0.4s; }
        .feature-item:nth-child(4) { animation-delay: 0.5s; }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .feature-item:hover {
            background: rgba(255,255,255,0.2);
            transform: translateX(5px);
        }

        .feature-icon {
            font-size: 24px;
            width: 40px;
            height: 40px;
            background: white;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .feature-text {
            color: white;
            font-weight: 500;
            font-size: 14px;
        }

        .cta-buttons {
            display: flex;
            gap: 20px;
            animation: fadeInUp 1s ease 0.6s both;
        }

        .btn-primary {
            background: linear-gradient(135deg, #4A90E2 0%, #5AB9EA 100%);
            color: white;
            padding: 16px 40px;
            font-size: 16px;
            border: 2px solid white;
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }

        .btn-secondary {
            background: rgba(255,255,255,0.2);
            backdrop-filter: blur(10px);
            color: white;
            padding: 16px 40px;
            font-size: 16px;
            border: 2px solid rgba(255,255,255,0.3);
        }

        .btn-secondary:hover {
            background: rgba(255,255,255,0.3);
        }

        /* Dashboard Preview */
        .dashboard-preview {
            flex: 1;
            animation: slideLeft 1s ease;
            position: relative;
        }

        @keyframes slideLeft {
            from {
                opacity: 0;
                transform: translateX(100px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .preview-card {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            position: relative;
            animation: float-card 6s ease-in-out infinite;
        }

        @keyframes float-card {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-20px);
            }
        }

        .preview-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .preview-title {
            font-size: 20px;
            font-weight: bold;
            color: #333;
        }

        .preview-date {
            font-size: 14px;
            color: #666;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-bottom: 25px;
        }

        .stat-card {
            background: linear-gradient(135deg, #4A90E2 0%, #5AB9EA 100%);
            padding: 20px;
            border-radius: 15px;
            color: white;
            animation: pulse-stat 2s ease-in-out infinite;
        }

        .stat-card:nth-child(2) {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            animation-delay: 0.5s;
        }

        .stat-card:nth-child(3) {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            animation-delay: 1s;
        }

        .stat-card:nth-child(4) {
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            animation-delay: 1.5s;
        }

        @keyframes pulse-stat {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
        }

        .stat-label {
            font-size: 12px;
            opacity: 0.9;
            margin-bottom: 5px;
        }

        .stat-value {
            font-size: 24px;
            font-weight: bold;
        }

        .chart-placeholder {
            background: #f5f5f5;
            border-radius: 15px;
            height: 150px;
            display: flex;
            align-items: flex-end;
            padding: 20px;
            gap: 10px;
        }

        .chart-bar {
            flex: 1;
            background: linear-gradient(180deg, #4A90E2 0%, #5AB9EA 100%);
            border-radius: 5px;
            animation: grow-bar 1.5s ease-out;
        }

        @keyframes grow-bar {
            from {
                transform: scaleY(0);
            }
            to {
                transform: scaleY(1);
            }
        }

        .chart-bar:nth-child(1) { height: 60%; animation-delay: 0.1s; transform-origin: bottom; }
        .chart-bar:nth-child(2) { height: 80%; animation-delay: 0.2s; transform-origin: bottom; }
        .chart-bar:nth-child(3) { height: 50%; animation-delay: 0.3s; transform-origin: bottom; }
        .chart-bar:nth-child(4) { height: 90%; animation-delay: 0.4s; transform-origin: bottom; }
        .chart-bar:nth-child(5) { height: 70%; animation-delay: 0.5s; transform-origin: bottom; }

        /* Responsive */
        @media (max-width: 1024px) {
            .container {
                flex-direction: column;
                text-align: center;
                padding: 20px;
            }

            .content h1 {
                font-size: 42px;
            }

            .features {
                grid-template-columns: 1fr;
            }

            .cta-buttons {
                justify-content: center;
                flex-wrap: wrap;
            }
        }

        @media (max-width: 768px) {
            header {
                padding: 15px 20px;
                flex-direction: column;
                gap: 20px;
            }

            .content h1 {
                font-size: 32px;
            }

            .content p {
                font-size: 16px;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .nav-buttons {
                flex-direction: column;
                width: 100%;
            }

            .btn {
                width: 100%;
            }

            .cta-buttons {
                flex-direction: column;
                width: 100%;
            }

            .btn-primary, .btn-secondary {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <!-- Animated Particles Background -->
    <div class="particles" id="particles"></div>

    <!-- Header -->
    <header>
        <div class="logo">
            <div class="logo-icon">🛒</div>
            <span>SkyraMart POS</span>
        </div>
        <nav class="nav-buttons">
            <a href="/login" class="btn btn-outline">Login</a>
            <!-- <a href="/register" class="btn btn-solid">Register</a> -->
        </nav>
    </header>

    <!-- Main Content -->
    <div class="container">
        <div class="content">
            <h1>Modern <span class="highlight">Point of Sale</span> System</h1>
            <p>Manage your retail business with ease and efficiency. SkyraMart POS comes with comprehensive features to help boost your productivity and sales performance.</p>
            
            <div class="features">
                <div class="feature-item">
                    <div class="feature-icon">⚡</div>
                    <span class="feature-text">Fast Transactions</span>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">📊</div>
                    <span class="feature-text">Real-time Reporting</span>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">📦</div>
                    <span class="feature-text">Inventory Management</span>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">💳</div>
                    <span class="feature-text">Multiple Payments</span>
                </div>
            </div>

            <div class="cta-buttons">
                <a href="/dashboard" class="btn btn-primary">Get Started</a>
                <a href="#" class="btn btn-secondary">Learn More</a>
            </div>
        </div>

        <div class="dashboard-preview">
            <div class="preview-card">
                <div class="preview-header">
                    <div class="preview-title">Dashboard Overview</div>
                    <div class="preview-date" id="currentDate">Today</div>
                </div>
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-label">Total Sales</div>
                        <div class="stat-value">$12.5M</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-label">Transactions</div>
                        <div class="stat-value">1,234</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-label">Products Sold</div>
                        <div class="stat-value">3,456</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-label">Net Profit</div>
                        <div class="stat-value">$4.2M</div>
                    </div>
                </div>
                <div class="chart-placeholder">
                    <div class="chart-bar"></div>
                    <div class="chart-bar"></div>
                    <div class="chart-bar"></div>
                    <div class="chart-bar"></div>
                    <div class="chart-bar"></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Generate animated particles
        const particlesContainer = document.getElementById('particles');
        for (let i = 0; i < 30; i++) {
            const particle = document.createElement('div');
            particle.className = 'particle';
            particle.style.width = Math.random() * 5 + 2 + 'px';
            particle.style.height = particle.style.width;
            particle.style.left = Math.random() * 100 + '%';
            particle.style.animationDelay = Math.random() * 15 + 's';
            particle.style.animationDuration = (Math.random() * 10 + 10) + 's';
            particlesContainer.appendChild(particle);
        }

        // Fetch real dashboard data from API
        async function loadDashboardData() {
            try {
                // Fetch sales data
                const salesResponse = await fetch('/api/sales');
                const sales = await salesResponse.json();
                
                // Calculate statistics
                const completedSales = sales.data ? sales.data.filter(s => s.status === 'completed') : [];
                const today = new Date().toISOString().split('T')[0];
                const todaySales = completedSales.filter(s => s.sale_date.startsWith(today));
                
                const totalSales = todaySales.reduce((sum, sale) => sum + parseFloat(sale.total_price || 0), 0);
                const totalTransactions = todaySales.length;
                
                // Calculate products sold
                let totalProducts = 0;
                todaySales.forEach(sale => {
                    if (sale.sale_details) {
                        totalProducts += sale.sale_details.reduce((sum, detail) => sum + detail.quantity, 0);
                    }
                });
                
                // Calculate profit (simplified - using 30% margin estimate)
                const estimatedProfit = totalSales * 0.3;
                
                // Update UI
                document.querySelectorAll('.stat-value')[0].textContent = formatCurrency(totalSales);
                document.querySelectorAll('.stat-value')[1].textContent = totalTransactions.toLocaleString('en-US');
                document.querySelectorAll('.stat-value')[2].textContent = totalProducts.toLocaleString('en-US');
                document.querySelectorAll('.stat-value')[3].textContent = formatCurrency(estimatedProfit);
                
                // Update chart with last 5 days data
                updateChart(completedSales);
                
            } catch (error) {
                console.error('Error loading dashboard data:', error);
                // Keep default values if API fails
            }
        }
        
        function formatCurrency(amount) {
            return '$' + (amount / 1000000).toFixed(1) + 'M';
        }
        
        function updateChart(sales) {
            // Get last 5 days
            const days = [];
            for (let i = 4; i >= 0; i--) {
                const date = new Date();
                date.setDate(date.getDate() - i);
                days.push(date.toISOString().split('T')[0]);
            }
            
            // Calculate sales per day
            const dailySales = days.map(day => {
                const daySales = sales.filter(s => s.sale_date.startsWith(day));
                return daySales.reduce((sum, sale) => sum + parseFloat(sale.total_price || 0), 0);
            });
            
            // Find max for scaling
            const maxSales = Math.max(...dailySales, 1);
            
            // Update chart bars
            const bars = document.querySelectorAll('.chart-bar');
            bars.forEach((bar, index) => {
                if (dailySales[index] !== undefined) {
                    const height = (dailySales[index] / maxSales) * 100;
                    bar.style.height = Math.max(height, 10) + '%';
                }
            });
        }
        
        // Update current date display
        function updateCurrentDate() {
            const options = { 
                weekday: 'long', 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric' 
            };
            const dateStr = new Date().toLocaleDateString('en-US', options);
            document.getElementById('currentDate').textContent = dateStr;
        }
        
        // Initial load
        updateCurrentDate();
        loadDashboardData();
        
        // Refresh every 30 seconds
        setInterval(loadDashboardData, 30000);
    </script>
</body>
</html>