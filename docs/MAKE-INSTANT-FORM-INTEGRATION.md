# Make Meta Instant Form Integration

## Flow

1. Make watches the Facebook Page and Instant Form for new leads.
2. Make maps the form answers into the JSON contract below.
3. Make sends an authenticated POST request to Archer ERP.
4. ERP creates an active Lead Enquiry. Repeated delivery of the same Meta Lead ID does not create a duplicate.

## Endpoint

```http
POST /api/integrations/make/meta-instant-leads
Authorization: Bearer {MAKE_INSTANT_FORM_SECRET}
Content-Type: application/json
Accept: application/json
```

Development URL:

```text
https://dev.archerchessacademy.com/api/integrations/make/meta-instant-leads
```

## Payload

```json
{
  "meta_lead_id": "12345678901234567",
  "created_time": "2026-10-27T08:15:00Z",
  "form_id": "987654321",
  "form_name": "UK Free Trial",
  "page_id": "123456789",
  "kids_first_name": "Aarav",
  "kids_last_name": "Shah",
  "parent_name": "Ravi Shah",
  "email": "parent@example.com",
  "phone": "+447700900123",
  "country": "United Kingdom",
  "city": "London",
  "age": 9,
  "timezone": "British Summer Time",
  "language_preference": "English",
  "level": "Beginner",
  "campaign_id": "campaign-123",
  "campaign_name": "UK Trial Campaign",
  "adset_id": "adset-123",
  "adset_name": "UK Parents",
  "ad_id": "ad-123",
  "ad_name": "Free Trial"
}
```

Required fields are `meta_lead_id`, `form_id`, `email`, `phone`, `country`, and either `kids_first_name` or `full_name`. Make may send the aliases `lead_id` or `leadgen_id` for `meta_lead_id`, and `phone_number` or `mobile` for `phone`.

Send phone numbers in E.164 format when possible, such as `+447700900123`. If the form returns only a national number, also send `country_code`, such as `+44`.

## Responses

- `201`: a new Lead Enquiry was created.
- `200`: the Meta Lead ID had already been received.
- `401`: the bearer token is missing or invalid.
- `422`: required data is missing or invalid.
- `503`: the ERP environment does not have the integration secret configured.

The bearer token must be shared separately through a secure channel and must not be included in email, screenshots, or the Make scenario name.
