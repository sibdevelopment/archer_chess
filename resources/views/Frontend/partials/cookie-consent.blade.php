<style>
    .cookie-consent-panel {
        position: fixed;
        left: 24px;
        right: 24px;
        bottom: 24px;
        z-index: 99999;
        display: none;
        max-width: 980px;
        margin: 0 auto;
        background: #ffffff;
        border: 1px solid #e6e8ef;
        border-radius: 10px;
        box-shadow: 0 18px 55px rgba(15, 23, 42, 0.22);
        color: #172033;
    }

    .cookie-consent-panel.is-visible,
    .cookie-preference-modal.is-visible {
        display: block;
    }

    .cookie-consent-inner {
        display: grid;
        grid-template-columns: 1fr auto;
        gap: 18px;
        align-items: center;
        padding: 20px 22px;
    }

    .cookie-consent-title {
        margin: 0 0 6px;
        font-size: 18px;
        font-weight: 700;
        color: #101828;
    }

    .cookie-consent-text {
        margin: 0;
        font-size: 14px;
        line-height: 1.55;
        color: #475467;
    }

    .cookie-consent-text a,
    .cookie-preference-modal a {
        color: #ff4d4f;
        text-decoration: underline;
    }

    .cookie-consent-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        justify-content: flex-end;
    }

    .cookie-btn {
        border: 1px solid #d0d5dd;
        border-radius: 6px;
        background: #ffffff;
        color: #344054;
        cursor: pointer;
        font-size: 14px;
        font-weight: 700;
        line-height: 1;
        min-height: 42px;
        padding: 12px 16px;
    }

    .cookie-btn-primary {
        border-color: #ff4d4f;
        background: #ff4d4f;
        color: #ffffff;
    }

    .cookie-btn-link {
        border: 0;
        background: transparent;
        color: #344054;
        text-decoration: underline;
    }

    .cookie-preference-modal {
        position: fixed;
        inset: 0;
        z-index: 100000;
        display: none;
        background: rgba(15, 23, 42, 0.58);
        padding: 24px;
        overflow-y: auto;
    }

    .cookie-preference-dialog {
        max-width: 640px;
        margin: 8vh auto;
        background: #ffffff;
        border-radius: 10px;
        box-shadow: 0 20px 70px rgba(15, 23, 42, 0.28);
        overflow: hidden;
    }

    .cookie-preference-header,
    .cookie-preference-footer {
        padding: 18px 22px;
        border-bottom: 1px solid #edf0f5;
    }

    .cookie-preference-footer {
        border-top: 1px solid #edf0f5;
        border-bottom: 0;
        display: flex;
        flex-wrap: wrap;
        justify-content: flex-end;
        gap: 10px;
    }

    .cookie-preference-body {
        padding: 18px 22px;
    }

    .cookie-option {
        display: flex;
        justify-content: space-between;
        gap: 18px;
        padding: 16px 0;
        border-bottom: 1px solid #edf0f5;
    }

    .cookie-option:last-child {
        border-bottom: 0;
    }

    .cookie-option h3 {
        margin: 0 0 5px;
        font-size: 16px;
        color: #101828;
    }

    .cookie-option p {
        margin: 0;
        font-size: 14px;
        line-height: 1.5;
        color: #475467;
    }

    .cookie-switch {
        flex: 0 0 auto;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        color: #344054;
    }

    .cookie-switch input {
        width: 18px;
        height: 18px;
    }

    @media (max-width: 767px) {
        .cookie-consent-panel {
            left: 12px;
            right: 12px;
            bottom: 12px;
        }

        .cookie-consent-inner {
            grid-template-columns: 1fr;
        }

        .cookie-consent-actions,
        .cookie-preference-footer {
            justify-content: stretch;
        }

        .cookie-btn {
            flex: 1 1 auto;
        }

        .cookie-option {
            flex-direction: column;
        }
    }
</style>

<div class="cookie-consent-panel" id="cookieConsentPanel" role="dialog" aria-live="polite" aria-label="Cookie consent">
    <div class="cookie-consent-inner">
        <div>
            <p class="cookie-consent-title">Cookie preferences</p>
            <p class="cookie-consent-text">
                We use essential cookies to run this website. Analytics and marketing cookies are used only after your
                consent. You can change your choice anytime from Cookie Settings.
                <a href="{{ route('cookie.policy') }}">Cookie Policy</a>
            </p>
        </div>
        <div class="cookie-consent-actions">
            <button type="button" class="cookie-btn cookie-btn-link" data-cookie-open-preferences>Manage preferences</button>
            <button type="button" class="cookie-btn" data-cookie-reject>Reject all</button>
            <button type="button" class="cookie-btn cookie-btn-primary" data-cookie-accept>Accept all</button>
        </div>
    </div>
</div>

<div class="cookie-preference-modal" id="cookiePreferenceModal" role="dialog" aria-modal="true" aria-labelledby="cookiePreferenceTitle">
    <div class="cookie-preference-dialog">
        <div class="cookie-preference-header">
            <h2 id="cookiePreferenceTitle" class="cookie-consent-title">Manage cookie preferences</h2>
            <p class="cookie-consent-text">Choose which optional cookies Archer Chess Academy can use on this browser.</p>
        </div>
        <div class="cookie-preference-body">
            <div class="cookie-option">
                <div>
                    <h3>Necessary cookies</h3>
                    <p>Required for core website features such as page navigation, forms, and security.</p>
                </div>
                <label class="cookie-switch">
                    <input type="checkbox" checked disabled>
                    Always on
                </label>
            </div>
            <div class="cookie-option">
                <div>
                    <h3>Analytics cookies</h3>
                    <p>Help us understand visitor activity so we can improve the website and landing pages.</p>
                </div>
                <label class="cookie-switch">
                    <input type="checkbox" id="cookieAnalyticsToggle">
                    Allow
                </label>
            </div>
            <div class="cookie-option">
                <div>
                    <h3>Marketing cookies</h3>
                    <p>Help measure advertising campaigns and show relevant follow-up messages.</p>
                </div>
                <label class="cookie-switch">
                    <input type="checkbox" id="cookieMarketingToggle">
                    Allow
                </label>
            </div>
            <p class="cookie-consent-text">
                Read more in our <a href="{{ route('cookie.policy') }}">Cookie Policy</a> and
                <a href="{{ route('privacy') }}">Privacy Policy</a>.
            </p>
        </div>
        <div class="cookie-preference-footer">
            <button type="button" class="cookie-btn" data-cookie-close-preferences>Cancel</button>
            <button type="button" class="cookie-btn" data-cookie-reject>Reject all</button>
            <button type="button" class="cookie-btn cookie-btn-primary" data-cookie-save>Save preferences</button>
        </div>
    </div>
</div>

<script>
    (function() {
        var consentKey = 'archer_cookie_consent_v1';
        var cookieName = 'archer_cookie_consent';
        var gtmId = 'GTM-KCPKNMQ';
        var gtmLoaded = false;
        var panel = document.getElementById('cookieConsentPanel');
        var modal = document.getElementById('cookiePreferenceModal');
        var analyticsToggle = document.getElementById('cookieAnalyticsToggle');
        var marketingToggle = document.getElementById('cookieMarketingToggle');

        window.dataLayer = window.dataLayer || [];
        window.gtag = window.gtag || function() {
            window.dataLayer.push(arguments);
        };

        window.gtag('consent', 'default', {
            ad_storage: 'denied',
            analytics_storage: 'denied',
            ad_user_data: 'denied',
            ad_personalization: 'denied'
        });

        function getStoredConsent() {
            try {
                var stored = window.localStorage.getItem(consentKey);
                if (stored) {
                    return JSON.parse(stored);
                }
            } catch (error) {
                return getCookieConsent();
            }

            return getCookieConsent();
        }

        function getCookieConsent() {
            var cookies = document.cookie ? document.cookie.split(';') : [];
            for (var index = 0; index < cookies.length; index++) {
                var cookie = cookies[index].trim();
                if (cookie.indexOf(cookieName + '=') === 0) {
                    try {
                        return JSON.parse(decodeURIComponent(cookie.substring(cookieName.length + 1)));
                    } catch (error) {
                        return null;
                    }
                }
            }

            return null;
        }

        function persistCookie(consent) {
            var expires = new Date();
            expires.setFullYear(expires.getFullYear() + 1);
            document.cookie = cookieName + '=' + encodeURIComponent(JSON.stringify(consent)) +
                '; expires=' + expires.toUTCString() + '; path=/; SameSite=Lax';
        }

        function saveConsent(consent) {
            var payload = {
                necessary: true,
                analytics: Boolean(consent.analytics),
                marketing: Boolean(consent.marketing),
                savedAt: new Date().toISOString()
            };

            try {
                window.localStorage.setItem(consentKey, JSON.stringify(payload));
            } catch (error) {}

            persistCookie(payload);
            updateConsentMode(payload);
            if (payload.analytics || payload.marketing) {
                loadGtm();
            }
            hidePanel();
            closePreferences();
        }

        function updateConsentMode(consent) {
            window.gtag('consent', 'update', {
                analytics_storage: consent.analytics ? 'granted' : 'denied',
                ad_storage: consent.marketing ? 'granted' : 'denied',
                ad_user_data: consent.marketing ? 'granted' : 'denied',
                ad_personalization: consent.marketing ? 'granted' : 'denied'
            });
        }

        function loadGtm() {
            if (gtmLoaded || document.getElementById('archer-gtm-script')) {
                gtmLoaded = true;
                return;
            }

            gtmLoaded = true;
            window.dataLayer.push({
                'gtm.start': new Date().getTime(),
                event: 'gtm.js'
            });

            var firstScript = document.getElementsByTagName('script')[0];
            var script = document.createElement('script');
            script.id = 'archer-gtm-script';
            script.async = true;
            script.src = 'https://www.googletagmanager.com/gtm.js?id=' + gtmId;
            firstScript.parentNode.insertBefore(script, firstScript);
        }

        function showPanel() {
            if (panel) {
                panel.classList.add('is-visible');
            }
        }

        function hidePanel() {
            if (panel) {
                panel.classList.remove('is-visible');
            }
        }

        function openPreferences() {
            var consent = getStoredConsent();
            analyticsToggle.checked = consent ? Boolean(consent.analytics) : false;
            marketingToggle.checked = consent ? Boolean(consent.marketing) : false;
            if (modal) {
                modal.classList.add('is-visible');
            }
        }

        function closePreferences() {
            if (modal) {
                modal.classList.remove('is-visible');
            }
        }

        document.querySelectorAll('[data-cookie-accept]').forEach(function(button) {
            button.addEventListener('click', function() {
                saveConsent({ analytics: true, marketing: true });
            });
        });

        document.querySelectorAll('[data-cookie-reject]').forEach(function(button) {
            button.addEventListener('click', function() {
                saveConsent({ analytics: false, marketing: false });
            });
        });

        document.querySelectorAll('[data-cookie-open-preferences]').forEach(function(button) {
            button.addEventListener('click', openPreferences);
        });

        document.querySelectorAll('[data-cookie-close-preferences]').forEach(function(button) {
            button.addEventListener('click', closePreferences);
        });

        document.querySelectorAll('[data-cookie-save]').forEach(function(button) {
            button.addEventListener('click', function() {
                saveConsent({
                    analytics: analyticsToggle.checked,
                    marketing: marketingToggle.checked
                });
            });
        });

        document.addEventListener('click', function(event) {
            var trigger = event.target.closest('[data-cookie-settings]');
            if (trigger) {
                event.preventDefault();
                openPreferences();
            }
        });

        window.ArcherCookieConsent = {
            openPreferences: openPreferences
        };

        var existingConsent = getStoredConsent();
        if (existingConsent) {
            updateConsentMode(existingConsent);
            if (existingConsent.analytics || existingConsent.marketing) {
                loadGtm();
            }
        } else {
            showPanel();
        }
    })();
</script>
