<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Welcome' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .main-content {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .content-card {
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            text-align: center;
        }

        .modal-backdrop {
            background-color: rgba(0, 0, 0, 0.7);
        }

        .custom-modal {
            animation: slideDown 0.3s ease-out;
        }

        @keyframes slideDown {
            from {
                transform: translateY(-100px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
    </style>
</head>

<body>
    <div class="main-content">
        <div class="content-card">
            <h1>Welcome to Our Website</h1>
            <p class="mt-3">This is the main content of the page.</p>
            <button class="btn btn-primary mt-3" id="showModalBtn">
                Show Modal Again
            </button>
        </div>
    </div>

    <!-- Modal Structure -->
    <div class="modal fade" id="welcomeModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered custom-modal">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Welcome Message</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        <img src="https://cdn-icons-png.flaticon.com/512/5610/5610942.png"
                            alt="welcome"
                            style="width: 100px; margin-bottom: 20px;">
                        <h4>Hello, Welcome to Our Website!</h4>
                        <p class="mt-3">Thank you for visiting our website. We hope you enjoy your experience here.</p>
                        <div class="alert alert-info mt-3">
                            <small>This modal appears automatically when the page loads.</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="acceptBtn">Get Started</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Check if modal has been shown before using localStorage
        let modalShown = localStorage.getItem('modalShown');

        // Auto show modal when page loads
        document.addEventListener('DOMContentLoaded', function() {
            <?php if ($showModal ?? true): ?>
                // Show modal if not shown before or you can always show it
                if (!modalShown) {
                    var myModal = new bootstrap.Modal(document.getElementById('welcomeModal'));
                    myModal.show();
                    // Set flag bahwa modal sudah ditampilkan (optional)
                    // localStorage.setItem('modalShown', 'true');
                }
            <?php endif; ?>
        });

        // Handle Get Started button
        document.getElementById('acceptBtn').addEventListener('click', function() {
            var myModal = bootstrap.Modal.getInstance(document.getElementById('welcomeModal'));
            myModal.hide();
            // Show success notification
            alert('Welcome! Enjoy exploring our website.');
        });

        // Show modal manually with button
        document.getElementById('showModalBtn').addEventListener('click', function() {
            var myModal = new bootstrap.Modal(document.getElementById('welcomeModal'));
            myModal.show();
        });

        // Optional: Clear localStorage to test modal again
        // localStorage.removeItem('modalShown');
    </script>
</body>

</html>