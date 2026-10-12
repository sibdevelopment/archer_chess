# Frontend Tracking, UTM, And Cookie Consent

## Tracking

Frontend layouts and country landing pages use Google Tag Manager container `GTM-KCPKNMQ`. Older duplicate conversion/tracking snippets were removed so the common layout or consent loader owns GTM startup.

## UTM Capture

Trial booking forms read:

```text
utm_source
utm_medium
```

Values are taken from the URL, retained in browser session storage, copied into hidden form inputs, and persisted on `demoleadenquiries` by `HomeController`.

## Cookie Consent

Main files:

```text
resources/views/Frontend/partials/cookie-consent.blade.php
resources/views/Frontend/Design/Policy/cookie.blade.php
resources/views/Frontend/Design/Policy/privacy.blade.php
resources/views/layouts/frontend.blade.php
resources/views/layouts/revamp.blade.php
resources/views/Frontend/country.blade.php
resources/views/Frontend/india.blade.php
```

The preference UI supports necessary, analytics, and marketing categories. Necessary cookies are always enabled. Optional consent defaults to denied on consent-controlled pages.

Preferences are stored under:

```text
localStorage: archer_cookie_consent_v1
cookie: archer_cookie_consent
```

The cookie expires after one year. Accept, reject, customize, and reopen-settings flows update Google Consent Mode. GTM loads only after optional consent where consent is required.

## Regional Behavior

| Surface | Consent banner | GTM before choice |
| --- | --- | --- |
| Main website/layout pages | Yes, all regions | No |
| UK landing page | Yes | No |
| European Union landing page | Yes | No |
| Other country landing pages | No | Yes |
| India landing page | No | Yes |

Country matching uses normalized application country values. Any new UK/EU alias must be added consistently to centralized country helpers and the landing-page consent condition.

## Policy Routes

```text
/cookie/policy
/design/policy/cookie
```
