@extends('layouts.revamp')
@section('title', 'Cookie Policy')
@section('content')

    <style>
        .cookie-policy-card {
            background: #fff;
            padding: 28px 32px;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.05);
        }

        .cookie-policy-card h2 {
            margin-top: 26px;
            margin-bottom: 10px;
            font-size: 24px;
            color: #172033;
        }

        .cookie-policy-card p,
        .cookie-policy-card li {
            color: #555;
            font-size: 16px;
            line-height: 1.7;
        }

        .cookie-policy-card ul {
            padding-left: 20px;
        }

        .cookie-category-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 14px;
            margin-bottom: 22px;
        }

        .cookie-category-table th,
        .cookie-category-table td {
            border: 1px solid #e6e8ef;
            padding: 12px 14px;
            vertical-align: top;
            color: #475467;
        }

        .cookie-category-table th {
            background: #fff6f6;
            color: #172033;
            font-weight: 700;
        }

        @media (max-width: 767px) {
            .cookie-policy-card {
                padding: 0;
                box-shadow: none;
                border-radius: 0;
            }

            .cookie-category-table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }
        }
    </style>

    <section class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="cookie-policy-card">
                        <h1 class="fw-bold mb-3">Cookie Policy</h1>
                        <p>
                            This Cookie Policy explains how Archer Chess Academy uses cookies and similar technologies on
                            its website and landing pages. It should be read with our
                            <a href="{{ route('privacy') }}">Privacy Policy</a>.
                        </p>

                        <h2>What are cookies?</h2>
                        <p>
                            Cookies are small files placed on your browser or device. Some cookies are required for the
                            website to work, while optional cookies help us measure website performance and advertising.
                        </p>

                        <h2>Cookie categories we use</h2>
                        <table class="cookie-category-table">
                            <thead>
                                <tr>
                                    <th>Category</th>
                                    <th>Purpose</th>
                                    <th>Consent requirement</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Necessary</td>
                                    <td>Used for core website functions such as security, page navigation, forms, and saved preferences.</td>
                                    <td>Always active because the site cannot work properly without them.</td>
                                </tr>
                                <tr>
                                    <td>Analytics</td>
                                    <td>Used to understand page visits, campaign performance, and user interaction so we can improve the website.</td>
                                    <td>Loaded only after you give consent.</td>
                                </tr>
                                <tr>
                                    <td>Marketing</td>
                                    <td>Used to measure advertising campaigns and support relevant follow-up communication.</td>
                                    <td>Loaded only after you give consent.</td>
                                </tr>
                            </tbody>
                        </table>

                        <h2>Third-party tools</h2>
                        <p>
                            We may use Google Tag Manager to manage analytics and marketing tags. These optional tags are
                            blocked until you give consent through the cookie banner or Cookie Settings.
                        </p>

                        <h2>How to change your choice</h2>
                        <p>
                            You can update or withdraw consent anytime by opening
                            <a href="javascript:void(0)" data-cookie-settings>Cookie Settings</a>. You can also delete or
                            block cookies from your browser settings.
                        </p>

                        <h2>Policy updates</h2>
                        <p>
                            We may update this policy when our website, tools, or legal requirements change. The latest
                            version will be available on this page.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
