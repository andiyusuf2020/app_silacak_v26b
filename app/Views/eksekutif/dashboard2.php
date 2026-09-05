<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard SiTAPIS - Provinsi Lampung</title>
    <link rel="stylesheet" href="<?php echo base_url('cssportal/templatemo-graph-page.css'); ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css"
        integrity="sha256-4MX+61mt9NVvvuPjUWdUdyfZfxSB1/Rf9WtqRHgG5S0="
        crossorigin="anonymous" />
</head>

<body>
    <!-- Navigation -->
    <nav id="navbar">
        <div class="nav-container">
            <a href="#home" class="logo">
                <div class="logo-icon">
                    <svg viewBox="0 0 24 24">
                        <path d="M3 13h2v8H3zm4-8h2v13H7zm4-2h2v15h-2zm4 4h2v11h-2zm4-2h2v13h-2z" />

                    </svg>
                </div>
                <span class="logo-text">Dashboard SiTAPIS</span>
            </a>
            <ul class="nav-links">
                <li><a href="#home" class="active">Home</a></li>
                <li><a href="#dashboard">Dashboard</a></li>
                <li><a href="#analytics">Analytics</a></li>
                <li><a href="#reports">Reports</a></li>
                <li><a href="#contact">Contact</a></li>
            </ul>
            <!-- <a href="https://www.google.com/search" target="_blank" rel="noopener" title="Search">
                <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"></circle>
                    <path d="m21 21-4.35-4.35"></path>
                </svg>
            </a> -->
            <div class="hamburger" id="hamburger">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
        <ul class="nav-links-mobile" id="navLinksMobile">
            <li><a href="#home" class="active">Home</a></li>
            <li><a href="#dashboard">Dashboard</a></li>
            <li><a href="#analytics">Analytics</a></li>
            <li><a href="#reports">Reports</a></li>
            <li><a href="#contact">Contact</a></li>
        </ul>
    </nav>

    <!-- Hero Section -->
    <section class="hero" id="home">
        <div class="hero-bg"></div>
        <div class="geometric-shapes">
            <div class="shape shape1"></div>
            <div class="shape shape2"></div>
            <div class="shape shape3"></div>
            <div class="shape shape4"></div>
            <div class="shape shape5"></div>
            <div class="shape shape6"></div>


        </div>

        <div class="hero-content">
            <div class="hero-text">
                <img src="<?= base_url() ?>cssportal/img_home/lampung.png" width="20%" />

                <h1>Data Analytics<br>Executive Dashboard</h1>
                <p>Penyampaian Resume Data Pembangunan Provinsi Lampung dari Sistem Data Pengadalian dan Informasi (SiTAPIS)</p>
                <a href="#dashboard" class="cta-button">Get Started</a>

            </div>
            <div class="hero-visual">
                <div class="city-container">
                    <div class="building building1">
                        <div class="building-fill"></div>
                        <div class="building-windows"></div>
                    </div>
                    <div class="building building2">
                        <div class="building-fill"></div>
                        <div class="building-windows"></div>
                    </div>
                    <div class="building building3">
                        <div class="building-fill"></div>
                        <div class="building-windows"></div>
                    </div>
                    <div class="building building4">
                        <div class="building-fill"></div>
                        <div class="building-windows"></div>
                    </div>
                    <div class="neon-line neon-line1"></div>
                    <div class="neon-line neon-line2"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Dashboard Section -->
    <section class="dashboard-section" id="dashboard">
        <div class="dashboard-container">
            <h2 class="section-title">Dashboard Overview</h2>
            <!-- Stats Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon">📊</div>
                        <div class="stat-title">Sasaran Pembangunan</div>
                    </div>
                    <div class="stat-value">$42,847</div>
                    <div class="stat-description">Monthly revenue increased by 23% compared to last month with strong performance across all channels.</div>
                    <div class="stat-chart">
                        fgfdg
                        <canvas class="mini-chart" id="miniChart1"></canvas>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon">📊</div>
                        <div class="stat-title">Program Unggulan Pembangunan</div>
                    </div>
                    <div class="stat-value">$42,847</div>
                    <div class="stat-description">Monthly revenue increased by 23% compared to last month with strong performance across all channels.</div>
                    <div class="stat-chart">
                        fgfdg
                        <canvas class="mini-chart" id="miniChart1"></canvas>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon">📊</div>
                        <div class="stat-title">Program Tematik Pembangunan</div>
                    </div>
                    <div class="stat-value">$42,847</div>
                    <div class="stat-description">Monthly revenue increased by 23% compared to last month with strong performance across all channels.</div>
                    <div class="stat-chart">
                        fgfdg
                        <canvas class="mini-chart" id="miniChart1"></canvas>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon">👥</div>
                        <div class="stat-title">Active Users</div>
                    </div>
                    <div class="stat-value">18.5K</div>
                    <div class="stat-description">Real-time analytics showing active users currently engaging with the platform.</div>
                    <div class="stat-chart">
                        <canvas class="mini-chart" id="miniChart2"></canvas>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon"><i class="bi bi-bank2"></i></div>
                        <div class="stat-title">Conversion Rate</div>
                    </div>
                    <div class="stat-value">94.3%</div>
                    <div class="stat-description">Customer satisfaction rate based on recent surveys and feedback analysis.</div>
                    <div class="stat-chart">
                        <canvas class="mini-chart" id="miniChart3"></canvas>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon">🚀</div>
                        <div class="stat-title">Performance Score</div>
                    </div>
                    <div class="stat-value">7,392</div>
                    <div class="stat-description">Overall system performance metrics showing optimal operation across all services.</div>
                    <div class="stat-chart">
                        <canvas class="mini-chart" id="miniChart4"></canvas>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon">💰</div>
                        <div class="stat-title">Monthly Growth</div>
                    </div>
                    <div class="stat-value">+28.5%</div>
                    <div class="stat-description">Consistent month-over-month growth in user acquisition and revenue generation.</div>
                    <div class="stat-chart">
                        <canvas class="mini-chart" id="miniChart5"></canvas>
                    </div>
                    sdfsdf
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon">⚡</div>
                        <div class="stat-title">System Uptime</div>
                    </div>
                    <div class="stat-value">99.9%</div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th style="width: 10px">#</th>
                                    <th>Task</th>
                                    <th>Progress</th>
                                    <th style="width: 40px">Label</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="align-middle">
                                    <td>1.</td>
                                    <td>Update software</td>
                                    <td>
                                        <div class="progress progress-xs">
                                            <div
                                                class="progress-bar progress-bar-danger"
                                                style="width: 55%"></div>
                                        </div>
                                    </td>
                                    <td><span class="badge text-bg-danger">55%</span></td>
                                </tr>
                                <tr class="align-middle">
                                    <td>2.</td>
                                    <td>Clean database</td>
                                    <td>
                                        <div class="progress progress-xs">
                                            <div class="progress-bar text-bg-warning" style="width: 70%"></div>
                                        </div>
                                    </td>
                                    <td><span class="badge text-bg-warning">70%</span></td>
                                </tr>
                                <tr class="align-middle">
                                    <td>3.</td>
                                    <td>Cron job running</td>
                                    <td>
                                        <div class="progress progress-xs progress-striped active">
                                            <div class="progress-bar text-bg-primary" style="width: 30%"></div>
                                        </div>
                                    </td>
                                    <td><span class="badge text-bg-primary">30%</span></td>
                                </tr>
                                <tr class="align-middle">
                                    <td>4.</td>
                                    <td>Fix and squish bugs</td>
                                    <td>
                                        <div class="progress progress-xs progress-striped active">
                                            <div class="progress-bar text-bg-success" style="width: 90%"></div>
                                        </div>
                                    </td>
                                    <td><span class="badge text-bg-success">90%</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Analytics Section -->
    <section class="analytics-section" id="analytics">
        <div class="dashboard-container">
            <h2 class="section-title">Advanced Analytics</h2>

            <!-- Key Metrics Overview -->
            <div class="metrics-grid">
                <div class="metric-item">
                    <div class="metric-value">2.4M</div>
                    <div class="metric-label">Page Views</div>
                </div>
                <div class="metric-item">
                    <div class="metric-value">156K</div>
                    <div class="metric-label">Unique Visitors</div>
                </div>
                <div class="metric-item">
                    <div class="metric-value">4.2min</div>
                    <div class="metric-label">Avg Session</div>
                </div>
                <div class="metric-item">
                    <div class="metric-value">68%</div>
                    <div class="metric-label">Return Rate</div>
                </div>
                <div class="metric-item">
                    <div class="metric-value">89</div>
                    <div class="metric-label">NPS Score</div>
                </div>
                <div class="metric-item">
                    <div class="metric-value">3.2K</div>
                    <div class="metric-label">Daily Active</div>
                </div>
            </div>

            <!-- Chart Cards -->
            <div class="charts-grid">
                <div class="chart-card">
                    <div class="chart-header">
                        <h3 class="chart-title">📈 Monthly Trends</h3>
                        <div class="chart-options">
                            <span class="chart-option active">2024</span>
                            <span class="chart-option">2023</span>
                            <span class="chart-option">2022</span>
                        </div>
                    </div>
                    <div class="chart-container">
                        <div class="bar-chart" id="barChart">
                            <div class="bar" style="height: 10%">
                                <span class="bar-value">10</span>
                                <span class="bar-label">Jan</span>
                            </div>
                            <div class="bar" style="height: 80%">
                                <span class="bar-value">180</span>
                                <span class="bar-label">Feb</span>
                            </div>
                            <div class="bar" style="height: 45%">
                                <span class="bar-value">90</span>
                                <span class="bar-label">Mar</span>
                            </div>
                            <div class="bar" style="height: 70%">
                                <span class="bar-value">140</span>
                                <span class="bar-label">Apr</span>
                            </div>
                            <div class="bar" style="height: 90%">
                                <span class="bar-value">200</span>
                                <span class="bar-label">May</span>
                            </div>
                            <div class="bar" style="height: 65%">
                                <span class="bar-value">130</span>
                                <span class="bar-label">Jun</span>
                            </div>
                            <div class="bar" style="height: 75%">
                                <span class="bar-value">150</span>
                                <span class="bar-label">Jul</span>
                            </div>
                            <div class="bar" style="height: 85%">
                                <span class="bar-value">170</span>
                                <span class="bar-label">Aug</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="chart-card">
                    <div class="chart-header">
                        <h3 class="chart-title">📊 Growth Analytics</h3>
                        <div class="chart-options">
                            <span class="chart-option active">Week</span>
                            <span class="chart-option">Month</span>
                            <span class="chart-option">Year</span>
                        </div>
                    </div>
                    <div class="chart-container">
                        <div class="bar-chart" id="barChart">
                            <div class="bar" style="height: 10%">
                                <span class="bar-value">10</span>
                                <span class="bar-label">Jan</span>
                            </div>
                            <div class="bar" style="height: 80%">
                                <span class="bar-value">180</span>
                                <span class="bar-label">Feb</span>
                            </div>
                            <div class="bar" style="height: 45%">
                                <span class="bar-value">90</span>
                                <span class="bar-label">Mar</span>
                            </div>
                            <div class="bar" style="height: 70%">
                                <span class="bar-value">140</span>
                                <span class="bar-label">Apr</span>
                            </div>
                            <div class="bar" style="height: 90%">
                                <span class="bar-value">200</span>
                                <span class="bar-label">May</span>
                            </div>
                            <div class="bar" style="height: 65%">
                                <span class="bar-value">130</span>
                                <span class="bar-label">Jun</span>
                            </div>
                            <div class="bar" style="height: 75%">
                                <span class="bar-value">150</span>
                                <span class="bar-label">Jul</span>
                            </div>
                            <div class="bar" style="height: 85%">
                                <span class="bar-value">170</span>
                                <span class="bar-label">Aug</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="chart-card">
                    <div class="chart-header">
                        <h3 class="chart-title">🌍 Geographic Distribution</h3>
                        <div class="chart-options">
                            <span class="chart-option active">Global</span>
                            <span class="chart-option">US</span>
                            <span class="chart-option">EU</span>
                        </div>
                    </div>
                    <div class="chart-container">
                        <div class="bar-chart">
                            <div class="bar" style="height: 85%; background: linear-gradient(180deg, #ff6b6b 0%, #ff8e53 100%);">
                                <span class="bar-value">42%</span>
                                <span class="bar-label">USA</span>
                            </div>
                            <div class="bar" style="height: 65%; background: linear-gradient(180deg, #4ecdc4 0%, #44a08d 100%);">
                                <span class="bar-value">28%</span>
                                <span class="bar-label">EU</span>
                            </div>
                            <div class="bar" style="height: 45%; background: linear-gradient(180deg, #45b7d1 0%, #96c93d 100%);">
                                <span class="bar-value">18%</span>
                                <span class="bar-label">Asia</span>
                            </div>
                            <div class="bar" style="height: 25%; background: linear-gradient(180deg, #f093fb 0%, #f5576c 100%);">
                                <span class="bar-value">12%</span>
                                <span class="bar-label">Other</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="chart-card">
                    <div class="chart-header">
                        <h3 class="chart-title">📱 Device Analytics</h3>
                        <div class="chart-options">
                            <span class="chart-option active">This Month</span>
                            <span class="chart-option">Last Month</span>
                            <span class="chart-option">YTD</span>
                        </div>
                    </div>
                    <div class="chart-container">
                        <div id="sales-chart"></div>
                        <script
                            src="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.min.js"
                            integrity="sha256-+vh8GkaU7C9/wbSLIcwq82tQ2wTf44aOHA8HlBMwRI8="
                            crossorigin="anonymous"></script>
                        <script>
                            const sales_chart_options = {
                                series: [{
                                        name: 'Net Profit',
                                        data: [44, 55, 57, 56, 61, 58, 63, 60, 66],
                                    },
                                    {
                                        name: 'Revenue',
                                        data: [76, 85, 101, 98, 87, 105, 91, 114, 94],
                                    },
                                    {
                                        name: 'Free Cash Flow',
                                        data: [35, 41, 36, 26, 45, 48, 52, 53, 41],
                                    },
                                ],
                                chart: {
                                    type: 'bar',
                                    height: 200,
                                },
                                plotOptions: {
                                    bar: {
                                        horizontal: false,
                                        columnWidth: '55%',
                                        endingShape: 'rounded',
                                    },
                                },
                                legend: {
                                    show: false,
                                },
                                colors: ['#0d6efd', '#20c997', '#ffc107'],
                                dataLabels: {
                                    enabled: false,
                                },
                                stroke: {
                                    show: true,
                                    width: 2,
                                    colors: ['transparent'],
                                },
                                xaxis: {
                                    categories: ['Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct'],
                                },
                                fill: {
                                    opacity: 1,
                                },
                                tooltip: {
                                    y: {
                                        formatter: function(val) {
                                            return '$ ' + val + ' thousands';
                                        },
                                    },
                                },
                            };

                            const sales_chart = new ApexCharts(
                                document.querySelector('#sales-chart'),
                                sales_chart_options,
                            );
                            sales_chart.render();
                        </script>

                    </div>
                </div>
            </div>
        </div>
        </div>
    </section>

    <!-- Reports Section -->
    <section class="reports-section" id="reports">
        <div class="dashboard-container">
            <h2 class="section-title">Reports & Insights</h2>
            <div class="info-grid">
                <div class="info-card">
                    <div class="info-icon">💼</div>
                    <h3 class="info-title">Business Intelligence</h3>
                    <div class="info-value">98.5%</div>
                    <p style="font-size: 14px; color: #a0a0a0;">Accuracy in predictive analytics and business forecasting models.</p>
                </div>
                <div class="info-card">
                    <div class="info-icon">📱</div>
                    <h3 class="info-title">Mobile Analytics</h3>
                    <div class="info-value">2.4M</div>
                    <p style="font-size: 14px; color: #a0a0a0;">Mobile app downloads and active user engagement metrics.</p>
                </div>
                <div class="info-card">
                    <div class="info-icon">🌍</div>
                    <h3 class="info-title">Global Reach</h3>
                    <div class="info-value">150+</div>
                    <p style="font-size: 14px; color: #a0a0a0;">Countries actively using our analytics platform worldwide.</p>
                </div>
                <div class="info-card">
                    <div class="info-icon">🚀</div>
                    <h3 class="info-title">Performance Index</h3>
                    <div class="info-value">847</div>
                    <p style="font-size: 14px; color: #a0a0a0;">Comprehensive performance scoring across all platform metrics.</p>
                </div>
                <div class="info-card">
                    <div class="info-icon">⚡</div>
                    <h3 class="info-title">Response Time</h3>
                    <div class="info-value">0.2s</div>
                    <p style="font-size: 14px; color: #a0a0a0;">Average API response time ensuring optimal user experience.</p>
                </div>
                <div class="info-card">
                    <div class="info-icon">📊</div>
                    <h3 class="info-title">Data Processing</h3>
                    <div class="info-value">12TB</div>
                    <p style="font-size: 14px; color: #a0a0a0;">Daily data volume processed through our analytics pipeline.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="contact-section" id="contact">
        <div class="dashboard-container">
            <h2 class="section-title">Get In Touch</h2>
            <div class="contact-grid">
                <!-- Contact Form -->
                <div class="contact-form">
                    <h3 style="margin-bottom: 30px; font-size: 24px;">Send us a Message</h3>
                    <form id="contactForm">
                        <div class="form-group">
                            <label for="name">Full Name</label>
                            <input type="text" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="subject">Subject</label>
                            <input type="text" id="subject" name="subject" required>
                        </div>
                        <div class="form-group">
                            <label for="message">Message</label>
                            <textarea id="message" name="message" required placeholder="Tell us how we can help you..."></textarea>
                        </div>
                        <button type="submit" class="cta-button" style="width: 100%;">Send Message</button>
                    </form>
                </div>

                <!-- Contact Info -->
                <div class="contact-info">
                    <h3>Contact Information</h3>

                    <a href="mailto:hello@graphpage.com" class="contact-item" style="text-decoration: none; color: inherit;">
                        <div class="contact-icon">📧</div>
                        <div class="contact-details">
                            <h4>Email Address</h4>
                            <p>hello@graphpage.com<br>support@graphpage.com</p>
                        </div>
                    </a>

                    <a href="tel:+15551234567" class="contact-item" style="text-decoration: none; color: inherit;">
                        <div class="contact-icon">📞</div>
                        <div class="contact-details">
                            <h4>Phone Number</h4>
                            <p>+1 (555) 123-4567<br>Available 24/7</p>
                        </div>
                    </a>

                    <a href="https://maps.google.com/?q=123+Data+Drive+Suite+100+Analytics+City" target="_blank" rel="noopener" class="contact-item" style="text-decoration: none; color: inherit;">
                        <div class="contact-icon">📍</div>
                        <div class="contact-details">
                            <h4>Office Location</h4>
                            <p>123 Data Drive, Suite 100<br>Analytics City, AC 12345</p>
                        </div>
                    </a>

                    <div class="contact-item">
                        <div class="contact-icon">🕒</div>
                        <div class="contact-details">
                            <h4>Business Hours</h4>
                            <p>Monday - Friday: 9:00 AM - 6:00 PM<br>Weekend: Emergency support only</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <p class="copyright">© 2026 Graph Page. All rights reserved. Transforming data into insights.
                | Designed by <a href="https://templatemo.com" rel="nofollow noopener" target="_blank">TemplateMo</a></p>
        </div>
    </footer>

    <script src="<?php echo base_url('cssportal/templatemo-graph-script.js'); ?>"></script>
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
        integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
        crossorigin="anonymous"></script>

</body>

</html>