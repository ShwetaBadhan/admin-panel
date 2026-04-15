<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activate Your MLM Account</title>
      <!-- Style Css -->
   
    <style>

        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; background: #f5f7fa; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 30px auto; background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px 20px; text-align: center; }
        .header h1 { margin: 0; font-size: 24px; }
        .content { padding: 30px 20px; }
        .content p { margin: 15px 0; }
        .btn { display: inline-block; background: #667eea; color: #fff !important; padding: 14px 32px; text-decoration: none; border-radius: 8px; font-weight: 600; margin: 20px 0; }
        .btn:hover { background: #5568d3; }
        .expiry { background: #fff3cd; border-left: 4px solid #ffc107; padding: 12px 15px; margin: 20px 0; border-radius: 4px; font-size: 14px; }
        .footer { background: #f8f9fa; padding: 20px; text-align: center; font-size: 12px; color: #6c757d; border-top: 1px solid #e9ecef; }
        .footer a { color: #667eea; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>🎉 Welcome to VSRMLM!</h1>
        </div>

        <!-- Content -->
        <div class="content">
            <p>Hi <strong>{{ $firstName }}</strong>,</p>
            
            <p>Your MLM account has been successfully created with the following details:</p>
            
            <table style="width:100%; border-collapse:collapse; margin:20px 0;">
                <tr>
                    <td style="padding:8px 0; font-weight:600;">Username:</td>
                    <td style="padding:8px 0;">{{ $userName }}</td>
                </tr>
                <tr>
                    <td style="padding:8px 0; font-weight:600;">Email:</td>
                    <td style="padding:8px 0;">{{ $user->email }}</td>
                </tr>
            </table>

            <p>To activate your account and start building your binary tree, please click the button below:</p>
            
            <p style="text-align:center;">
                <a href="{{ $activationUrl }}" class="btn">Activate My Account</a>
            </p>

            <div class="expiry">
                ⏰ <strong>Security Notice:</strong> This activation link will expire in {{ $expiryHours }} hours.
            </div>

            <p>If the button doesn't work, copy and paste this link into your browser:</p>
            <p style="background:#f8f9fa; padding:10px; border-radius:4px; font-size:12px; word-break:break-all;">
                {{ $activationUrl }}
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>If you didn't create this account, please ignore this email.</p>
            <p>&copy; {{ date('Y') }} <strong>{{ config('app.name', 'VSRMLM') }}</strong>. All rights reserved.</p>
            <p><a href="{{ config('app.url') }}">{{ config('app.url') }}</a></p>
        </div>
    </div>
     <!-- Required vendors -->
    <script src="{{ url('vendor/global/global.min.js') }}"></script>
    <script src="{{ url('vendor/bootstrap-select/dist/js/bootstrap-select.min.js') }}"></script>
    <script src="{{ url('vendor/chart.js/chart.bundle.min.js') }}"></script>
    <script src="{{ url('vendor/owl-carousel/owl.carousel.js') }}"></script>

    <!-- Apex Chart -->
    <script src="{{ url('vendor/apexchart/apexchart.js') }}"></script>

    <!-- Dashboard 1 -->
    <script src="{{ url('js/dashboard/dashboard-1.js') }}"></script>
    <script src="{{ url('js/custom.min.js') }}"></script>
    <script src="{{ url('js/deznav-init.js') }}"></script>
    <script src="{{ url('js/demo.js') }}"></script>
    {{--
    <script src="{{ url('js/styleSwitcher.js') }}"></script> --}}
    {{-- summernote js --}}
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-bs4.min.js"></script>

    <!-- Datatable -->
    <script src="{{ url('vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ url('vendor/datatables/js/dataTables.responsive.min.js') }}"></script>
</body>
</html>